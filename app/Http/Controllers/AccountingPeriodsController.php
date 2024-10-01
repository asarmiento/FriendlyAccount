<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\BalancePeriod;
use AccountHon\Entities\Catalog;
use AccountHon\Traits\Convert;
use AccountHon\Traits\GeneralTransactionTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use AccountHon\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AccountingPeriodsController extends BaseAbtractController
{

    use Convert;
    use GeneralTransactionTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $accountingPeriods = $this->accountingPeriodRepository->oneWhere('school_id', userSchool()->id, 'id');
        return view('accountingPeriods.index', compact('accountingPeriods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $period = $this->periodDateIfAvailable();

        //echo $nextPeriod->month;die;
        if ($period['month'] == 12) {
            $nextPeriod = '01' . '-' . ($period['year'] + 1);
        } else {
            $nextPeriod = ($period['month'] + 1) . '-' . $period['year'];
            /*<<<<<<< HEAD

                        if($nextPeriod < 10){
                            $nextPeriod = '0'.(int)$nextPeriod;
            =======*/
            if ($nextPeriod < 10) {
                $nextPeriod = '0' . (int)$nextPeriod . '-' . $period['year'];

            }
        }
        return view('accountingPeriods.create', compact('nextPeriod'));
    }

    /**
     * Store a newly created resource in storage.
     * create @asarmiento
     * Pasos para cambio de periodo. 1 en
     * 1. Preparamos las variables de mes y año para ingresar a la tabla.
     * 2. Preparacion de array que se ingresara la informacion a la tabla de periodos
     * 3. Verificacion de la tabla asientos que no vean datos por aplicar.
     * 4. Verificacion de la tabla asientosPart que no vean datos por aplicar.
     * 5. Verificacion de la tabla recibos contabilidad que no vean datos por aplicar.
     * 6. Verificacion de la tabla recibos contabilidad que no vean datos por aplicar.
     * 7. Verificacion de la tabla asientos de auxiliar que no vean datos por aplicar.
     * 8. Traslado de saldos de saldo de la tabla asientos y asientosPart a la tabla balance
     * 9. Insertar el nuevo periodo para la institucion a la que esta trabajando.
     * 10. Despues de crear el periodo creamos el asiento del nuevo periodo de los alumnos
     * var $period
     * var $periodo
     * var $nextYear
     * var $nextMonth
     * var $Validation
     * var $seating
     * var $seatingPart
     * @return mixed
     */
    public function store()
    {
        set_time_limit(0);
        /* Capturamos los datos enviados por ajax */
        //   $period = $this->convertionObjeto();
        $periodo = periodSchool();
        try {
            //  $clave = bcrypt($period->clave);
            # Paso 1

            $period = $this->periodDateIfAvailable();

            if ($period['month'] == 12) {
                $nextMonth = '01';
                $nextYear = $period['year'] + 1;
            } else {
                $nextMonth = str_pad($period['month'] + 1, 2, '0', STR_PAD_LEFT);
                $nextYear = $period['year'];
            }
            //     Log::info('Paso Uno Año'.$nextYear.' Mes'.$nextMonth);
            # Paso 2
            $Validation = array('month' => $nextMonth, 'year' => $nextYear, 'school_id' => userSchool()->id, 'token' => bcrypt($nextYear . $nextMonth), 'period' => $nextYear . $nextMonth, 'user_created' => currentUser()->id);
            if (periodSchool()):
                //   Log::info('Paso dos '.$Validation);
                /* Declaramos las clases a utilizar */
                DB::beginTransaction();
                # Paso 3
                $seating = $this->seatingRepository->whereDuo('status', 'No Aplicado', 'accounting_period_id', periodSchool()->id, 'id', 'ASC');
                if (!$seating->isEmpty()):
                    DB::rollback();
                    return $this->errores(array('seating' => 'Existen Asientos pendientes de aplicar'));
                endif;
                # Paso 4
                $seatingPart = $this->seatingPartRepository->whereDuo('status', 'No Aplicado', 'accounting_period_id', periodSchool()->id, 'id', 'ASC');
                if (!$seatingPart->isEmpty()):
                    DB::rollback();
                    return $this->errores(array('seatingPart' => 'Existen Asientos pendientes de aplicar'));
                endif;
                # Paso 5
                $Receipt = $this->receiptRepository->whereDuo('status', 'No Aplicado', 'accounting_period_id', periodSchool()->id, 'id', 'ASC');
                if (!$Receipt->isEmpty()):
                    DB::rollback();
                    return $this->errores(array('receipt' => 'Existen Recibos en contabilidad pendientes de aplicar'));
                endif;
                # Paso 6
                if (userSchool()->type == 'education'):
                    $auxiliaryReceipt = $this->auxiliaryReceiptRepository->whereDuo('status', 'No Aplicado', 'accounting_period_id', periodSchool()->id, 'id', 'ASC');
                    if (!$auxiliaryReceipt->isEmpty()):
                        DB::rollback();
                        return $this->errores(array('auxiliaryReceipt' => 'Existen Recibos del auxiliar pendientes de aplicar'));
                    endif;
                    # Paso 7
                    $auxiliarySeat = $this->auxiliarySeatRepository->whereDuo('status', 'No Aplicado', 'accounting_period_id', periodSchool()->id, 'id', 'ASC');
                    if (!$auxiliarySeat->isEmpty()):
                        DB::rollback();
                        return $this->errores(array('auxiliaryReceipt' => 'Existen Asientos del auxiliar pendientes de aplicar'));
                    endif;
                endif;
            endif;
            # Paso 8
            $this->trasldoSaldo();

            # Paso 9
            $periods = $this->accountingPeriodRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($periods->isValid($Validation)):
                $periods->fill($Validation);


                if ($periods->save()) {   # Paso 10
                    if (userSchool()->type == 'education'):
                        $this->registerDataFinantial('debito', 'credito', 'Registro ', $periods, $periodo);
                        $this->revaluationBalanceCatalogo($periodo->id, $periods);
                    endif;
                }
                DB::commit();
               $this->actualizarSaldoBalance($periodo->id,$periods->id);
                /* Enviamos el mensaje de guardado correctamente */
                return $this->exito('Los datos se guardaron con exito!!!');
            endif;
            DB::rollback();
            /* Enviamos el mensaje de error */
            return $this->errores($periods->errors);
        } catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('accountingPeriod Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
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

    /**
     * @param $debito
     * @param $credito
     * @param $message
     * @param $period
     * @param $periodo
     * @return mixed
     * @internal param $Student
     */
    public function registerDataFinantial($debito, $credito, $message, $period, $periodo)
    {
        $Students = $this->studentRepository->lists('id');
        $token = Crypt::encrypt($Students);
        $newStudents = $this->financialRecordsRepository->searchStudent($Students);

        foreach ($newStudents AS $newStudent):
            # Cobro de mensualidad
            $mensualidad = $this->auxiliarySeatController->saveMensualidad($newStudent, $newStudent->costs->monthly_payment, $message . 'de la Mensualidad ', $debito, 'mensualidad', $period, $token);
            # comprobacion si tiene descuento
            if ($newStudent->monthly_discount > 0):
                $this->auxiliarySeatController->saveMensualidad($newStudent, $newStudent->monthly_discount, $message . 'del Descuento por Mensualidad  ', $credito, 'descuento', $period, $token);
            endif;
            $this->auxiliarySeatController->recalcularSaldoStuden($newStudent->id);
            #
            if (!$mensualidad):
                DB::rollback();
                return $this->errores(['AuxiliarySeat' => 'No se pudo guardar los datos.']);
            endif;
        endforeach;
        #Cambiamos el numero de asiento despues de generado los asientos.
        $this->typeSeatRepository->updateWhere('DGA', userSchool()->id);

    }

    /**
     * COn esta funcion buscamos los saldos de las cuentas
     * para poder guardarlo en la tabla de balance de periodo
     * mes a mes en cambio de periodo.
     */
    public function trasldoSaldo()
    {
        /* $catalogs = $this->catalogRepository->accountSchool();

         foreach ($catalogs AS $catalog):
             $balanceBefore = $this->balancefinishBeforeMonth($catalog);

             $balance = $this->balancePeriodRepository->saldoTotalPeriod($catalog, periodSchool()->id);
             $balanceAfter = $balanceBefore+$balance;

             $data = [
             'catalog_id' => $catalog->id,
             'amount' => $balanceAfter,
             'period' => periodSchool()->period,
              'year' => periodSchool()->year,
              'school_id' => userSchool()->id
              ];
             $balancePeriod = $this->balancePeriodRepository->getModel();
             /// Validamos los datos para guardar tabla menu
             if ($balancePeriod->isValid($data)):
                 $balancePeriod->fill($data);
                 $balancePeriod->save();
             /// Enviamos el mensaje de guardado correctamente
             endif;
         endforeach;*/

        $idBalance = BalancePeriod::where('school_id', userSchool()->id)->get()->last();

        $catalogs = $this->catalogRepository->accountSchool();

        foreach ($catalogs AS $catalog):

            $amount = $this->seatingRepository->balanceCatalogoPeriod($catalog->id, periodSchool()->id);
            $monto = 0;
            if ($idBalance) {
                $monto = $this->balancePeriodRepository->getModel()
                    ->where('catalog_id', $catalog->id)->where('accounting_period_id', $idBalance->accounting_period_id)->sum('amount');
            }
            $total = $amount + $monto;
            if ($this->balancePeriodRepository->getModel()->where('accounting_period_id', periodSchool()->id)->where('catalog_id', $catalog->id)->count() > 0) {
                $this->balancePeriodRepository->getModel()->where('accounting_period_id', periodSchool()->id)
                    ->where('catalog_id', $catalog->id)->update(['amount' => ($total)]);
            } else {
                $balance = $this->balancePeriodRepository->getModel();
                $balance->fill([
                    'catalog_id' => $catalog->id,
                    'accounting_period_id' => periodSchool()->id,
                    'amount' => ($total),
                    'school_id' => userSchool()->id,
                    'year' => periodSchool()->year]);
                $balance->save();
            }
        endforeach;

    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 01/08/16 08:16 AM   @Update 0000-00-00
     ***************************************************
     * @Description: con esta accion traemos el saldo
     *   final del mes anterior para poder hacer el cambio
     *   de mes
     *
     * @Pasos:
     *
     *
     * @param $catalog
     * @return \AccountHon\Repositories\amount
     **************************************************/
    public function balancefinishBeforeMonth($catalog)
    {

        $period = periodSchool()->period - 1;
        if (periodSchool()->month == '01' || periodSchool()->month == '1'):
            $period = (periodSchool()->year - 1) . '12';
        endif;
        $periodBefore = $this->accountingPeriodRepository->listsWhere('period', $period, 'id');
        return $this->balancePeriodRepository->saldoTotalPeriod($catalog, $periodBefore[0]);

    }

    private function periodDateIfAvailable()
    {
        $nextPeriod = periodSchool();

        if ($nextPeriod):
            $month = $nextPeriod->month;
            $year = $nextPeriod->year;
        else:
            $month = Carbon::now()->subMonth(1)->format('m');
            $year = Carbon::now()->format('Y');
        endif;

        return ['month' => $month, 'year' => $year];
    }
}
