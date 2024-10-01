<?php

namespace AccountHon\Http\Controllers\Accounting;

use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\AccountingReceipt;
use AccountHon\Entities\SchoolsMonthsFiscal;
use AccountHon\Http\Controllers\AccountingPeriodsController;
use AccountHon\Http\Controllers\BaseAbtractController;
use AccountHon\Repositories\Accounting\FiscalBalanceRepository;
use AccountHon\Repositories\Accounting\SchoolsMonthsFiscalRepository;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\AuxiliaryReceiptRepository;
use AccountHon\Repositories\AuxiliarySeatRepository;
use AccountHon\Repositories\AuxiliarySupplierRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\ReceiptRepository;
use AccountHon\Repositories\Restaurant\RecipeRepository;
use AccountHon\Repositories\SeatingPartRepository;
use AccountHon\Repositories\SeatingRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Carbon\Carbon;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;

class ClosedYearController extends Controller
{
    use Convert;
    /**
     * @var SchoolsMonthsFiscalRepository
     */
    private $schoolsMonthsFiscalRepository;
    /**
     * @var SeatingRepository
     */
    private $seatingRepository;
    /**
     * @var SeatingPartRepository
     */
    private $seatingPartRepository;
    /**
     * @var AuxiliaryReceiptRepository
     */
    private $auxiliaryReceiptRepository;
    /**
     * @var AuxiliarySupplierRepository
     */
    private $auxiliarySupplierRepository;
    /**
     * @var AuxiliarySeatRepository
     */
    private $auxiliarySeatRepository;
    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var FiscalBalanceRepository
     */
    private $fiscalBalanceRepository;

    public function __construct(
        SchoolsMonthsFiscalRepository $schoolsMonthsFiscalRepository,
        AccountingPeriodRepository $accountingPeriodRepository,
        SeatingRepository $seatingRepository,
        SeatingPartRepository $seatingPartRepository,
        ReceiptRepository $receiptRepository,
        AuxiliaryReceiptRepository $auxiliaryReceiptRepository,
        AuxiliarySupplierRepository $auxiliarySupplierRepository,
        AuxiliarySeatRepository $auxiliarySeatRepository,
        TypeFormRepository $typeFormRepository,
        CatalogRepository $catalogRepository,
        TypeSeatRepository $typeSeatRepository,
        FiscalBalanceRepository $fiscalBalanceRepository
    )
    {

        $this->schoolsMonthsFiscalRepository = $schoolsMonthsFiscalRepository;
        $this->seatingRepository = $seatingRepository;
        $this->seatingPartRepository = $seatingPartRepository;
        $this->auxiliaryReceiptRepository = $auxiliaryReceiptRepository;
        $this->auxiliarySupplierRepository = $auxiliarySupplierRepository;
        $this->auxiliarySeatRepository = $auxiliarySeatRepository;
        $this->receiptRepository = $receiptRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->catalogRepository = $catalogRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->fiscalBalanceRepository = $fiscalBalanceRepository;
    }

    public function index(){
        $id = userSchool()->id;
        $closedYears = AccountingPeriod::where('school_id',$id)->orderBy('id', 'desc')->first();
        $year = $closedYears->year;
        Log::info("Ano ".json_encode($year));
        Log::info("Ano ".json_encode($closedYears));
        $lastYear = AccountingPeriod::where('school_id',$id)->where('period',$year.'12')->first();
        $error = "El sistema no esta en el mes de cierre adecuado o tiene algun otro problema puede contactar soporte: WhatsApp +506 8304-5030, Email: soporte@sistemasamigables.com";
        if($lastYear == null){
            return view('Accounting.closedYear.index', compact('closedYears', 'year','error'));
        }
        $error = null;
        Session::put('idFiscal',$closedYears->id);
        Session::put('year',$year);
        Session::put('periodo','12-'.$year);
        Session::put('periodoId',$lastYear->id);
        Session::put('dateFiscal',$year.'-12-31');
        \Log::info("Ano ".json_encode($lastYear->id).' -- '.Session::get('year'));

      $fiscal =  SchoolsMonthsFiscal::where('year',$year)->where('school_id',$id)->first();
        \Log::info("Ano ".json_encode($fiscal).' -- '.Session::get('year'));
        return view('Accounting.closedYear.index', compact('closedYears', 'year', 'error'));
    }

    public function validatePass(Request $request){
        $data = $this->convertionObjeto();
        if (!Auth::attempt(['email' => Auth::user()->email, 'password' => $data->password]))
        {
            return $this->errores('El password no coincide con nuestras credenciales.');
        }
        return $this->verifycationMonth();
    }

