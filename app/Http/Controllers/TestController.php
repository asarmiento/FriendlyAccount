<?php

namespace AccountHon\Http\Controllers;


use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\BalanceBudget;
use AccountHon\Entities\BalancePeriod;
use AccountHon\Entities\Catalog;
use AccountHon\Entities\Restaurant\Invoice;
use AccountHon\Entities\Restaurant\MenuRestaurant;
use AccountHon\Entities\Restaurant\OrderSalon;
use AccountHon\Entities\Restaurant\TableSalon;
use AccountHon\Entities\School;
use AccountHon\Entities\Spreadsheet;
use AccountHon\Entities\Transfer;
use AccountHon\Repositories\Accounting\SupplierRepository;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\AuxiliarySeatRepository;
use AccountHon\Repositories\BalancePeriodRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\FinancialRecordsRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\SeatingPartRepository;
use AccountHon\Repositories\SeatingRepository;
use AccountHon\Repositories\StudentRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Repositories\UsersRepository;
use AccountHon\Traits\Convert;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class TestController extends Controller
{
    use  Convert;
    /**
     * @var FinancialRecordsRepository
     */
    private $financialRecordsRepository;
    private $studentRepository;
    private $auxiliarySeatRepository;
    private $catalogRepository;
    private $balancePeriodRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var SeatingPartRepository
     */
    private $seatingPartRepository;
    /**
     * @var SeatingRepository
     */
    private $seatingRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    private $invoiceRepository;
    /**
     * @var AuxiliarySeatController
     */
    private $auxiliarySeatController;

    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;


    /**
     * @param FinancialRecordsRepository $financialRecordsRepository
     * @param AuxiliarySeatRepository $auxiliarySeatRepository
     * @param StudentRepository $studentRepository
     * @param CatalogRepository $catalogRepository
     * @param BalancePeriodRepository $balancePeriodRepository
     * @param AccountingPeriodRepository $accountingPeriodRepository
     * @param \AccountHon\Repositories\Accounting\SupplierRepository $supplierRepository
     * @param SeatingPartRepository $seatingPartRepository
     * @param SeatingRepository $seatingRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param InvoiceRepository $invoiceRepository
     * @param UsersRepository $userRepository
     * @param AuxiliarySeatController $auxiliarySeatController
     * @param TypeFormRepository $typeFormRepository
     */
    public function __construct(
        FinancialRecordsRepository $financialRecordsRepository,
        AuxiliarySeatRepository $auxiliarySeatRepository,
        StudentRepository $studentRepository,
        CatalogRepository $catalogRepository,
        BalancePeriodRepository $balancePeriodRepository,
        AccountingPeriodRepository $accountingPeriodRepository,
        SupplierRepository $supplierRepository,
        SeatingPartRepository $seatingPartRepository,
        SeatingRepository $seatingRepository,
        TypeSeatRepository $typeSeatRepository,
        InvoiceRepository $invoiceRepository,
        UsersRepository $userRepository,
        AuxiliarySeatController $auxiliarySeatController,
        TypeFormRepository $typeFormRepository
    )
    {
        //$this->middleware('sessionOff');
        $this->financialRecordsRepository = $financialRecordsRepository;
        $this->studentRepository = $studentRepository;
        $this->auxiliarySeatRepository = $auxiliarySeatRepository;
        $this->catalogRepository = $catalogRepository;
        $this->balancePeriodRepository = $balancePeriodRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
        $this->supplierRepository = $supplierRepository;
        $this->seatingPartRepository = $seatingPartRepository;
        $this->seatingRepository = $seatingRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->userRepository = $userRepository;
        $this->auxiliarySeatController = $auxiliarySeatController;
        $this->typeFormRepository = $typeFormRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {


        try {
            $debit = $this->typeFormRepository->nameType('debito');
            $credit = $this->typeFormRepository->nameType('credito');
            $typeSeat = $this->typeSeatRepository->whereDuoData('DG');
            $period = $this->accountingPeriodRepository->lists('id');
            DB::beginTransaction();
            $catalogs = $this->catalogRepository->whereDuoFilterSchool('type', 4, 'style', 'Detalle', 'code', 'ASC');
            foreach ($catalogs AS $catalog):
                $catalogCapital = $this->catalogRepository->accountNameSingleSchool('Fondo general');
                $balance = $this->seatingRepository->balanceCatalogoInPeriod($catalog->id, $period);
                if ($balance < 0):
                    $balance = $balance * -1;
                endif;

                $data = ['code' => $typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity,
                    'detail' => 'Estamos Generando el Asiento de Cierre fiscal. ' . period(),
                    'date' => dateShort(),
                    'amount' => $balance,
                    'status' => 'Aplicado',
                    'catalog_id' => $catalogCapital[0]->id,
                    'accounting_period_id' => periodSchool()->id,
                    'type_id' => $credit,
                    'type_seat_id' => $typeSeat[0]->id,
                    'user_created' => currentUser()->id,
                    'token' => Crypt::encrypt($typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity)];
                $seating = $this->seatingRepository->getModel();
                if ($balance > 0):
                    /* Validamos los datos para guardar tabla menu */
                    if ($seating->isValid($data)):
                        $seating->fill($data);
                        $seating->save();
                    endif;
                endif;

                $data = ['code' => $typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity,
                    'detail' => 'Estamos Generando el Asiento de Cierre fiscal. ' . period(),
                    'date' => dateShort(),
                    'amount' => $balance,
                    'status' => 'Aplicado',
                    'catalog_id' => $catalog->id,
                    'seating_id' => $seating->id,
                    'accounting_period_id' => periodSchool()->id,
                    'type_id' => $debit,
                    'type_seat_id' => $typeSeat[0]->id,
                    'user_created' => currentUser()->id,
                    'token' => Crypt::encrypt($typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity)];
                $seatingPart = $this->seatingPartRepository->getModel();
                if ($balance > 0):
                    /* Validamos los datos para guardar tabla menu */
                    if ($seatingPart->isValid($data)):
                        $seatingPart->fill($data);
                        $seatingPart->save();
                    endif;
                endif;
            endforeach;
            /**
             *
             **/

            /**
             *
             */
            $catalogs = $this->catalogRepository->whereDuoFilterSchool('type', 6, 'style', 'Detalle', 'code', 'ASC');
            $balance = 0;
            foreach ($catalogs AS $catalog):
                $catalogCapital = $this->catalogRepository->accountNameSingleSchool('Fondo general');
                $balance = $this->seatingRepository->balanceCatalogoInPeriod($catalog->id, $period);
                if ($balance < 0):
                    $balance = $balance * -1;
                endif;
                $data = ['code' => $typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity,
                    'detail' => 'Estamos Generando el Asiento de Cierre fiscal. ' . period(),
                    'date' => dateShort(),
                    'amount' => $balance,
                    'status' => 'Aplicado',
                    'catalog_id' => $catalogCapital[0]->id,
                    'accounting_period_id' => periodSchool()->id,
                    'type_id' => $debit,
                    'type_seat_id' => $typeSeat[0]->id,
                    'user_created' => currentUser()->id,
                    'token' => Crypt::encrypt($typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity)];
                $seating = $this->seatingRepository->getModel();
                if ($balance > 0):
                    /* Validamos los datos para guardar tabla menu */
                    if ($seating->isValid($data)):
                        $seating->fill($data);
                        $seating->save();
                    endif;
                endif;

                $data = ['code' => $typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity,
                    'detail' => 'Estamos Generando el Asiento de Cierre fiscal. ' . period(),
                    'date' => dateShort(),
                    'amount' => $balance,
                    'status' => 'Aplicado',
                    'catalog_id' => $catalog->id,
                    'seating_id' => $seating->id,
                    'accounting_period_id' => periodSchool()->id,
                    'type_id' => $credit,
                    'type_seat_id' => $typeSeat[0]->id,
                    'user_created' => currentUser()->id,
                    'token' => Crypt::encrypt($typeSeat[0]->abbreviation . '-' . $typeSeat[0]->quatity)];
                $seatingPart = $this->seatingPartRepository->getModel();
                if ($balance > 0):
                    /* Validamos los datos para guardar tabla menu */
                    if ($seatingPart->isValid($data)):
                        $seatingPart->fill($data);
                        $seatingPart->save();
                    endif;
                endif;
            endforeach;
            $this->typeSeatRepository->updateWhere($typeSeat[0]->abbreviation);
            $this->balanceFiscal();
            DB::commit();
            return $this->exito('Se ha generado con exito el asiento de cierre fiscal');

        } catch (Exception $e) {
            Db::rollback();
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }


    }

    public function actualizacionToken($id)
    {
        $catalos = Catalog::where('school_id', $id)->get();
        foreach ($catalos AS $catalo) {
            Catalog::where('id', $catalo->id)->update(['token' => bcrypt($catalo->name . '-' . $id . '-' . $catalo->code)]);
        }
        return redirect()->back();
    }

    /**
     *
     */
    public function updateCompras()
    {


        foreach ($this->supplierRepository->getModel()->get() AS $data):
            $token = bcrypt($data->identification);
            $update = $this->supplierRepository->getModel();
            $update->where('id', $data->id)->update(['token' => $token]);
        endforeach;
    }

    /**
     * Con este metodo recalculamos los saldos de los estudiantes del primer
     * periodo
     *
     */

    public function recalcularSaldoStuden()
    {
        $Student = $this->studentRepository->lists('id');
        $period = $this->accountingPeriodRepository->lists('id');

        $finantials = $this->financialRecordsRepository->whereInList('student_id', $Student, 'id');
        foreach ($finantials as $finantial) {

            $seatDebito = $this->auxiliarySeatRepository->saldoStudentInPeriod($finantial, $period, 6);
            $seatCredito = $this->auxiliarySeatRepository->saldoStudentInPeriod($finantial, $period, 7);
            $saldo = $seatDebito - $seatCredito;

            $this->financialRecordsRepository->updateData($finantial, 'balance', $saldo);
        }


    }

    public function recalcularAsiento()
    {
        $Students = $this->studentRepository->allFilterScholl();


        foreach ($Students as $student) {
            $finantials = $this->financialRecordsRepository->whereInList('student_id', $student, 'id');
            echo json_encode($finantials);
            die;
            /*  $seatDebito = $this->auxiliarySeatRepository->getModel()->where('financial_records_id',$finantial)->where('type_id',6)->sum('amount');
              $seatCredito = $this->auxiliarySeatRepository->getModel()->where('financial_records_id',$finantial)->where('type_id',7)->sum('amount');

                  $amount = $this->financialRecordsRepository->find($finantial);

              */

        }


    }

    public function TrasladoSaldo()
    {
        set_time_limit(0);
        $catalogs = $this->catalogRepository->accountSchool();

        foreach ($catalogs AS $catalog):
            $saldo = $this->balancePeriodRepository->saldoTotalPeriod($catalog, [63]);
            $data = [
                'catalog_id' => $catalog->id,
                'amount' => $saldo,
                'period' => '201507',
                'year' => periodSchool()->year,
                'school_id' => userSchool()->id
            ];
            $balance = $this->balancePeriodRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($balance->isValid($data)):
                $balance->fill($data);
                $balance->save();
                /* Enviamos el mensaje de guardado correctamente */
            endif;
        endforeach;
    }

    public function VerificacionSaldo()
    {


        $typeSeat = $this->accountingPeriodRepository->getModel()->where('school_id', 1)->lists('id');
        $pdf = Fpdf::AddPage('p', 'letter');

        $seatings = $this->seatingRepository->getModel()->where('status', 'aplicado')
            ->whereIn('accounting_period_id', $typeSeat)->groupBy('code')->get();
        $debitoSeatC = 0;
        $pdf .= Fpdf::SetFont('Arial', 'B', 16);
        $pdf .= Fpdf::Cell(30, 5, 'Codigo', 0, 0, 'C');
        $pdf .= Fpdf::Cell(40, 5, 'Debito', 0, 0, 'C');
        $pdf .= Fpdf::Cell(30, 5, 'Credito', 0, 0, 'C');
        $pdf .= Fpdf::Cell(30, 5, 'Balance', 0, 1, 'C');
        foreach ($seatings AS $seating):

            $pdf .= Fpdf::SetFont('Arial', 'I', 14);
            $debito = $this->seatingRepository->getModel()->where('code', $seating->code)
                ->where('type_id', 6)->whereIn('accounting_period_id', $typeSeat)->where('status', 'Aplicado')->sum('amount');
            $credito = $this->seatingRepository->getModel()->where('code', $seating->code)
                ->where('type_id', 7)->whereIn('accounting_period_id', $typeSeat)->where('status', 'Aplicado')->sum('amount');

            $debitoPart = $this->seatingPartRepository->getModel()->where('code', $seating->code)->where('type_id', 6)
                ->where('status', 'Aplicado')->whereIn('accounting_period_id', $typeSeat)->sum('amount');

            $creditoPart = $this->seatingPartRepository->getModel()->where('code', $seating->code)->where('type_id', 7)
                ->where('status', 'Aplicado')->whereIn('accounting_period_id', $typeSeat)->sum('amount');


            $pdf .= Fpdf::Cell(30, 5, $seating->code, 0, 0, 'C');
            $pdf .= Fpdf::Cell(60, 5, number_format(($debito + $debitoPart), 2), 0, 0, 'C');
            $pdf .= Fpdf::Cell(60, 5, number_format(($credito + $creditoPart), 2), 0, 0, 'C');
            $pdf .= Fpdf::Cell(60, 5, number_format(($debito + $debitoPart) - ($credito + $creditoPart), 2), 0, 1, 'C');
        endforeach;

        $typeSeat = $this->accountingPeriodRepository->getModel()->where('school_id', 1)->where('year', 2015)->lists('id');
        $catalogs = $this->catalogRepository->getModel()->where('school_id', 1)->orderBy('code', 'ASC')->get();

        foreach ($catalogs AS $catalog):

            $pdf .= Fpdf::SetFont('Arial', 'I', 12);
            $debito = $this->seatingRepository->getModel()->where('catalog_id', $catalog->id)
                ->where('type_id', 6)->where('status', 'Aplicado')->whereIn('accounting_period_id', $typeSeat)->sum('amount');
            $credito = $this->seatingRepository->getModel()->where('catalog_id', $catalog->id)
                ->where('type_id', 7)->where('status', 'Aplicado')->whereIn('accounting_period_id', $typeSeat)->sum('amount');
            $debitoPart = $this->seatingPartRepository->getModel()->where('catalog_id', $catalog->id)->where('type_id', 6)
                ->where('status', 'Aplicado')->whereIn('accounting_period_id', $typeSeat)->sum('amount');
            $creditoPart = $this->seatingPartRepository->getModel()->where('catalog_id', $catalog->id)->where('type_id', 7)
                ->where('status', 'Aplicado')->whereIn('accounting_period_id', $typeSeat)->sum('amount');


            $pdf .= Fpdf::Cell(15, 5, utf8_decode($catalog->id), 0, 0, 'L');
            $pdf .= Fpdf::Cell(70, 5, utf8_decode($catalog->name), 0, 0, 'L');
            $pdf .= Fpdf::Cell(40, 5, number_format(($debito + $debitoPart), 2), 0, 0, 'C');
            $pdf .= Fpdf::Cell(40, 5, number_format(($credito + $creditoPart), 2), 0, 0, 'C');
            $pdf .= Fpdf::Cell(40, 5, number_format(($debito + $debitoPart) - ($credito + $creditoPart), 2), 0, 1, 'C');
        endforeach;

        // $debitoSeatC =0;
        Fpdf::OutPut();
        exit;
    }

    public function catalogPartSaldo()
    {
//set_time_limit(0);
        /* $periods = $this->accountingPeriodRepository->getModel()->where('school_id',userSchool()->id)->get();
         foreach($periods AS $month):
  */

        $catalogs = $this->catalogRepository->accountSchool();

        foreach ($catalogs AS $catalog):
            $amount = $this->seatingRepository->balanceCatalogoPeriod($catalog->id, 98);
            $monto = $this->balancePeriodRepository->getModel()
                ->where('catalog_id', $catalog->id)->where('accounting_period_id', 97)->sum('amount');
            $total = $amount + $monto;
            $balance = $this->balancePeriodRepository->getModel();
            $balance->fill(['catalog_id' => $catalog->id,
                'accounting_period_id' => 98,
                'amount' => ($total),
                'school_id' => userSchool()->id,
                'year' => '2016']);

            $balance->save();

        endforeach; /*

       $catalogs = $this->catalogRepository->accountSchool();

       foreach($catalogs AS $catalog):

           $amount = $this->seatingRepository->balanceCatalogoPeriod($catalog->id,99);
           $monto =   $this->balancePeriodRepository->getModel()->where('catalog_id',$catalog->id)
               ->where('accounting_period_id',98)->sum('amount');
           $total = $amount+$monto;
           $balance= $this->balancePeriodRepository->getModel();
           $balance->fill(['catalog_id'=>$catalog->id,'accounting_period_id'=>99,'amount'=>($total),'school_id'=>userSchool()->id,'year'=>'2016']);
           $balance->save();

       endforeach;*/
    }

    public function createUser()
    {
        $new_user = $this->userRepository->getModel();
        $new_user->name = 'Mesero';
        $new_user->last = 'Salon';
        $new_user->email = 'Mesero@gmail.com';
        $new_user->password = \Crypt::encrypt('Mesero');
        $new_user->type_user_id = 5;
        $new_user->token = 'qw213sadwqe231<xb56';
        $new_user->save();

        $new_user = $this->userRepository->getModel();
        $new_user->name = 'Caja';
        $new_user->last = 'Salon';
        $new_user->email = 'caja@hotmail.com';
        $new_user->password = \Crypt::encrypt('caja');
        $new_user->type_user_id = 4;
        $new_user->token = 'qw213sadwqe231231wqewqesadsda3<xb56';
        $new_user->save();
    }

    public function seatStudentMonth($year)
    {
        $newStudents = $this->financialRecordsRepository->getModel()->where('retirement_date', null)->where('year', $year)->get();

        foreach ($newStudents AS $newStudent):
            # Cobro de mensualidad
            $debito = $this->auxiliarySeatRepository->getModel()->where('financial_records_id', $newStudent->id)->where('type_id', 6)->sum('amount');
            $credito = $this->auxiliarySeatRepository->getModel()->where('financial_records_id', $newStudent->id)->where('type_id', 7)->sum('amount');
            $balance = $debito - $credito;
            $this->financialRecordsRepository->getModel()->where('id', $newStudent->id)->update(['balance' => $balance]);
        endforeach;
        #Cambiamos el numero de asiento despues de generado los asientos.

    }

    public function tableSalon()
    {
        $bases = TableSalon::where('school_id', userSchool()->id)->where('deleted_at', '>=', '2017-01-01')->get();
        foreach ($bases AS $base):
            $id = TableSalon::where('school_id', userSchool()->id)->where('restaurant', 'base')->lists('id');
            Invoice::where('table_salon_id', $base->id)->update(['table_salon_id' => $id[0]]);
            OrderSalon::where('status', 'aplicado')->where('table_salon_id', $base->id)->update(['table_salon_id' => $id[0]]);
            TableSalon::where('id', $base->id)->delete();
        endforeach;
        return redirect('/institucion/inst/salon');
    }

    public function productPrice($id)
    {
        $menus = MenuRestaurant::where('school_id', $id)->get();
        foreach ($menus AS $menu) {
            if ($menu->costo > 0):
                \Log::info('menu' . $menu->id);
                MenuRestaurant::where('id', $menu->id)->update(['costo' => ($menu->costo / 1.13)]);
            endif;
        }

        /*   $proccess = ProcessedProduct::where('school_id',$id)->get();
            foreach ($proccess AS $procces){
                if($procces->price > 0):
                    \Log::info('proccess'.$procces->id);
                    ProcessedProduct::where('id',$procces->id)->update(['price'=>($procces->price + ($procces->price*0.13))]);
                endif;
            }*/

        return redirect('/');
    }

    public function pruebadeFile()
    {

        /* Buscamos todos los datos de school y traemos solo el id y el name */
        $school = School::select('id', 'name')->get();
        $dataJson = [];

        foreach ($school as $schools):
            $dataJson[] = array('value' => $schools->id, 'text' => $schools->name);
        endforeach;
        Log::debug( json_encode($dataJson));

        $fh = fopen('json/schools.json', 'w')
        or die('Error al abrir fichero de salida');

        fwrite($fh, json_encode($dataJson, JSON_UNESCAPED_UNICODE));
        fclose($fh);
}
    public function actualizarSaldoBalance($id, $other)
    {
        $catalogs = Catalog::where('school_id', userSchool()->id)->get();
        $periodo = AccountingPeriod::find($other);

        $periods = $this->accountingPeriodRepository->listsRange(array($periodo->year . $periodo->month, $periodo->year . $periodo->month), 'period', 'id');


        foreach ($catalogs AS $catalog) {
            $inicial = BalancePeriod::where('catalog_id', $catalog->id)->where('accounting_period_id', $id)->sum('amount');
            $debito = $this->saldoPeriod($catalog->id, $periods, 'debito');
            $credito = $this->saldoPeriod($catalog->id, $periods, 'credito');
            $balance = ($inicial + $debito) - $credito;

            $bal = BalancePeriod::where('catalog_id', $catalog->id)->where('accounting_period_id', $periodo->id);
            if ($bal->count() > 0) {
                BalancePeriod::where('catalog_id', $catalog->id)->where('accounting_period_id', $periodo->id)->update(['amount' => $balance]);
            } else {
                BalancePeriod::create([
                    'amount' => $balance, 'catalog_id' => $catalog->id, 'accounting_period_id' => $periodo->id, 'year' => $periodo->year,
                    'school_id' => userSchool()->id
                ]);
            }
        }
    }

    public function saldoPeriod($catalog, $period, $type)
    {

        $debit = $this->typeFormRepository->nameType($type);
        return $this->seatingRepository->balanceCatalogoType($catalog, $period, $debit);
    }
}
