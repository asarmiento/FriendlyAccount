<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\Cash;
use AccountHon\Entities\School;
use AccountHon\Repositories\AuxiliaryReceiptRepository;
use AccountHon\Repositories\AuxiliarySeatRepository;
use AccountHon\Repositories\CashRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\CourtCaseRepository;
use AccountHon\Repositories\DepositRepository;
use AccountHon\Repositories\FinancialRecordsRepository;
use AccountHon\Repositories\ReceiptRepository;
use AccountHon\Repositories\SeatingPartRepository;
use AccountHon\Repositories\SeatingRepository;
use AccountHon\Repositories\SettingRepository;
use AccountHon\Repositories\StudentRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use App;
use Carbon\Carbon;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use View;

class CourtCaseController extends Controller
{

    use Convert;
    /**
     * @var AuxiliaryReceiptRepository
     */
    private $auxiliaryReceiptRepository;

    /**
     * @var CatalogRepository
     */
    private $catalogRepository;

    /**
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * @var FinancialRecordsRepository
     */
    private $financialRecordsRepository;

    /**
     * @var CourtCaseRepository
     */
    private $courtCaseRepository;

    /**
     * @var DepositRepository
     */
    private $depositRepository;

    /**
     * @var CashRepository
     */
    private $cashRepository;

    /**
     * @var AuxiliarySeatRepository
     */
    private $auxiliarySeatRepository;

    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;

    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;

    /**
     * @var SeatingPartRepository
     */
    private $seatingPartRepository;

    /**
     * @var SeatingRepository
     */
    private $seatingRepository;
    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;

