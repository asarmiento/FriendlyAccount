<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\AuxiliaryReceipt;
use AccountHon\Entities\Cash;
use AccountHon\Entities\Deposit;
use AccountHon\Entities\FinancialRecords;
use AccountHon\Entities\Student;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Http\Requests;
use AccountHon\Repositories\AuxiliaryReceiptRepository;
use AccountHon\Repositories\CashRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\DepositRepository;
use AccountHon\Repositories\FinancialRecordsRepository;
use AccountHon\Repositories\SchoolsRepository;
use AccountHon\Repositories\StudentRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Jenssegers\Date\Date;
use \Crypt;

class AuxiliaryReceiptController extends Controller
{
    use Convert;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var FinancialRecordsRepository
     */
    private $financialRecordsRepository;
    /**
     * @var AuxiliaryReceiptRepository
     */
    private $auxiliaryReceiptRepository;
    /**
     * @var StudentRepository
     */
    private $studentRepository;
    /**
     * @var DepositRepository
     */
    private $depositRepository;
    /**
     * @var CashRepository
     */
    private $cashRepository;
    /**
     * @var SchoolsRepository
     */
    private $schoolsRepository;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;

    /**
     * @param TypeFormRepository $typeFormRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param FinancialRecordsRepository $financialRecordsRepository
     * @param AuxiliaryReceiptRepository $auxiliaryReceiptRepository
     * @param StudentRepository $studentRepository
     * @param DepositRepository $depositRepository
     * @param CashRepository $cashRepository
     * @param SchoolsRepository $schoolsRepository
     * @param CatalogRepository $catalogRepository
     */
    public function __construct(
        TypeFormRepository $typeFormRepository,
        TypeSeatRepository $typeSeatRepository,
        FinancialRecordsRepository $financialRecordsRepository,
        AuxiliaryReceiptRepository $auxiliaryReceiptRepository,
        StudentRepository $studentRepository,
        DepositRepository $depositRepository,
        CashRepository $cashRepository,
        SchoolsRepository $schoolsRepository,
        CatalogRepository $catalogRepository
    )
    {
        $this->middleware('auth');

        $this->typeFormRepository = $typeFormRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->financialRecordsRepository = $financialRecordsRepository;
        $this->auxiliaryReceiptRepository = $auxiliaryReceiptRepository;
        $this->studentRepository = $studentRepository;
        $this->depositRepository = $depositRepository;
        $this->cashRepository = $cashRepository;
        $this->schoolsRepository = $schoolsRepository;
        $this->catalogRepository = $catalogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $schools_ids = $this->studentRepository->listsOrder('id','id');
        $financialRecords_ids = $this->financialRecordsRepository->whereInList('student_id', $schools_ids, 'id');
        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->getModel()->with('financialRecords')->with('accountingPeriods')
            ->where('status', 'aplicado')->whereIn('financial_record_id', $financialRecords_ids)->orderBy('id','DESC')->limit(600)->get();

        return View('auxiliaryReceipts.index', compact('auxiliaryReceipts'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-08-05
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |@Pasos:
    |
    |
    |
    |
    |
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function create()
    {
        $types = $this->typeFormRepository->getModel()->all();
        $typeSeat = $this->typeSeatRepository->whereDuoData('RCA');
        if ($typeSeat->isEmpty()):
            abort(503);
        endif;
        $total = 0;
        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->whereDuo('status', 'no aplicado', 'type_seat_id', $typeSeat[0]->id, 'id', 'ASC');

        /**pendiente solo debe enviar los estudiantes de la institucion*/
        if (!$auxiliaryReceipts->isEmpty()):
            $total = $this->auxiliaryReceiptRepository->getModel()->where('type_seat_id', $typeSeat[0]->id)->where('receipt_number', $auxiliaryReceipts[0]->receipt_number)->sum('amount');
        endif;
        $financialRecords = $this->financialRecordsRepository->oneWhere('year', periodSchool()->year, 'year');
        $financialRecordsAfter = $this->financialRecordsRepository->getModel()->where('year', '<=', (periodSchool()->year - 1))->orderBy('year', 'DESC')->get();
        $banks = $this->catalogRepository->accountNameSchool('BANCOS');
         //= $financialRecords->orderBy('name',);
        return View('auxiliaryReceipts.create', compact('types', 'typeSeat', 'banks', 'financialRecords', 'auxiliaryReceipts', 'total', 'financialRecordsAfter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            $auxiliary = $this->convertionObjeto();
            $verification = $this->auxiliaryReceiptRepository->whereDuo('receipt_number', $auxiliary->receiptNumberAuxiliaryReceipt, 'accounting_period_id', periodSchool()->id, 'updated_at', 'ASC');

            if (!$verification->isEmpty()):
                if (count($verification) == 5):
                    return $this->errores(['message' => 'Solo se permiten 5 movimientos por recibos']);
                endif;
            endif;

            $Validation = $this->CreacionArray($auxiliary, 'AuxiliaryReceipt');
            $Validation['line'] = 1;
            if ($verification->count() > 0):
                $Validation['token'] = $verification[0]->token;
                $Validation['date'] = $verification[0]->date;
                $Validation['line'] = $verification[0]->line + 1;
            endif;
            $type = $this->typeFormRepository->oneWhere('name', 'Credito', 'id');
            $Validation['type_id'] = $type[0]->id;
            /* Creamos un array para cambiar nombres de parametros */
            $Validation['user_created'] = Auth::user()->id;
            $student = $this->studentRepository->token($Validation['financialRecord']);
            $Validation['financial_record_id'] = $student->financialRecords->id;
            $Validation['status'] = 'no aplicado';
            $Validation['receipt_number'] = $Validation['receiptNumber'];
            $Validation['received_from'] = $Validation['receivedFrom'];
            $Validation['accounting_period_id'] = periodSchool()->id;
            $typeSeat = $this->typeSeatRepository->whereDuoData('RCA');
            $Validation['type_seat_id'] = $typeSeat[0]->id;

            /* Declaramos las clases a utilizar */
            $auxiliarys = $this->auxiliaryReceiptRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($auxiliarys->isValid($Validation)):
                $auxiliarys->fill($Validation);
                $auxiliarys->save();

                $total = $this->auxiliaryReceiptRepository->getModel()->where('receipt_number', $auxiliarys->receipt_number)->sum('amount');
                return $this->exito(['token' => $Validation['token'], 'id' => $auxiliarys->id, 'total' => $total]);
            endif;
            /* Enviamos el mensaje de error */
            return $this->errores($auxiliarys->errors);
        } catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('auxiliaryReceipt Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 17/07/16 06:51 PM   @Update 0000-00-00
     ***************************************************
     * @Description: En esta vista cambiamos los recibos
     * de no aplicado -> Aplicado, luego registramos en
     * la tabla de deposits todos las boletas de cada
     * uno de los depositos, y ahora le agregmos el ID
     * de la cuenta de catalogos. y lo faltante se guarda
     * en la tabla de cashs
     * @Pasos:
     *
     * @return Response
     ***************************************************@internal param int $id
     */
    public function status()
    {
        try {
            $deposits = $this->convertionObjeto();
            $auxiliaryReceipt = $this->auxiliaryReceiptRepository->token($deposits->token);

            //array depositos validos
            $deposits_valids = array();

            $sumDeposit = 0;

            //Validando el total
            $total = $this->auxiliaryReceiptRepository->getModel()->where('receipt_number', $auxiliaryReceipt->receipt_number)->sum('amount');

            if ($deposits->numberDepositAuxiliaryReceipt[0] != null || $deposits->numberDepositAuxiliaryReceipt[0] != '') {
                //Validación de datos
                for ($i = 0; $i < count($deposits->numberDepositAuxiliaryReceipt); $i++):
                    $validation = array('number' => $deposits->numberDepositAuxiliaryReceipt[$i],
                        'date' => $deposits->dateDepositAuxiliaryReceipt[$i],
                        'catalog_id' => $this->catalogRepository->token($deposits->accountDepositAuxiliaryReceipt[$i])->id,
                        'amount' => $deposits->amountDepositAuxiliaryReceipt[$i],
                        'school_id' => userSchool()->id,
                        'token' => Crypt::encrypt($deposits->numberDepositAuxiliaryReceipt[$i]),
                        'codeReceipt' => $auxiliaryReceipt->receipt_number
                    );
                    $deposit = $this->depositRepository->getModel();

                    if ($deposit->isValid($validation)):
                        array_push($deposits_valids, $validation);
                    else:
                        return $this->errores($deposit->errors);
                    endif;
                endfor;

                //validate date and number
                if (!$this->validateDeposits($deposits)) {
                    return $this->errores('No se pueden registrar los datos, existen depósitos duplicados.');
                }
                foreach ($deposits->amountDepositAuxiliaryReceipt AS $suma):
                    $sumDeposit += $suma;
                endforeach;

                if ($total < $sumDeposit):
                    return $this->errores(array('auxiliaryReceipt Save' => 'Los depositos no pueden ser de mayor cantidad que el recibo'));
                endif;

                DB::beginTransaction();
                foreach ($deposits_valids as $key => $value) {
                    $deposit = $this->depositRepository->getModel();
                    $deposit->fill($value);
                    if ($deposit->save()) {
                        $validate = true;
                    } else {
                        $validate = false;
                    }
                }
            }

            if (isset($validate) && !$validate) {
                DB::rollback();
                return $this->errores('No se pudieron grabar los depósitos');
            }

            if ($total > $sumDeposit):
                $diferent = $total - $sumDeposit;
                $cash = $this->cashRepository->getModel();
                $cashs = ['amount' => $diferent, 'receipt' => $auxiliaryReceipt->receipt_number, 'school_id' => userSchool()->id];
                if ($cash->isValid($cashs)):
                    $cash->fill($cashs);
                    if ($cash->save()) {
                        $validate = true;
                    } else {
                        $validate = false;
                    }
                endif;
            endif;

            if (!$validate) {
                DB::rollback();
                Log::error('No se pudo grabar el efectivo ' . __CLASS__ . ', método ' . __METHOD__ . '.');
                return $this->errores('No se pudo grabar el efectivo');
            }

            if (!$this->updateBalance($deposits->token)) {
                DB::rollback();
                Log::error('Error al guardar el balance');
                return $this->errores('No se pudo grabar el Recibo');
            }

            $auxiliary = $this->auxiliaryReceiptRepository->updateWhere($deposits->token, 'aplicado', 'status');
            if ($auxiliary > 0) {
                if ($this->typeSeatRepository->updateWhere('RCA') > 0) {
                    $this->sendEmail($auxiliaryReceipt['id']);
                    DB::commit();
                    return $this->exito(['msg' => "Se ha aplicado con exito!!!", 'data' => $this->report($deposits->token)]);;
                }
            } else {
                DB::rollback();
                Log::error('No se puede aplicar el status a aplicado ' . __CLASS__ . ', método ' . __METHOD__ . '.');
                return $this->errores('No se puede aplicar el asiento, si persiste contacte soporte');
            }
            DB::rollback();
            return $this->errores('No se puedo Aplicar el asiento, si persiste contacte soporte');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return $this->errores(array('auxiliaryReceipt Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    public function updateBalance($token)
    {
        $seats = $this->auxiliaryReceiptRepository->getModel()->where('token', $token)->get();
        foreach ($seats AS $seat):
            $student = $this->financialRecordsRepository->saldoStudent($seat->financial_record_id);
            $balance = $student - $seat->amount;
            if ($this->financialRecordsRepository->updateData($seat->financial_record_id, 'balance', $balance) > 0) {
                $validate = true;
            } else {
                $validate = false;
            }
        endforeach;
        return $validate;
    }

    /**
     * [view description]
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function view($token)
    {
        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->oneWhere('token', $token, 'id');
        $deposits = $this->depositRepository->oneWhere('codeReceipt', $auxiliaryReceipts[0]->receipt_number, 'id');
        $deposits_numbers = '';
        if (!$deposits->isEmpty()) {
            foreach ($deposits as $key => $deposit) {
                $deposits_numbers .= $deposit->number . ', ';
            }
            $deposits_numbers = substr($deposits_numbers, 0, -2) . '.';
        }

        $total = $this->auxiliaryReceiptRepository->getModel()->where('receipt_number', $auxiliaryReceipts[0]->receipt_number)->sum('amount');
        return View('auxiliaryReceipts.view', compact('auxiliaryReceipts', 'total', 'deposits_numbers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function deleteDetail($id)
    {
        $auxiliary = $this->auxiliaryReceiptRepository->destroy($id);
        if ($auxiliary == 1):
            $aux = AuxiliaryReceipt::find($id);
            $cash = Cash::where('receipt', $aux->receipt_number)->where('school_id', userSchool()->id);
            if ($cash != null) {
                $cash->delete();
            }
            $dep = Deposit::where('receipt', $aux->receipt_number)->where('school_id', userSchool()->id)->delete();
            if ($dep != null) {
                $dep->delete();
            }
            $typeSeat = $this->typeSeatRepository->whereDuoData('RCA');
            $auxiliaryReceipts = $this->auxiliaryReceiptRepository->whereDuo('status', 'no aplicado', 'type_seat_id', $typeSeat[0]->id, 'id', 'ASC');
            $total = 0;

            if (!$auxiliaryReceipts->isEmpty()):
                $total = $this->auxiliaryReceiptRepository->getModel()->where('receipt_number', $auxiliaryReceipts[0]->receipt_number)->sum('amount');
            endif;
            return $this->exito(['total' => $total, 'message' => 'Se ha eliminado con éxito']);
        endif;
        return $this->errores(['No se puedo eliminar la fila, si persiste contacte soporte']);
    }

    private function validateDeposits($deposits)
    {
        $date = $deposits->dateDepositAuxiliaryReceipt;
        $ref = $deposits->numberDepositAuxiliaryReceipt;

        $duplicatesDate = $this->get_keys_for_duplicate_values($date);

        foreach ($duplicatesDate as $key => $position) {
            $auxRef = null;
            foreach ($position as $keyPos => $valuePos) {
                if ($ref[$valuePos] == $auxRef) {
                    return false;
                }
                $auxRef = $ref[$valuePos];
            }
        }

        return true;
    }

    private function get_keys_for_duplicate_values($my_arr)
    {
        $duplicates = array_count_values($my_arr);

        $new_array = array();
        foreach ($duplicates as $key => $value) {
            if ($value > 1) {
                array_push($new_array, $key);
            }
        }

        $dups = array();
        foreach ($my_arr as $keyMy => $valueMy) {
            foreach ($new_array as $key => $value) {
                if ($value == $valueMy) {
                    if (isset($dups[$valueMy])) {
                        $dups[$valueMy][] = $keyMy;
                    } else {
                        $dups[$valueMy] = array($keyMy);
                    }
                }
            }
        }
        return $dups;
    }

    public function pdfReceipt($token)
    {

        $dataReceipt = $this->auxiliaryReceiptRepository->allId($token);
        $totalReceipt = $this->auxiliaryReceiptRepository->sumId($token);
        $deposits = $this->depositRepository->whereDuo('codeReceipt', $dataReceipt[0]->receipt_number, 'school_id', userSchool()->id, 'id', 'ASC');
        $deposits_numbers = '';
        if (!$deposits->isEmpty()) {
            foreach ($deposits as $key => $deposit) {
                $deposits_numbers .= $deposit->number . ', ';
            }
            $deposits_numbers = substr($deposits_numbers, 0, -2) . '.';
        }
        Date::setLocale('es');
        $fpdf = new Fpdf();
        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 16);
        $fpdf->Cell(40, 7, (utf8_decode(userSchool()->name)), 0, 1);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->Cell(40, 5, (utf8_decode(userSchool()->town)), 0, 1);
        $fpdf->Cell(40, 5, (utf8_decode('TEL: ' . userSchool()->phoneOne)), 0, 1);
        $fpdf->Cell(40, 5, (utf8_decode('Email: ' . userSchool()->email)), 0, 1);
        $fpdf->Rect(135, 20, 55, 10);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(150, 23);
        $fpdf->Cell(20, 5, (utf8_decode('RECIBO')), 0, 1, 'C');
        $fpdf->Rect(135, 30, 55, 10);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(140, 32);
        $fpdf->Cell(5, 5, (utf8_decode('N°')), 0, 1);
        $fpdf->SetXY(160, 32);
        $fpdf->Cell(5, 5, ($dataReceipt[0]->receipt_number), 0, 1);

        $fpdf->Rect(5, 43, 85, 50);
        $fpdf->SetXY(10, 45);
        $fpdf->Cell(5, 5, ('Cliente: '), 0, 1);
        $fpdf->Cell(5, 5, utf8_decode($dataReceipt[0]->received_from), 0, 1);

        $fpdf->Rect(93, 43, 55, 20);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(95, 45);
        $fpdf->Cell(5, 5, utf8_decode('Lugar y Fecha de expedición: '), 0, 1);
        $fpdf->SetXY(100, 53);
        $fpdf->Cell(5, 5, utf8_decode(Carbon::now()->toDateString()), 0, 1);


        $fpdf->Rect(148, 43, 50, 20);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(150, 45);
        $fpdf->Cell(5, 5, utf8_decode('Vencimiento: '), 0, 1);
        $fpdf->SetXY(160, 53);
        $fpdf->Cell(5, 5, utf8_decode(Carbon::now()->toDateString()), 0, 1);

        $fpdf->Rect(93, 63, 55, 15);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(95, 65);
        $fpdf->Cell(5, 5, utf8_decode('Vendedor: '), 0, 1);
        $fpdf->SetXY(100, 70);
        $fpdf->Cell(5, 5, utf8_decode(Auth::user()->name), 0, 1);

        $fpdf->Rect(148, 63, 50, 15);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(150, 68);
        $fpdf->Cell(5, 5, utf8_decode('Condiciones: Efectivo'), 0, 1);

        $fpdf->Rect(93, 78, 55, 15);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(95, 83);
        $fpdf->Cell(5, 5, utf8_decode('Refer.: '), 0, 1);

        $fpdf->Rect(148, 78, 50, 15);

        $fpdf->SetXY(152, 83);
        $fpdf->Cell(5, 5, utf8_decode('Envío: Entrega'), 0, 1);

        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetXY(5, 96);
        $fpdf->Cell(23, 7, utf8_decode('Código'), 1, 0, 'C');
        $fpdf->Cell(100, 7, utf8_decode('Descripción'), 1, 0, 'C');
        $fpdf->Cell(20, 7, utf8_decode('Cantidad'), 1, 0, 'C');
        $fpdf->Cell(25, 7, utf8_decode('Precio Unit.'), 1, 0, 'C');
        $fpdf->Cell(25, 7, utf8_decode('Subtotal'), 1, 1, 'C');

        foreach ($dataReceipt as $receipt) {
            $fpdf->SetX(5);
            $fpdf->SetFont('Arial', '', 10);
            $fpdf->Cell(23, 6, utf8_decode($receipt->financialRecords->students->book), 0, 0, 'C');
            $fpdf->Cell(100, 6, utf8_decode(convertTitle($receipt->detail)), 0, 0, 'C');
            $fpdf->Cell(20, 6, utf8_decode(1), 0, 0, 'C');
            $fpdf->Cell(25, 6, utf8_decode($receipt->amount), 0, 0, 'C');
            $fpdf->Cell(25, 6, utf8_decode($receipt->amount), 0, 1, 'C');
            $fpdf->Line(5, $fpdf->GetY(), 198, $fpdf->GetY());

        }


        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(165, $fpdf->GetY() + 3);
        $fpdf->Cell(20, 5, (utf8_decode('Subtotal: ') . $totalReceipt), 0, 1, 'C');


        $fpdf->Rect(140, $fpdf->GetY() + 20, 55, 10);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(150, $fpdf->GetY() + 22);
        $fpdf->Cell(20, 5, (utf8_decode('TOTAL') . $totalReceipt), 0, 1, 'C');
        $fpdf->Output();
        exit;

    }

    public function sendEmail($id)
    {

        $dataReceipt = $this->auxiliaryReceiptRepository->allId($id);

        $student = FinancialRecords::where('id', $dataReceipt[0]->financial_record_id)->first();
        $student = Student::where('id', $student->student_id)->first();
        $patch = public_path() . DIRECTORY_SEPARATOR . "email" . DIRECTORY_SEPARATOR . $dataReceipt[0]->receipt_number . ".pdf";

        $this->pdfReceiptPath($id, $patch);

        if ($student->emails != null) {
            Mail::send('emails.receipt', (array)$student, function ($q) use ($student, $dataReceipt, $patch) {
                $q->subject($dataReceipt[0]->receipt_number);
                $q->to($student->emails);
                $q->attach($patch, [
                    'as' => 'Recibo' . '-' . $dataReceipt[0]->receipt_number . '.pdf',
                    'mime' => 'application/pdf',
                ]);
            });

        }
        return \redirect()->route('ver-recibos-auxiliares');
    }

    public function pdfReceiptPath($token, $path = null)
    {

        $dataReceipt = $this->auxiliaryReceiptRepository->allId($token);

        $totalReceipt = $this->auxiliaryReceiptRepository->sumId($token);

        $deposits = $this->depositRepository->whereDuo('codeReceipt', $dataReceipt[0]->receipt_number, 'school_id', userSchool()->id, 'id', 'ASC');
        $deposits_numbers = '';
        if (!$deposits->isEmpty()) {
            foreach ($deposits as $key => $deposit) {
                $deposits_numbers .= $deposit->number . ', ';
            }
            $deposits_numbers = substr($deposits_numbers, 0, -2) . '.';
        }
        Date::setLocale('es');
        $fpdf = new Fpdf();
        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 16);
        $fpdf->Cell(40, 7, (utf8_decode(userSchool()->name)), 0, 1);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->Cell(40, 5, (utf8_decode(userSchool()->town)), 0, 1);
        $fpdf->Cell(40, 5, (utf8_decode('TEL: ' . userSchool()->phoneOne)), 0, 1);
        $fpdf->Cell(40, 5, (utf8_decode('Email: ' . userSchool()->email)), 0, 1);
        $fpdf->Rect(135, 20, 55, 10);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(150, 23);
        $fpdf->Cell(20, 5, (utf8_decode('RECIBO')), 0, 1, 'C');
        $fpdf->Rect(135, 30, 55, 10);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(140, 32);
        $fpdf->Cell(5, 5, (utf8_decode('N°')), 0, 1);
        $fpdf->SetXY(160, 32);
        $fpdf->Cell(5, 5, ($dataReceipt[0]->receipt_number), 0, 1);

        $fpdf->Rect(5, 43, 85, 50);
        $fpdf->SetXY(10, 45);
        $fpdf->Cell(5, 5, ('Cliente: '), 0, 1);
        $fpdf->Cell(5, 5, utf8_decode($dataReceipt[0]->received_from), 0, 1);

        $fpdf->Rect(93, 43, 55, 20);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(95, 45);
        $fpdf->Cell(5, 5, utf8_decode('Lugar y Fecha de expedición: '), 0, 1);
        $fpdf->SetXY(100, 53);
        $fpdf->Cell(5, 5, utf8_decode(Carbon::now()->toDateString()), 0, 1);


        $fpdf->Rect(148, 43, 50, 20);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(150, 45);
        $fpdf->Cell(5, 5, utf8_decode('Vencimiento: '), 0, 1);
        $fpdf->SetXY(160, 53);
        $fpdf->Cell(5, 5, utf8_decode(Carbon::now()->toDateString()), 0, 1);

        $fpdf->Rect(93, 63, 55, 15);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(95, 65);
        $fpdf->Cell(5, 5, utf8_decode('Vendedor: '), 0, 1);
        $fpdf->SetXY(100, 70);
        $fpdf->Cell(5, 5, utf8_decode(Auth::user()->name), 0, 1);

        $fpdf->Rect(148, 63, 50, 15);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(150, 68);
        $fpdf->Cell(5, 5, utf8_decode('Condiciones: Efectivo'), 0, 1);

        $fpdf->Rect(93, 78, 55, 15);

        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetXY(95, 83);
        $fpdf->Cell(5, 5, utf8_decode('Refer.: '), 0, 1);

        $fpdf->Rect(148, 78, 50, 15);

        $fpdf->SetXY(152, 83);
        $fpdf->Cell(5, 5, utf8_decode('Envío: Entrega'), 0, 1);

        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetXY(5, 96);
        $fpdf->Cell(23, 7, utf8_decode('Código'), 1, 0, 'C');
        $fpdf->Cell(100, 7, utf8_decode('Descripción'), 1, 0, 'C');
        $fpdf->Cell(20, 7, utf8_decode('Cantidad'), 1, 0, 'C');
        $fpdf->Cell(25, 7, utf8_decode('Precio Unit.'), 1, 0, 'C');
        $fpdf->Cell(25, 7, utf8_decode('Subtotal'), 1, 1, 'C');

        foreach ($dataReceipt as $receipt) {
            $fpdf->SetX(5);
            $fpdf->SetFont('Arial', '', 10);
            $fpdf->Cell(23, 6, utf8_decode($receipt->financialRecords->students->book), 0, 0, 'C');
            $fpdf->Cell(100, 6, utf8_decode(convertTitle($receipt->detail)), 0, 0, 'C');
            $fpdf->Cell(20, 6, utf8_decode(1), 0, 0, 'C');
            $fpdf->Cell(25, 6, utf8_decode($receipt->amount), 0, 0, 'C');
            $fpdf->Cell(25, 6, utf8_decode($receipt->amount), 0, 1, 'C');
            $fpdf->Line(5, $fpdf->GetY(), 198, $fpdf->GetY());

        }


        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(165, $fpdf->GetY() + 3);
        $fpdf->Cell(20, 5, (utf8_decode('Subtotal: ') . $totalReceipt), 0, 1, 'C');


        $fpdf->Rect(140, $fpdf->GetY() + 20, 55, 10);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetXY(150, $fpdf->GetY() + 22);
        $fpdf->Cell(20, 5, (utf8_decode('TOTAL') . $totalReceipt), 0, 1, 'C');
        if ($path) {
            $fpdf->Output($path, 'F', true);
        } else {
            $fpdf->Output('F', $dataReceipt[0]->receipt_number . '.pdf', true);
        }
        return true;

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function report($token)
    {
        $dataReceipt = $this->auxiliaryReceiptRepository->allToken($token);
        $totalReceipt = $this->auxiliaryReceiptRepository->sumToken($token);
        $deposits = $this->depositRepository->whereDuo('codeReceipt', $dataReceipt[0]->receipt_number, 'school_id', userSchool()->id, 'id', 'ASC');
        $deposits_numbers = '';
        if (!$deposits->isEmpty()) {
            foreach ($deposits as $key => $deposit) {
                $deposits_numbers .= $deposit->number . ', ';
            }
            $deposits_numbers = substr($deposits_numbers, 0, -2) . '.';
        }
        Date::setLocale('es');
        $date = Date::now()->format('j F Y');
        $totalReceipt = $totalReceipt;
        return view('auxiliaryReceipts.print', compact('dataReceipt', 'date', 'deposits_numbers', 'totalReceipt'))->render();
        //return View('auxiliaryReceipts.report',compact('dataReceipt','totalReceipt', 'deposits_numbers'));die;

        /*$pdf = \PDF::loadView('auxiliaryReceipts.report', compact('dataReceipt','totalReceipt', 'deposits_numbers'))->setOrientation('portrait');

        return $pdf->stream("Impresion - $dataReceipt[0]->receipt_number.pdf");*/
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-09-14   @Update 0000-00-00
     ***************************************************
     * @Description: Estamos anulando el recibo que se
     *   desea
     *
     *
     * @Pasos:
     *
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     **************************************************/
    public function anular($token)
    {

        $recibo = $this->auxiliaryReceiptRepository->token($token);

         Cash::where('receipt', $recibo->receipt_number)->update(['amount' => 0]);
        $this->depositRepository->getModel()->where('school_id', userSchool()->id)->where('codeReceipt', $recibo->receipt_number)
            ->update(['amount' => 0]);
        $this->cashRepository->getModel()->where('school_id', userSchool()->id)->where('receipt', $recibo->receipt_number)
            ->update(['amount' => 0]);
        $this->auxiliaryReceiptRepository->getModel()->where('token', $token)
            ->update(['amount' => 0, 'detail' => 'anulado']);

        return \redirect()->back();
    }
}