    /**
     * ---------------------------------------------------------------------
     * @Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
     * @Date Create: 2016-04-29
     * @Date Update: 2016-00-00
     * ---------------------------------------------------------------------
     * @Description:
     *
     *
     * @Pasos:
     *
     *
     *
     *
     *
     *
     * ----------------------------------------------------------------------
     * @return mixed
     * ----------------------------------------------------------------------
     */
    public function balanceFiscal()
    {
        $period = $this->accountingPeriodRepository->listsFiscal('id',Session::get('year'));
        $catalogs = $this->catalogRepository->allInstitution('style','Detalle');
        $count=0;
        foreach($catalogs AS $catalog):
            $balance = $this->seatingRepository->balanceCatalogoPeriod($catalog->id,$period);
            if($balance < 0):
                $balance = $balance * -1;
            endif;
           $data = [
               'balance'=>$balance,
               'date'=>Session::get('dateFiscal'),
               'schools_months_fiscal_id'=>Session::get('idFiscal'),
               'catalog_id'=>$catalog->id
                ];
            $fical = $this->fiscalBalanceRepository->getModel();
            if($balance > 0):
                /* Validamos los datos para guardar tabla menu */
                if ($fical->isValid($data)):
                    $fical->fill($data);
                    $fical->save();
                    else:
                    $count++;
                endif;
            endif;
        endforeach;

        if($count>0){
            DB::rollback();
            return false;
        }

    }
    /**
     * ---------------------------------------------------------------------
     * @Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
     * @Date Create: 2016-04-28
     * @Date Update: 2016-00-00
     * ---------------------------------------------------------------------
     * @Description: En esta accion Generaremos el asiento final del cierre
     * donde volveremos las cuentas de ingresos y gastos en cero, para saber
     * si hubieron perdidas o ganacia en la Institución.
     * ----------------------------------------------------------------------
     * @return mixed
     * ----------------------------------------------------------------------
     */
    public function generationSeatFinish()
    {
        try{
        $debit = $this->typeFormRepository->nameType('debito');
        $credit = $this->typeFormRepository->nameType('credito');
        $typeSeat = $this->typeSeatRepository->whereDuoData('DG');
        $period = $this->accountingPeriodRepository->listsFiscal('id',Session::get('year'));

         DB::beginTransaction();
            Log::info("Periodo: ".json_encode($period)."line: ".__LINE__);
        $catalogs = $this->catalogRepository->whereDuoFilterSchool('type',4,'style','Detalle','code','ASC');
        Log::info("Ingresos: ".json_encode($catalogs)."line: ".__LINE__);
        foreach($catalogs AS $catalog):
            $catalogCapital = $this->catalogRepository->accountNameSingleSchool('Fondo general');

            $balance = $this->seatingRepository->balanceCatalogoInPeriod($catalog->id,$period);

            if($balance < 0):
                $balance = $balance * -1;
            endif;
            $data = ['code'=>$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity,
                'detail'=>'Estamos Generando el Asiento de Cierre fiscal. '.Session::get('periodo'),
                'date'=>Session::get('dateFiscal'),
                'amount'=>$balance,
                'status'=>'Aplicado',
                'catalog_id'=>$catalogCapital[0]->id,
                'accounting_period_id'=>Session::get('periodoId'),
                'type_id'=>$credit,
                'type_seat_id'=>$typeSeat[0]->id,
                'user_created'=>currentUser()->id,
                'token'=>Crypt::encrypt($typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity)];
            Log::info("Array 4: ".json_encode($data)."line: ".__LINE__);
            $seating = $this->seatingRepository->getModel();
            if($balance > 0):
                /* Validamos los datos para guardar tabla menu */
                if ($seating->isValid($data)):
                    $seating->fill($data);
                    $seating->save();
                    Log::info(json_encode($seating)."line: ".__LINE__);
                endif;
                Log::info("error".json_encode($seating->error)."line: ".__LINE__);
            endif;

            $data = ['code'=>$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity,
                'detail'=>'Estamos Generando el Asiento de Cierre fiscal. '.Session::get('periodo'),
                'date'=>Session::get('dateFiscal'),
                'amount'=>$balance,
                'status'=>'Aplicado',
                'catalog_id'=>$catalog->id,
                'seating_id'=> $seating->id,
                'accounting_period_id'=>Session::get('periodoId'),
                'type_id'=>$debit,
                'type_seat_id'=>$typeSeat[0]->id,
                'user_created'=>currentUser()->id,
                'token'=>Crypt::encrypt($typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity)];
            $seatingPart = $this->seatingPartRepository->getModel();
            if($balance > 0):
                /* Validamos los datos para guardar tabla menu */
                if ($seatingPart->isValid($data)):
                    $seatingPart->fill($data);
                    $seatingPart->save();
                    Log::info(json_encode($seatingPart)."line: ".__LINE__);
                endif;
                Log::info("error".json_encode($seatingPart->error)."line: ".__LINE__);
            endif;
        endforeach;
        /**
         *
         **/

         /**
          *
          */
        $catalogs = $this->catalogRepository->whereDuoFilterSchool('type',6,'style','Detalle','code','ASC');
            Log::info("Gastos: ".json_encode($catalogs)."line: ".__LINE__);
            $balance=0;
            foreach($catalogs AS $catalog):
                $catalogCapital = $this->catalogRepository->accountNameSingleSchool('Fondo general');

                $balance = $this->seatingRepository->balanceCatalogoInPeriod($catalog->id,$period);

                if($balance < 0):
                    $balance = $balance * -1;
                endif;
                $data = ['code'=>$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity,
                    'detail'=>'Estamos Generando el Asiento de Cierre fiscal. '.Session::get('periodo'),
                    'date'=>Session::get('dateFiscal'),
                    'amount'=>$balance,
                    'status'=>'Aplicado',
                    'catalog_id'=>$catalogCapital[0]->id,
                    'accounting_period_id'=>Session::get('periodoId'),
                    'type_id'=>$debit,
                    'type_seat_id'=>$typeSeat[0]->id,
                    'user_created'=>currentUser()->id,
                    'token'=>Crypt::encrypt($typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity)];
                Log::info("Balance: ".json_encode($data)."line: ".__LINE__);
                $seating = $this->seatingRepository->getModel();
                if($balance > 0):
                    /* Validamos los datos para guardar tabla menu */
                    if ($seating->isValid($data)):
                        $seating->fill($data);
                        $seating->save();
                        Log::info(json_encode($seating)."line: ".__LINE__);
                    endif;
                    Log::info("error".json_encode($seating->error)."line: ".__LINE__);
                endif;

                $data = ['code'=>$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity,
                    'detail'=>'Estamos Generando el Asiento de Cierre fiscal. '.Session::get('periodo'),
                    'date'=>Session::get('dateFiscal'),
                    'amount'=>$balance,
                    'status'=>'Aplicado',
                    'catalog_id'=>$catalog->id,
                    'seating_id'=> $seating->id,
                    'accounting_period_id'=>Session::get('periodoId'),
                    'type_id'=>$credit,
                    'type_seat_id'=>$typeSeat[0]->id,
                    'user_created'=>currentUser()->id,
                    'token'=>Crypt::encrypt($typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity)];
                $seatingPart = $this->seatingPartRepository->getModel();
                if($balance > 0):
                    /* Validamos los datos para guardar tabla menu */
                    if ($seatingPart->isValid($data)):
                        $seatingPart->fill($data);
                        $seatingPart->save();
                        Log::info(json_encode($seatingPart)."line: ".__LINE__);
                    endif;
                    Log::info("error".json_encode($seatingPart->error)."line: ".__LINE__);
                endif;
            endforeach;

            $this->typeSeatRepository->updateWhere($typeSeat[0]->abbreviation);
            $this->balanceFiscal();
            $closedYears = $this->schoolsMonthsFiscalRepository->getModel()
                ->where('school_id',userSchool()->id)->orderBy('id', 'desc')->first();
            $year = new Carbon('01-01-'.$closedYears->year);
            Log::info("YEAR ".json_encode($year)." line: ".__LINE__.' - '.$closedYears->year);
            $this->schoolsMonthsFiscalRepository->getModel()->create([
                'year'=>$year->addYear(1)->year,
                'month_first'=>1,
                'month_end'=>12,
                'school_id'=>userSchool()->id,
            ]);
            DB::commit();
            return $this->exito('Se ha generado con exito el asiento de cierre fiscal');

        }catch (Exception $e) {
            Db::rollback();
            Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-28
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion verificaremos que este cuadrado los
    |   saldos de los auxiliares con contabilidad.
    |
    |@Pasos:
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function verificationBalanceAuxConta()
    {
        $debit = $this->typeFormRepository->nameType('debito');
        $credit = $this->typeFormRepository->nameType('credito');
        $period = $this->accountingPeriodRepository->listsFiscal('id',Session::get('year'));
        $auxSeatDebit= $this->auxiliarySeatRepository->sumInDuo('accounting_period_id',$period,'type_id',$debit);
        $auxSeatCredit= $this->auxiliarySeatRepository->sumInDuo('accounting_period_id',$period,'type_id',$credit);
        $total = $auxSeatDebit-$auxSeatCredit;
        $seatCA = $this->balanceSeatAccounting($period,$debit,$credit,'CONTROL ALUMNOS');
        if((number_format($total,0,'.','') == number_format($seatCA,0,'.',''))):
            return $this->exito('Todos los Auxiliares estan cuadrados correctamente con la contabilidad puede proseguir.');
        endif;

        return $this->errores(' Hay una Diferencia entre el Auxiliar y contabilidad favor corrijala para proseguir con el cierre.');
    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-28
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion verificamos en cada uno de los tiepos de
    |asiento no existan asientos sin aplicar, y si asi lo fuera se le
    |informa al usuario para que pueda corregirlo.
    |@Pasos:
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function verificationSeatPending()
    {
        $period = $this->accountingPeriodRepository->listsFiscal('id',Session::get('year'));
        $seat = $this->seatingRepository->whereDuoInList('accounting_period_id',$period,'status','No Aplicado','code');
        $result = array();
        $error = false;
        if($seat->isEmpty()):
            $result['seat']= 'Todos los Asientos estan aplicados';
        else:
            $result['seat']= 'Tiene Asientos sin aplicar: '.$seat;
            $error = true;
        endif;
        $seatPart = $this->seatingPartRepository->whereDuoInList('accounting_period_id',$period,'status','No Aplicado','code');
        if($seatPart->isEmpty()):
            $result['seatPart'] = 'Todos los Asientos estan aplicados';
        else:
            $result['seatPart'] = 'Tiene Asientos sin aplicar: '.$seatPart;
            $error = true;
        endif;
        $recipe = $this->receiptRepository->whereDuoInList('accounting_period_id',$period,'status','No Aplicado','receipt_number');
        if($recipe->isEmpty()):
            $result['Receipt'] = 'Todos los Recibos de Contabilidad estan aplicados';
        else:
            $result['Receipt'] = 'Tiene Recibos de Contabilidad sin aplicar: '.$recipe;
            $error = true;
        endif;
        $recipeAux = $this->auxiliaryReceiptRepository->whereDuoInList('accounting_period_id',$period,'status','No Aplicado','receipt_number');
        if($recipeAux->isEmpty()):
            $result['ReceiptAux'] = 'Todos los Recibos del Auxiliar estan aplicados';
        else:
            $result['ReceiptAux'] = 'Tiene Recibos del Auxiliar sin aplicar: '.$recipeAux;
            $error = true;
        endif;
        $seatAux = $this->auxiliarySeatRepository->whereDuoInList('accounting_period_id',$period,'status','No Aplicado','code');
        if($seatAux->isEmpty()):
            $result['seatPart'] = 'Todos los Asientos Auxiliar estan aplicados';
        else:
            $result['seatPart'] = 'Tiene Asientos Auxiliar sin aplicar: '.$seatAux;
            $error = true;
        endif;
        $supplier = $this->auxiliarySupplierRepository->whereDuoInList('accounting_period_id',$period,'status','No Aplicado','code');
        if($supplier->isEmpty()):
            $result['seatPart'] = 'Todos los auxiliares de Proveedores estan aplicados';
        else:
            $result['seatPart'] = 'Tiene auxiliares de Proveedores sin aplicar: '.$supplier;
            $error = true;
        endif;
        if($error){
            return $this->errores($result);
        }
        return $this->exito($result);

    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-27
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion vamos a verificar si la contabilidad
    | abarca los doces meses de un periodo segun haya estado registrado
    | en la tabla.
    |@Pasos:
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function verifycationMonth()
    {
       $period = $this->schoolsMonthsFiscalRepository->lastFilterSchool();
        $result = $this->accountingPeriodRepository->whereDuoFilterSchool('year',$period->year,'month',$period->month_end,'year','ASC');
        if($result->count()>0):
        return $this->exito('Listos estamos en el Periodo Correcto para el Cierre Fiscal, puede proseguir con el siguiente paso');
        endif;
        return $this->errores('Todavia no se encuentran registros elaborados con el ultimo mes del periodo fiscal');

    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-28
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: con esta accion privada obtenemos el saldo de la cuenta
    | de contabilidad a verificar.
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    private function balanceSeatAccounting($period,$debit,$credit,$name)
    {
        $catalog = $this->catalogRepository->accountNameSingleSchool($name);

        if($catalog->isEmpty()):
            $this->saveLogs('La cuenta de catalogo no fue encontrada',__CLASS__,__FUNCTION__,__LINE__,$catalog);
        endif;

        $accountingDebit = $this->seatingRepository->sumInTree('accounting_period_id',$period,'type_id',$debit,'catalog_id',$catalog[0]->id);
        $accountingCredit = $this->seatingRepository->sumInTree('accounting_period_id',$period,'type_id',$credit,'catalog_id',$catalog[0]->id);
        $accountingPartDebit = $this->seatingPartRepository->sumInTree('accounting_period_id',$period,'type_id',$debit,'catalog_id',$catalog[0]->id);
        $accountingPartCredit = $this->seatingPartRepository->sumInTree('accounting_period_id',$period,'type_id',$credit,'catalog_id',$catalog[0]->id);
        return ($accountingPartDebit+$accountingDebit)-($accountingCredit+$accountingPartCredit);

    }
}