    /**
     * @param AuxiliaryReceiptRepository $auxiliaryReceiptRepository
     * @param CatalogRepository $catalogRepository
     * @param StudentRepository $studentRepository
     * @param FinancialRecordsRepository $financialRecordsRepository
     * @param CourtCaseRepository $courtCaseRepository
     * @param DepositRepository $depositRepository
     * @param CashRepository $cashRepository
     * @param AuxiliarySeatRepository $auxiliarySeatRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param SettingRepository $settingRepository
     * @param TypeFormRepository $typeFormRepository
     * @param SeatingPartRepository $seatingPartRepository
     * @param SeatingRepository $seatingRepository
     * @param ReceiptRepository $receiptRepository
     */
    public function __construct(
        AuxiliaryReceiptRepository $auxiliaryReceiptRepository,
        CatalogRepository $catalogRepository,
        StudentRepository $studentRepository,
        FinancialRecordsRepository $financialRecordsRepository,
        CourtCaseRepository $courtCaseRepository,
        DepositRepository $depositRepository,
        CashRepository $cashRepository,
        AuxiliarySeatRepository $auxiliarySeatRepository,
        TypeSeatRepository $typeSeatRepository,
        SettingRepository $settingRepository,
        TypeFormRepository $typeFormRepository,
        SeatingPartRepository $seatingPartRepository,
        SeatingRepository $seatingRepository,
        ReceiptRepository $receiptRepository
    )
    {
        $this->middleware('auth');
        $this->auxiliaryReceiptRepository = $auxiliaryReceiptRepository;
        $this->catalogRepository = $catalogRepository;
        $this->studentRepository = $studentRepository;
        $this->financialRecordsRepository = $financialRecordsRepository;
        $this->courtCaseRepository = $courtCaseRepository;
        $this->depositRepository = $depositRepository;
        $this->cashRepository = $cashRepository;
        $this->auxiliarySeatRepository = $auxiliarySeatRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->settingRepository = $settingRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->seatingPartRepository = $seatingPartRepository;
        $this->seatingRepository = $seatingRepository;
        $this->receiptRepository = $receiptRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $courtCases = $this->courtCaseRepository->CourtCaseAll();
        $seating = $this->seatingRepository;
        return view('courtCases.index', compact('courtCases', 'seating'));
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 20/07/16 08:10 PM   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @return view
     ***************************************************/
    public function deposit()
    {
        $receipts = $this->listReceiptAccount();

        $cashAcount = $this->cashRepository->sumInSchool('receipt', $receipts);
        $depositAccoun = $this->depositRepository->sumInDuo('codeReceipt', $receipts, 'type', 'court');

        $auxiliaryReceipts = $this->listReceiptAxuliar();

        $cashAuxil = $this->cashRepository->sumInSchool('receipt', $auxiliaryReceipts);
        $depositAuxil = $this->depositRepository->sumInDuo('codeReceipt', $auxiliaryReceipts, 'type', 'court');
        $totalCashes = ($cashAcount + $cashAuxil) - ($depositAccoun + $depositAuxil);
	    $receiptsAll=[];
        if (($cashAcount + $cashAuxil) == 0):
            $totalCashes = 0;
        endif;
        $depositsAcc = $this->depositRepository->whereInSchool('codeReceipt', $receipts);
        $depositsAux = $this->depositRepository->whereInSchool('codeReceipt', $auxiliaryReceipts);
        foreach ($receipts AS $receipt):
            $receiptsAll[] = $receipt;


        endforeach;
        foreach ($auxiliaryReceipts AS $auxiliaryReceipt):
            $receiptsAll[] = $auxiliaryReceipt;

        endforeach;
        $banks = $this->catalogRepository->accountNameSchool('BANCOS');

        return view('courtCases.deposit', compact('receipts', 'auxiliaryReceipts', 'totalCashes', 'banks', 'receiptsAll', 'depositsAcc', 'depositsAux'));
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 21/07/16 12:37 PM   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @return Json
     ***************************************************/
    public function deleteDeposit()
    {
        $token = $this->convertionObjeto();
        $this->depositRepository->token($token->token)->delete();
        $receipts = $this->listReceiptAccount();
        $auxiliaryReceipts = $this->listReceiptAxuliar();
        $this->cashRepository->whereAllType('receipt', $receipts)->restore();
        $this->cashRepository->whereAllType('receipt', $auxiliaryReceipts)->restore();
        return $this->exito('Se elimino con exito');
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 20/07/16 09:22 PM   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @return Lists
     ***************************************************/
    private function listReceiptAxuliar()
    {
        $schools_ids = $this->studentRepository->lists('id');
        $financialRecords_ids = $this->financialRecordsRepository->whereInList('student_id', $schools_ids, 'id');
        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->dataCourCase($financialRecords_ids)->lists('receipt_number');

        return $auxiliaryReceipts;
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 20/07/16 09:20 PM   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @return Lists
     ***************************************************/
    private function listReceiptAccount()
    {
        $receipts = $this->receiptRepository->dataCourCase()->lists('receipt_number');
        return $receipts;
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 20/07/16 09:18 PM   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @return Json
     ***************************************************/
    public function saveDeposit()
    {
        $dataDeposit = $this->convertionObjeto();

        $deposit = $this->CreacionArray($dataDeposit, 'Deposit');
        $deposit['catalog_id'] = $this->catalogRepository->token($deposit['bank'])->id;
        $deposit['type'] = 'court';
        $deposits = $this->depositRepository->getModel();

        if ($deposits->isValid($deposit)):
            $deposits->fill($deposit);
            $deposits->save();

            return $this->exito('Se guardo con Exito el Deposito');
        endif;

        return $this->errores($deposits->errors);
    }

    /**
     * |---------------------------------------------------------------------
     * |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
     * |@Date Create: 2015-08-15
     * |@Date Update: 2015-00-00
     * |---------------------------------------------------------------------
     * |@Description: En esta vistas generamos un cuadro donde se verifica
     * |   el corte de caja y se le da aplicar si esta todo correcto
     * |
     * |@Pasos:
     * |
     * |
     * |
     * |
     * |
     * |
     * |----------------------------------------------------------------------
     * | @return mixed
     * |----------------------------------------------------------------------
     */
    public function create()
    {
        $receipts = $this->receiptRepository->dataCourCase()->get();
        $numbers_receipts = $this->receiptRepository->dataCourCase()->lists('receipt_number');

        // $this->cashRepository->whereAllType('receipt', $numbers_receipts)->delete();
        Cash::where('school_id', userSchool()->id)->whereIn('receipt', $numbers_receipts)->update(['deleted_at' => Carbon::now()->toDateString()]);
        //   DB::delete("DELETE FROM cashes WHERE school_id = ". userSchool()->id . " AND  receipt IN ('".$numbers_receipts[0] ."' , '".$numbers_receipts[1]."')");

        $sum_deposits_receipts = $this->depositRepository->sumInSchool('codeReceipt', $numbers_receipts);
        $sum_cashes_receipts = $this->cashRepository->sumInSchool('receipt', $numbers_receipts);


        $financialRecords_ids = $this->financialRecordsRepository->listStudent()->lists('id');

        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->whereDuoIn('court_case_id', null, 'status', 'aplicado', 'financial_record_id', $financialRecords_ids);
        $numbers_auxiliaryReceipts = $this->auxiliaryReceiptRepository->whereDuoInListDistinct('court_case_id', null, 'status', 'aplicado', 'financial_record_id', $financialRecords_ids, 'receipt_number');
        //$this->cashRepository->whereAllType('receipt', $numbers_auxiliaryReceipts)->delete();

        Cash::where('school_id', userSchool()->id)->whereIn('receipt', $numbers_auxiliaryReceipts)->update(['deleted_at' => Carbon::now()->toDateString()]);
        //  DB::delete("DELETE FROM cashes WHERE school_id = ".userSchool()->id ." AND  receipt IN ( '".$numbers_auxiliaryReceipts[0]."' , '".$numbers_auxiliaryReceipts[1]."' )");

        $sum_deposits_auxiliaryReceipts = $this->depositRepository->sumInSchool('codeReceipt', $numbers_auxiliaryReceipts);
        $sum_cashes_auxiliaryReceipts = $this->cashRepository->sumInSchool('receipt', $numbers_auxiliaryReceipts);

        $sum_deposits = $sum_deposits_receipts + $sum_deposits_auxiliaryReceipts;
        $sum_cashes = $sum_cashes_receipts + $sum_cashes_auxiliaryReceipts;

        $sum_total = $sum_deposits + $sum_cashes;

        return view('courtCases.create', compact('receipts', 'auxiliaryReceipts', 'sum_deposits', 'sum_cashes', 'sum_total'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-08-15
    |@Date Update: 2015-09-15
    |---------------------------------------------------------------------
    |@Description: Con esta accion ejecutamos cada uno de los script para
    |   poder realizar el corte de caja se utiliza tanto para el auxiliar
    |   como contabilidad si no existe registro simplemente lo omite.
    |@Pasos:
    |   1. Primero creamos el corte en la tabla de cortes de caja
    |   2. creamos el asiento del corte de caja en la tabla seatings para
    |      los recibos de contabilidad
    |   3. buscamos todos los recibos que seran parte del corte de caja
    |      tanto auxiliar y contabilidad
    |   4. actualizamos los recibos de contabilidad con el id del corte de
    |      caja que se creo.
    |   5. buscamos todos los recibos del auxiliar que seran parte del corte
    |      de caja
    |   6. creamos el asiento en la tabla auxiliarySeat el asiento de los
    |      alumnos segun los recibos
    |   7. actualizamos los recibos del auxiliar con el id del corte de caja
    |      que creamos.
    |   8. enviamos el token del corte de caja a la vista para que se genere
    |      los tres reportes a imprimir del corte.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function store()
    {
        try {
            # Paso 1
            $typeSeat = $this->typeSeatRepository->whereDuoData('CorCa');
            $CourtCases = ['date' => dateShort(), 'type_seat_id' => $typeSeat[0]->id, 'token' => Crypt::encrypt($typeSeat[0]->abbreviation()), 'abbreviation' => $typeSeat[0]->abbreviation()];
            \DB::beginTransaction();
            $courtCase = $this->courtCaseRepository->getModel();
            # Paso 2
            if ($courtCase->isValid($CourtCases)):
                $courtCase->fill($CourtCases);
                if ($courtCase->save()):

                    $token = Crypt::encrypt($courtCase->abbreviation);
                    # Paso 3 # Paso 4
                    $seatResult = $this->updateReceiptCourtCase($courtCase, $token);

                    if ($seatResult == false):
                        return $this->errores('Debe elegir configurar la cuenta para el Corte de Caja');
                    endif;
                    if(userSchool()->id != 37) {
                        # Paso 5
                        $schools_ids = $this->studentRepository->lists('id');
                        $financialRecords_ids = $this->financialRecordsRepository->whereInList('student_id', $schools_ids, 'id');
                        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->whereDuoIn('court_case_id', null, 'status', 'aplicado', 'financial_record_id', $financialRecords_ids);
                        $first = $this->auxiliaryReceiptRepository->whereDuoFirst('court_case_id', null, 'status', 'aplicado', 'financial_record_id', $financialRecords_ids);
                        $last = $this->auxiliaryReceiptRepository->whereDuoLast('court_case_id', null, 'status', 'aplicado', 'financial_record_id', $financialRecords_ids);
                        # Paso 6
                        $auxiliarSeatingCourtCase = $this->auxiliarSeat($courtCase, $auxiliaryReceipts, $first, $last, $token);
                        if ($auxiliarSeatingCourtCase):
                            # Paso 7
                            foreach ($auxiliaryReceipts AS $auxiliaryReceipt):
                                $this->auxiliaryReceiptRepository->updateDataWhere('receipt_number', $auxiliaryReceipt->receipt_number, 'court_case_id', $courtCase->id, $auxiliaryReceipt->type_seat_id);
                            endforeach;
                        else:
                            \DB::rollback();
                            return $this->errores($auxiliarSeatingCourtCase);
                        endif;
                    }
                endif;
                # Paso 8
                $this->typeSeatRepository->updateWhere('CorCa');
                \DB::commit();
                return $this->exito($courtCase->token);
            endif;
            return $this->errores(['save' => 'Se genero un error']);
        } catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('CourtCase Save' => 'Verificar la información del Corte de Caja, sino contactarse con soporte de la applicación'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function report($token)
    {
        $courtCase = $this->courtCaseRepository->token($token);
        $receipts = $this->receiptRepository->oneWhere('court_case_id', $courtCase->id, 'receipt_number');
        $auxiliaryReceipts = $this->auxiliaryReceiptRepository->oneWhere('court_case_id', $courtCase->id, 'receipt_number');
        $depositsRC = "";
        $sumDepositsRC = 0;
        $sumCashesRC = 0;
        $depositsRCA = "";
        $sumDepositsRCA = 0;
        $sumCashesRCA = 0;
        $cashesRC = "";
        $cashesRCA = "";
        $arrReceipt = array();
        $arrAuxilaryReceipt = array();
        if (!$receipts->isEmpty()) {
            $listsReceipts = $this->receiptRepository->newQuery()
                ->where('type_seat_id', $receipts[0]->type_seat_id)
                ->where('court_case_id', $courtCase->id)
                ->distinct()
                ->lists('receipt_number');
            $depositsRC = $this->depositRepository->whereIn('school_id', userSchool()->id, 'codeReceipt', $listsReceipts);
            if (!$depositsRC->isEmpty()) {
                $sumDepositsRC = $this->depositRepository->sumInSchool('codeReceipt', $listsReceipts);
            }
            $sumCashesRC = $this->cashRepository->sumInSchool('receipt', $listsReceipts);
        }
        if (!$auxiliaryReceipts->isEmpty()) {
            $listsAuxiliaryReceipts = $this->auxiliaryReceiptRepository->newQuery()
                ->where('type_seat_id', $auxiliaryReceipts[0]->type_seat_id)
                ->where('court_case_id', $courtCase->id)
                ->distinct()
                ->lists('receipt_number');
            $depositsRCA = $this->depositRepository->whereIn('school_id', userSchool()->id, 'codeReceipt', $listsAuxiliaryReceipts);
            if (!$depositsRCA->isEmpty()) {
                $sumDepositsRCA = $this->depositRepository->newQuery()->where('school_id', userSchool()->id)->whereIn('codeReceipt', $listsAuxiliaryReceipts)->sum('amount');
            }
            $sumCashesRCA = $this->cashRepository->sumTypeSchool('receipt', $listsAuxiliaryReceipts);
        }
        if (isset($listsReceipts)) {
            foreach ($listsReceipts as $value) {
                $sumList = $this->receiptRepository->newQuery()->where('receipt_number', $value)->where('type_seat_id', $receipts[0]->type_seat_id)->sum('amount');
                $arrReceipt[$value] = $sumList;
            }

            $cashesRC = $this->cashRepository->whereIn('school_id', userSchool()->id, 'receipt', $listsReceipts);
        }
        if (isset($listsAuxiliaryReceipts)) {
            foreach ($listsAuxiliaryReceipts as $value) {
                $sumList = $this->auxiliaryReceiptRepository->newQuery()->where('receipt_number', $value)->where('type_seat_id', $auxiliaryReceipts[0]->type_seat_id)->sum('amount');
                $arrAuxilaryReceipt[$value] = $sumList;
            }
            $cashesRCA = $this->cashRepository->whereIn('school_id', userSchool()->id, 'receipt', $listsAuxiliaryReceipts);
        }

        $sysconf = School::first();
        Fpdf::AddPage('P', 'letter');
        /**
         * LINE 2
         */
        if (!$receipts->isEmpty() || !$auxiliaryReceipts->isEmpty()):
            Fpdf::SetFont('Times', 'B', 18);
            Fpdf::Cell(0, 6, utf8_decode(substr($sysconf->name, 0, 35)), 0, 1, 'C');

            Fpdf::SetX(5);
            Fpdf::Cell(0, 6, 'Corte de Caja # ' . $courtCase->abbreviation, 0, 1, 'C');
            Fpdf::SetX(5);

            if (!$receipts->isEmpty()):
                Fpdf::Cell(0, 6, 'Recibos: ' . $receipts[0]->receipt_number . ' a ' . $receipts[count($receipts) - 1]->receipt_number, 0, 1, 'C');
            endif;
            if (!$auxiliaryReceipts->isEmpty()):
                Fpdf::Cell(0, 6, 'Recibos Auxiliares: ' . $auxiliaryReceipts[0]->receipt_number . ' a ' . $auxiliaryReceipts[count($auxiliaryReceipts) - 1]->receipt_number, 0, 1, 'C');
            endif;
            Fpdf::Cell(0, 6, 'Fecha de Asiento ' . dateShort() . ' - Periodo: ' . Carbon::parse($courtCase->date)->format('m-Y'), 0, 1, 'C');
            Fpdf::Ln();
            Fpdf::SetFont('Times', 'BI', 12);
            Fpdf::Cell(30, 6, utf8_decode('Código'), 1, 0, 'C');
            Fpdf::Cell(80, 6, 'Cuenta', 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('Crédito'), 1, 1, 'C');
            $aux = 0;
            if (!$receipts->isEmpty()):
                foreach ($receipts as $receipt):
                    Fpdf::Cell(30, 6, $receipt->catalogs->code, 1, 0, 'C');
                    Fpdf::Cell(80, 6, utf8_decode(convertTitle($receipt->catalogs->name)), 1, 0, 'C');
                    Fpdf::Cell(30, 6, $receipt->amount, 1, 1, 'C');
                    $aux += floatval($receipt->amount);
                endforeach;
            endif;
            if (!$auxiliaryReceipts->isEmpty()):
                foreach ($auxiliaryReceipts as $auxiliaryReceipt):
                    Fpdf::Cell(30, 6, $auxiliaryReceipt->financialRecords->students->book, 1, 0, 'C');
                    Fpdf::Cell(80, 6, utf8_decode(convertTitle($auxiliaryReceipt->financialRecords->students->nameComplete())), 1, 0, 'C');
                    Fpdf::Cell(30, 6, $auxiliaryReceipt->amount, 1, 1, 'C');
                    $aux += floatval($auxiliaryReceipt->amount);
                endforeach;
            endif;
            if (!$receipts->isEmpty() || !$auxiliaryReceipts->isEmpty()):
                Fpdf::Cell(30, 6, 'Depositos:', 1, 0, 'C');

                if ($sumDepositsRC > 0 && $sumDepositsRCA > 0):
                    Fpdf::Cell(80, 6, $sumDepositsRC + $sumDepositsRCA, 1, 0, 'C');
                elseif ($sumDepositsRC == 0):
                    Fpdf::Cell(80, 6, $sumDepositsRCA, 1, 0, 'C');
                elseif ($sumDepositsRCA == 0):
                    Fpdf::Cell(80, 6, $sumDepositsRC, 1, 0, 'C');
                endif;

                Fpdf::Cell(30, 6, '', 1, 1, 'C');
                Fpdf::Cell(30, 6, 'Efectivo:', 1, 0, 'C');
                if ($sumCashesRC > 0 && $sumCashesRCA > 0):
                    Fpdf::Cell(80, 6, $sumCashesRC + $sumCashesRCA, 1, 0, 'C');
                elseif ($sumCashesRC == 0):
                    Fpdf::Cell(80, 6, $sumCashesRCA, 1, 0, 'C');
                elseif ($sumCashesRCA == 0):
                    Fpdf::Cell(80, 6, $sumCashesRC, 1, 0, 'C');
                endif;
                Fpdf::Cell(30, 6, '', 1, 1, 'C');
                Fpdf::Cell(30, 6, 'Total:', 1, 0, 'C');
                Fpdf::Cell(80, 6, ($sumDepositsRC + $sumDepositsRCA + $sumCashesRC + $sumCashesRCA), 1, 0, 'C');
                Fpdf::Cell(30, 6, $aux, 1, 1, 'C');
            endif;
        endif;
        Fpdf::Ln();

        if (count($arrReceipt) > 0 || count($arrAuxilaryReceipt) > 0):
            Fpdf::AddPage('P', 'letter');
            Fpdf::SetFont('Times', 'B', 18);
            Fpdf::Cell(0, 6, utf8_decode(substr($sysconf->name, 0, 35)), 0, 1, 'C');

            Fpdf::SetX(5);
            Fpdf::Cell(0, 6, 'Corte de Caja # ' . $courtCase->abbreviation, 0, 1, 'C');
            Fpdf::SetX(5);
            if (!$receipts->isEmpty()):
                Fpdf::Cell(0, 6, 'Recibos: ' . $receipts[0]->receipt_number . ' a ' . $receipts[count($receipts) - 1]->receipt_number, 0, 1, 'C');
            endif;
            if (!$auxiliaryReceipts->isEmpty()):
                Fpdf::Cell(0, 6, 'Recibos Auxiliares: ' . $auxiliaryReceipts[0]->receipt_number . ' a ' . $auxiliaryReceipts[count($auxiliaryReceipts) - 1]->receipt_number, 0, 1, 'C');
            endif;
            Fpdf::Cell(0, 6, 'Fecha de Asiento ' . dateShort() . ' - Periodo: ' . Carbon::parse($courtCase->date)->format('m-Y'), 0, 1, 'C');
            Fpdf::Ln();
            Fpdf::SetFont('Times', 'B', 12);
            Fpdf::Cell(40, 6, utf8_decode('Cuenta Bancaria'), 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('N° Referencia'), 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('Fecha'), 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('Crédito'), 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('Total'), 1, 1, 'C');
            $aux = 0;
            if (count($arrReceipt) > 0):
                foreach ($arrReceipt as $codeReceipt => $sum):
                    Fpdf::Cell(130, 6, $codeReceipt, 1, 0, 'C');
                    Fpdf::Cell(30, 6, $sum, 1, 1, 'C');
                    foreach ($depositsRC as $depositRC):
                        if ($codeReceipt == $depositRC->codeReceipt):
                            Fpdf::Cell(40, 6, $depositRC->account, 1, 0, 'C');
                            Fpdf::Cell(30, 6, $depositRC->number, 1, 0, 'C');
                            Fpdf::Cell(30, 6, $depositRC->date, 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format($depositRC->amount), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format(0), 1, 1, 'C');
                        endif;
                    endforeach;
                    foreach ($cashesRC as $cashRC):
                        if ($codeReceipt == $cashRC->receipt):
                            Fpdf::Cell(40, 6, 'Efectivo', 1, 0, 'C');
                            Fpdf::Cell(30, 6, '', 1, 0, 'C');
                            $fecha = explode(' ', $cashRC->updated_at);
                            Fpdf::Cell(30, 6, $fecha[0], 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format($cashRC->amount), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format(0), 1, 1, 'C');
                        endif;
                    endforeach;
                    $aux += floatval($sum);
                endforeach;
            endif;
            if (count($arrAuxilaryReceipt) > 0):
                foreach ($arrAuxilaryReceipt as $codeReceipt => $sum):
                    Fpdf::Cell(130, 6, $codeReceipt, 1, 0, 'C');
                    Fpdf::Cell(30, 6, $sum, 1, 1, 'C');
                    foreach ($depositsRCA as $depositRCA):
                        if ($codeReceipt == $depositRCA->codeReceipt):
                            Fpdf::Cell(40, 6, $depositRCA->account, 1, 0, 'C');
                            Fpdf::Cell(30, 6, $depositRCA->number, 1, 0, 'C');
                            Fpdf::Cell(30, 6, $depositRCA->date, 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format($depositRCA->amount), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format(0), 1, 1, 'C');
                        endif;
                    endforeach;
                    foreach ($cashesRCA as $cashRCA):
                        if ($codeReceipt == $cashRCA->receipt):
                            Fpdf::Cell(40, 6, 'Efectivo', 1, 0, 'C');
                            Fpdf::Cell(30, 6, '', 1, 0, 'C');
                            $fecha = explode(' ', $cashRCA->updated_at);
                            Fpdf::Cell(30, 6, $fecha[0], 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format($cashRCA->amount), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format(0), 1, 1, 'C');
                        endif;
                    endforeach;
                    $aux += floatval($sum);
                endforeach;
            endif;
            Fpdf::Cell(100, 6, 'Total:', 1, 0, 'C');
            Fpdf::Cell(30, 6, number_format($aux), 1, 0, 'C');
            Fpdf::Cell(30, 6, number_format($aux), 1, 1, 'C');
        endif;


        /**
         *
         */
        Fpdf::Ln();
        if (count($arrReceipt) > 0 || count($arrAuxilaryReceipt) > 0):
            Fpdf::AddPage('P', 'letter');

            Fpdf::SetFont('Times', 'B', 18);
            Fpdf::Cell(0, 6, utf8_decode(substr($sysconf->name, 0, 35)), 0, 1, 'C');

            Fpdf::SetX(5);
            Fpdf::Cell(0, 6, 'Corte de Caja # ' . $courtCase->abbreviation, 0, 1, 'C');
            Fpdf::SetX(5);
            if (!$receipts->isEmpty()):
                Fpdf::Cell(0, 6, 'Recibos: ' . $receipts[0]->receipt_number . ' a ' . $receipts[count($receipts) - 1]->receipt_number, 0, 1, 'C');
            endif;
            if (!$auxiliaryReceipts->isEmpty()):
                Fpdf::Cell(0, 6, 'Recibos Auxiliares: ' . $auxiliaryReceipts[0]->receipt_number . ' a ' . $auxiliaryReceipts[count($auxiliaryReceipts) - 1]->receipt_number, 0, 1, 'C');
            endif;
            Fpdf::Cell(0, 6, 'Fecha de Asiento ' . dateShort() . ' - Periodo: ' . Carbon::parse($courtCase->date)->format('m-Y'), 0, 1, 'C');
            Fpdf::Ln();
            Fpdf::SetFont('Times', 'BI', 12);

            Fpdf::Cell(30, 6, utf8_decode('Código'), 1, 0, 'C');
            Fpdf::Cell(80, 6, utf8_decode('Cuenta'), 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('Crédito'), 1, 0, 'C');
            Fpdf::Cell(30, 6, utf8_decode('Total'), 1, 1, 'C');
            Fpdf::SetFont('Times', 'I', 12);
            $aux = 0;
            if (count($arrReceipt) > 0):
                foreach ($arrReceipt as $codeReceipt => $sum):
                    Fpdf::Cell(110, 6, ($codeReceipt), 1, 0, 'C');
                    Fpdf::Cell(30, 6, number_format(0), 1, 0, 'C');
                    Fpdf::Cell(30, 6, number_format($sum), 1, 1, 'C');
                    foreach ($receipts as $receipt):
                        if ($codeReceipt == $receipt->receipt_number):
                            Fpdf::Cell(20, 6, utf8_decode($receipt->catalogs->code), 1, 0, 'C');
                            Fpdf::Cell(80, 6, utf8_decode(convertTitle($receipt->catalogs->name)), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format($receipt->amount), 1, 1, 'C');
                        endif;
                    endforeach;
                    $aux += floatval($sum);
                endforeach;
            endif;
            if (count($arrAuxilaryReceipt) > 0):
                foreach ($arrAuxilaryReceipt as $codeReceipt => $sum):

                    Fpdf::Cell(110, 6, utf8_decode($codeReceipt), 1, 0, 'C');
                    Fpdf::Cell(30, 6, number_format(0), 1, 0, 'C');
                    Fpdf::Cell(30, 6, number_format($sum), 1, 1, 'C');
                    foreach ($auxiliaryReceipts as $auxiliaryReceipt):
                        if ($codeReceipt == $auxiliaryReceipt->receipt_number):
                            Fpdf::Cell(30, 6, utf8_decode($auxiliaryReceipt->financialRecords->students->book), 1, 0, 'C');
                            Fpdf::Cell(80, 6, utf8_decode(convertTitle($auxiliaryReceipt->financialRecords->students->nameComplete())), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format($auxiliaryReceipt->amount), 1, 0, 'C');
                            Fpdf::Cell(30, 6, number_format(0), 1, 1, 'C');
                        endif;
                    endforeach;
                    $aux += floatval($sum);
                endforeach;
            endif;
            Fpdf::Cell(110, 6, utf8_decode('Total:'), 1, 0, 'C');
            Fpdf::Cell(30, 6, number_format($aux), 1, 0, 'C');
            Fpdf::Cell(30, 6, number_format($aux), 1, 1, 'C');

        endif;
        Fpdf::Output();
        exit;
        /* if ($type == 1) {
             $view = View::make('courtCases.report', compact('courtCase', 'receipts', 'auxiliaryReceipts', 'sumDepositsRC', 'sumDepositsRCA', 'sumCashesRC', 'sumCashesRCA', 'type'))->render();
             $pdf = App::make('dompdf.wrapper');
             $pdf->loadHTML($view);
             return $pdf->stream("Impresion - $courtCase->abbreviation.pdf");
             // $pdf = \PDF::loadView('courtCases.report', compact('courtCase', 'receipts', 'auxiliaryReceipts', 'sumDepositsRC', 'sumDepositsRCA', 'sumCashesRC', 'sumCashesRCA', 'type'));
             //  return $pdf->stream("Impresion - $courtCase->abbreviation.pdf");
         } else if ($type == 2) {
             $view = View::make('courtCases.report', compact('courtCase', 'receipts', 'auxiliaryReceipts', 'type', 'arrReceipt', 'arrAuxilaryReceipt'))->render();
             $pdf = App::make('dompdf.wrapper');
             $pdf->loadHTML($view);
             return $pdf->stream("Impresion - $courtCase->abbreviation.pdf");
             //  $pdf = \PDF::loadView('courtCases.report', compact('courtCase', 'receipts', 'auxiliaryReceipts', 'type', 'arrReceipt', 'arrAuxilaryReceipt'));
         } else if ($type == 3) {
             $view = View::make('courtCases.report', compact('courtCase', 'receipts', 'auxiliaryReceipts', 'depositsRC', 'depositsRCA', 'cashesRC', 'cashesRCA', 'type', 'arrReceipt', 'arrAuxilaryReceipt'))->render();
             $pdf = App::make('dompdf.wrapper');
             $pdf->loadHTML($view);
             return $pdf->stream("Impresion - $courtCase->abbreviation.pdf");
             //  $pdf = \PDF::loadView('courtCases.report', compact('courtCase', 'receipts', 'auxiliaryReceipts', 'depositsRC', 'depositsRCA', 'cashesRC', 'cashesRCA', 'type', 'arrReceipt', 'arrAuxilaryReceipt'));
             //  return $pdf->stream("Impresion - $courtCase->abbreviation.pdf");
         } else {
             return abort(404);
         }*/
    }


    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-17
    |@Date Update: 2016-07-12
    |---------------------------------------------------------------------
    |@Description: con este metodo generamos el mensaje que pondremos en
    |   el asiento del corte de caja para los recibos de contabilidad,
    | Se modifico el helpers de la fecha.
    |----------------------------------------------------------------------
    | @return string
    |----------------------------------------------------------------------
    */
    public function menssage($catalogs)
    {
        $first = $this->receiptRepository->whereDuoFisrt('court_case_id', null, 'status', 'aplicado', 'catalog_id', $catalogs);
        $last = $this->receiptRepository->whereDuoInLast('court_case_id', null, 'status', 'aplicado', 'catalog_id', $catalogs);
        if (strlen($first) > 0):
            return 'Corte de Caja del recibo ' . $first->receipt_number . ' Al ' . $last->receipt_number . ' del mes ' . changeLetterMonth(periodSchool()->month);
        endif;
        return '';
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-07-28
    |@Date Update: 2015-09-13
    |---------------------------------------------------------------------
    |@Description: Con esta accion lo que hacemos es ver los recibos de
    |   contabilidad que no estan aplicados y generamos el corte de caja
    |
    |@Pasos:
    | 1. Buscamos el primer recibo y el ultimo recibo y el monto total
    |    para el corte de caja, traemos la cuenta que utilizaremos
    |    para darle el debito al corte de caja en el asiento.
    | 2. Si no encontramos la cuenta para la contra parte enviamos error
    | 3. insertamos los datos de corte en la tabla de asientos
    | 4. Insertamos cada una de las cuentas utilizadas
    |
    |@param $token, $courtCase
    |----------------------------------------------------------------------
    | @return bool
    |----------------------------------------------------------------------
    */
    private function accountingSeating($courtCase, $token, $receipts, $catalogs, $message)
    {
        #Pasos 1

        $amount = $this->receiptRepository->whereDuoInSum('court_case_id', null, 'status', 'aplicado', 'catalog_id', $catalogs);
        $setting = $this->settingRepository->whereDuoData($courtCase->type_seat_id);
        #Paso 2
        if (strlen($setting) == 0):
            return false;
        endif;
        #Paso 3
        if (!$receipts->isEmpty()):
            $seatings = [
                'date' => dateShort(),
                'code' => $courtCase->abbreviation,
                'detail' => $message,
                'amount' => $amount,
                'catalog_id' => $setting[0]->catalog_id,
                'type_seat_id' => $courtCase->type_seat_id,
                'accounting_period_id' => $receipts[0]->accounting_period_id,
                'type_id' => $this->typeFormRepository->nameType('debito'),
                'token' => $token,
                'status' => 'aplicado',
                'typeCollection' => 'otro',
                'user_created' => Auth::user()->id
            ];
            $Seating = $this->seatingRepository->getModel();

            if ($Seating->isValid($seatings)):
                $Seating->fill($seatings);
                #Paso 4
                if ($Seating->save()):
                    foreach ($receipts AS $seat):
                        $accountingSeats = [
                            'date' => dateShort(),
                            'code' => $courtCase->abbreviation,
                            'detail' => $message,
                            'amount' => $seat->amount,
                            'seating_id' => $courtCase->id,
                            'catalog_id' => $seat->catalog_id,
                            'type_seat_id' => $courtCase->type_seat_id,
                            'accounting_period_id' => $seat->accounting_period_id,
                            'type_id' => $this->typeFormRepository->nameType('credito'),
                            'token' => $token,
                            'status' => 'aplicado',
                            'typeCollection' => 'otro',
                            'user_created' => Auth::user()->id
                        ];
                        $SeatingPart = $this->seatingPartRepository->getModel();
                        if ($SeatingPart->isValid($accountingSeats)):
                            $SeatingPart->fill($accountingSeats);
                            $SeatingPart->save();

                        endif;
                    endforeach;
                endif;
            endif;
            return true;
        endif;
        return false;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-13
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: con esta accion actualizamos los recibos y le agregamos
    |   el numero de corte de caja
    |@Pasos
    |   1. Buscamos todas las cuentas de contabilidad del catalogo
    |   2. Con la lista de cuentas buscamos todas las que fueron usadas en
    |      en los recibos.
    |   3. Generamos el asientos con el registro de cada una de las cuentas
    |   4. Verificamos que si hay cambios registrados actualice los datos
    |      de los recibos agregandole el numero de corte al cual pertenece
    |@param $courtCase, $token, $receipts
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function updateReceiptCourtCase($courtCase, $token)
    {

        #Paso 1
        $catalogs = $this->catalogRepository->listsWhere('style', 'detalle', 'id');
        #Paso 2
        $receipts = $this->receiptRepository->whereDuoIn('court_case_id', null, 'status', 'aplicado', 'catalog_id', $catalogs);
        if (count($receipts) > 0):
            $message = $this->menssage($catalogs);
            #Paso 3
            $seatingCourtCase = $this->accountingSeating($courtCase, $token, $receipts, $catalogs, $message);

            #Paso 4
            if ($seatingCourtCase):
                foreach ($receipts AS $receipt):
                    $this->receiptRepository->updateDataWhere('receipt_number', $receipt->receipt_number, 'court_case_id', $courtCase->id, $receipt->type_seat_id);
                endforeach;

                return true;
            else:
                return false;
            endif;
        endif;
        return true;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-15
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
    |@param $courtCase, $auxiliaryReceipts, $first, $last, $token
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    private function auxiliarSeat($courtCase, $auxiliaryReceipts, $firts, $last, $token)
    {

        if (!$auxiliaryReceipts->isEmpty()):
            $total = 0;
            foreach ($auxiliaryReceipts AS $seat):
                $auxiliarSeatCourt = ['date' => dateShort(),
                    'code' => $courtCase->abbreviation,
                    'detail' => $seat->detail . ' # Recibo ' . $seat->receipt_number . ' del mes ' . changeLetterMonth(periodSchool()->month),
                    'amount' => $seat->amount,
                    'financial_records_id' => $seat->financial_record_id,
                    'type_seat_id' => $courtCase->type_seat_id,
                    'accounting_period_id' => $seat->accounting_period_id,
                    'type_id' => $this->typeFormRepository->nameType('credito'),
                    'token' => $token,
                    'status' => 'aplicado',
                    'typeCollection' => 'otro',
                    'user_created' => Auth::user()->id
                ];
                $auxiliarySeat = $this->auxiliarySeatRepository->getModel();
                if ($auxiliarySeat->isValid($auxiliarSeatCourt)):
                    $auxiliarySeat->fill($auxiliarSeatCourt);
                    $auxiliarySeat->save();
                endif;
                if (strlen($auxiliarySeat->errors) > 0):
                    \DB::rollback();
                    return $this->errores($auxiliarySeat->errors);
                endif;
            endforeach;
            return true;
        endif;
        return $this->errores($auxiliaryReceipts);
    }

}
