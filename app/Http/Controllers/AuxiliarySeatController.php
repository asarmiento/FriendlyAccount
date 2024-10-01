<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\Seating;
use AccountHon\Entities\SeatingPart;
use AccountHon\Entities\TypeForm;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\AuxiliarySeatRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\DegreesRepository;
use AccountHon\Repositories\FinancialRecordsRepository;
use AccountHon\Repositories\PeriodsRepository;
use AccountHon\Repositories\SeatingPartRepository;
use AccountHon\Repositories\SeatingRepository;
use AccountHon\Repositories\StudentRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


class AuxiliarySeatController extends Controller
{
    use Convert;
    /**
     * @var DegreesRepository
     */
    private $degreesRepository;
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
     * @var PeriodsRepository
     */
    private $periodsRepository;
    /**
     * @var AuxiliarySeatRepository
     */
    private $auxiliarySeatRepository;
    /**
     * @var StudentRepository
     */
    private $studentRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;
    /**
     * @var TempAuxiliarySeatController
     */
    private $tempAuxiliarySeatController;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var SeatingRepository
     */
    private $seatingRepository;
    /**
     * @var SeatingPartRepository
     */
    private $seatingPartRepository;

    /**
     * @var Route
     */


    /**
     * @param DegreesRepository $degreesRepository
     * @param TypeFormRepository $typeFormRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param FinancialRecordsRepository $financialRecordsRepository
     * @param PeriodsRepository $periodsRepository
     * @param AuxiliarySeatRepository $auxiliarySeatRepository
     * @param StudentRepository $studentRepository
     * @param AccountingPeriodRepository $accountingPeriodRepository
     * @param TempAuxiliarySeatController $tempAuxiliarySeatController
     * @param CatalogRepository $catalogRepository
     * @param SeatingRepository $seatingRepository
     * @param SeatingPartRepository $seatingPartRepository
     * @internal param AuxiliarySeatController $auxiliarySeatController
     * @internal param Route $route
     */
    public function __construct(
        DegreesRepository $degreesRepository,
        TypeFormRepository $typeFormRepository,
        TypeSeatRepository $typeSeatRepository,
        FinancialRecordsRepository $financialRecordsRepository,
        PeriodsRepository $periodsRepository,
        AuxiliarySeatRepository $auxiliarySeatRepository,
        StudentRepository $studentRepository,
        AccountingPeriodRepository $accountingPeriodRepository,
        TempAuxiliarySeatController $tempAuxiliarySeatController,
        CatalogRepository $catalogRepository,
        SeatingRepository $seatingRepository,
        SeatingPartRepository $seatingPartRepository

    ){
        set_time_limit(0);
        $this->middleware('auth');
        $this->middleware('sessionOff');
        $this->degreesRepository = $degreesRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->financialRecordsRepository = $financialRecordsRepository;
        $this->periodsRepository = $periodsRepository;
        $this->auxiliarySeatRepository = $auxiliarySeatRepository;
        $this->studentRepository = $studentRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
        $this->tempAuxiliarySeatController = $tempAuxiliarySeatController;

        $this->catalogRepository = $catalogRepository;
        $this->seatingRepository = $seatingRepository;
        $this->seatingPartRepository = $seatingPartRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {  set_time_limit(0);
        $typeSeat = $this->typeSeatRepository->code('DGA');
        if($typeSeat == false):
            Log::warning('No existe tipo de asiento DG: en la institucion '.userSchool()->name);
            abort(500,'prueba');
        endif;
        $auxiliarySeats = $this->auxiliarySeatRepository->nowYearSeatAuxiliar($typeSeat);


        return View('auxiliarySeats.index', compact('auxiliarySeats'));
    }

    public function indexNo()
    {  set_time_limit(0);
        $typeSeat = $this->typeSeatRepository->code('DGA');
        if($typeSeat == false):
            Log::warning('No existe tipo de asiento DG: en la institucion '.userSchool()->name);
            abort(500,'prueba');
        endif;
        $auxiliarySeats = $this->auxiliarySeatRepository->AllYearSeatAuxiliar($typeSeat);


        return View('auxiliarySeats.index', compact('auxiliarySeats'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-00-00
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion enviamos a la vista los datos para poder
    |   generar un cobro o darle un credito a un estudiando, y enviando a
    |   contabilidad los datos para generar el asiento correspondiente.
    |@Pasos:
    |   1. Verificamos que el usuario haya seleccionado una institucion
    |   2. Comprobamos el periodo contable si existe.
    |   3. Traemos todas los types.
    |   4. Buscamos el tipo de asiento de diario general auxiliar si no
    |      enviamos un error 500 y guardamos en el log el error.
    |   5. Traemos todos los estudiantes del año lectivo
    |   6. Traemos los movimientos del asiento que se esta digitando por el
    |      usuario.
    |   7. comprobamos que si existe movimientos que genera el total del
    |      asiento
        8. traemos tados las cuentas de ingreso y gasto de contabilidad
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function create()
    {
        # Paso 1
        if(userSchool()):
            # Paso 2
            if(periodSchool()):
                # Paso 3
                $types    = $this->typeFormRepository->getModel()->all();
                # Paso 4
                $typeSeat = $this->typeSeatRepository->whereDuoData('DGA');

                if($typeSeat->isEmpty()):
                    Log::warning('No existe tipo de asiento DGA: en la institucion , class '.__class__.', function '.__function__);
                    abort(500);
                endif;
                # Paso 5
                /**pendiente solo debe enviar los estudiantes de la institucion*/
                $financialRecords = $this->financialRecordsRepository->getModel()->whereBetween('year',[(periodSchool()->year-5),periodSchool()->year])->get();
                # Paso 6
                $auxiliarySeats   = $this->auxiliarySeatRepository
                                    ->whereDuo('status','no aplicado','type_seat_id',$typeSeat[0]->id,'id','ASC');
                $total = 0;
                if(!$auxiliarySeats->isEmpty()):
                    $total = $this->auxiliarySeatRepository->getModel()
                             ->where('status','no aplicado')
                             ->where('type_seat_id',$typeSeat[0]->id)
                             //->groupBy('code')
                             ->sum('amount');
                endif;
                $degrees = $this->degreesRepository->schoolsActive();
                $catalogs = $this->catalogRepository->accountIncome();
                return View('auxiliarySeats.create', compact('types','typeSeat','financialRecords','degrees','auxiliarySeats','total','catalogs'));
            endif;
            return $this->errores('No tiene periodos contables Creados');
        endif;
            Log::info('El usuario intento ingresar a , class '.__class__.', function '.__function__.' Directamente'.currentUser()->name);
        return Redirect::to('/');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-00-00
    |@Date Update: 2016-04-21
    |---------------------------------------------------------------------
    |@Description: Agregacion de una campo para registrar el id del asiento
    |   de conta donde fue registrado cada asiento.
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
    public function store()
    {
        try{
            $auxiliary = $this->convertionObjeto();

            $verification=$this->auxiliarySeatRepository->oneWhere('code',$auxiliary->codeAuxiliarySeat,'updated_at');
            $Validation = $this->CreacionArray($auxiliary, 'AuxiliarySeat');

            if($verification->count()>0):
                $Validation['token']= $verification[0]->token;
                $Validation['date']= $verification[0]->date;
            endif;

            $type=$this->typeFormRepository->token($Validation['type']);
            if($type):
             $Validation['type_id']= $type->id;
            endif;

            /* Creamos un array para cambiar nombres de parametros */
            $Validation['user_created'] = Auth::user()->id;
            $student = $this->studentRepository->token($Validation['financialRecord']);
            $Validation['financial_records_id'] = $student->financialRecords->id;
            $Validation['status'] = 'no aplicado';
            $Validation['typeCollection'] = 'otro';
            $Validation['accounting_period_id']= periodSchool()->id;
            $typeSeat=$this->typeSeatRepository->token($Validation['typeSeat']);
            $Validation['type_seat_id']= $typeSeat->id;

            DB::beginTransaction();
            /* Declaramos las clases a utilizar */
            $auxiliarys = $this->auxiliarySeatRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($auxiliarys->isValid($Validation)):
                $auxiliarys->fill($Validation);
                $auxiliarys->save();

                $total = $this->auxiliarySeatRepository->getModel()->where('code',$auxiliarys->code)->where('type_seat_id',$auxiliarys->type_seat_id)->groupBy('code')->sum('amount');
                  DB::commit();
                return $this->exito(['token'=>$Validation['token'],'id'=>$auxiliarys->id,'total'=>$total]);
            endif;
            /* Enviamos el mensaje de error */
            return $this->errores($auxiliarys->errors);
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    /**
     * @param $student
     * @param $amount
     * @param $message
     * @param $type
     * @param $collection
     * @return mixed
     */
    public function saveMatricula($student,$amount,$message,$type,$collection,$token)
    {
        try{
            $typeSeat=$this->typeSeatRepository->whereDuoData('DGA');
            $Validation = $this->generationSeat($student,$typeSeat,$amount,$message,$type,$collection,$token);
            //    echo json_encode($Validation); die;
            /* Declaramos las clases a utilizar */
            $auxiliarys = $this->auxiliarySeatRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($auxiliarys->isValid($Validation)):
                $auxiliarys->fill($Validation);
                $auxiliarys->save();

                return $this->exito('Se guardo con exito');
            endif;
            /* Enviamos el mensaje de error */
            return $this->errores($auxiliarys->errors);
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }
    /**
     * @param $student
     * @param $typeSeat
     * @param $amount
     * @param $message
     * @param $type
     * @param $collection
     * @return array
     */
    public function generationSeat($student,$typeSeat,$amount,$message,$type,$collection,$token){
        $seat=   ['date'=>dateShort(),
            'code'=>$typeSeat[0]->abbreviation(),
            'detail'=>$message.' del mes '.changeLetterMonth(periodSchool()->month),
            'amount'=>$amount,
            'financial_records_id'=>$student->id,
            'type_seat_id'=>$typeSeat[0]->id,
            'accounting_period_id'=>periodSchool()->id,
            'type_id'=>$this->typeFormRepository->nameType($type),
            'token'=>$token,
            'status'=>'aplicado',
            'typeCollection'=>$collection,
            'user_created' => Auth::user()->id
        ];
        return $seat;
    }

        /*
        |---------------------------------------------------------------------
        |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
        |@Date Create: 2015-08-01
        |@Date Update: 2015-08-29
        |---------------------------------------------------------------------
        |@Description: This Method according to create the insert
        |              Table auxiliary seats
        | @param:
        |  $student
        |  $typeSeat
        |  $amount
        |  $message
        |  $type
        |  $collection
        |  $period
        |  $token
        |----------------------------------------------------------------------
        | @return array
        |----------------------------------------------------------------------
        */
    public function generationOtherSeat($student,$typeSeat,$amount,$message,$type,$collection,$period,$token){
        $seat=   [
            'date'=>dateShort(),
            'code'=>$typeSeat[0]->abbreviation(),
            'detail'=>$message.' del mes '.changeLetterMonth($period->month),
            'amount'=>$amount,
            'financial_records_id'=>$student->id,
            'type_seat_id'=>$typeSeat[0]->id,
            'accounting_period_id'=>periodSchool()->id,
            'type_id'=>$this->typeFormRepository->nameType($type),
            'token'=>$token,
            'status'=>'aplicado',
            'typeCollection'=>$collection,
            'user_created' => Auth::user()->id
        ];
        return $seat;
    }
    /**
     * @param $message
     * @param $period
     * @return mixed
     */
    public function registerDataFinantial($message,$period){
        $Students = $this->studentRepository->listsWhere('school_id',userSchool()->id,'id');
        $typeSeat=$this->typeSeatRepository->whereDuoData('DGA');
        $token =Crypt::encrypt($typeSeat[0]->abbreviation());
        foreach($Students AS $Student):
            #
            $newStudent = $this->financialRecordsRepository->getModel()->where('student_id',$Student)->where('year',periodSchool()->year)->get();

            if(count($newStudent) > 0):
                $temp = $this->auxiliarySeatRepository->getModel()->where('financial_records_id',$newStudent[0]->id)->where('typeCollection','matricula')->get();
                $this->financialRecordsRepository->updateData($newStudent[0]->id,'status','aplicado');
                if($temp->isEmpty()):
                    // echo json_encode($newStudent[0]->monthly_discount); die;
                    $matricula= $this->saveMatricula($newStudent[0],$newStudent[0]->costs->tuition,$message.'la Matricula ','debito','matricula',$token);
                    if(!$matricula):
                        DB::rollback();
                        return $this->errores(['AuxiliarySeat' =>'No se pudo guardar los datos.']);
                    endif;
                    if($newStudent[0]->tuition_discount>0):
                        $this->saveMatricula($newStudent[0],$newStudent[0]->tuition_discount,$message.'del Descuento por Matricula ','credito','descuento',$token);
                    endif;
                endif;
            endif;
        endforeach;

        $this->typeSeatRepository->updateWhere('DGA');
    }
    /**
     * @param $token
     * @return \Illuminate\View\View
     */
    public function view($token){
        $auxiliarySeats   = $this->auxiliarySeatRepository->oneWhere('token',$token,'id');
        
        $total = $this->auxiliarySeatRepository->getModel()->where('code',$auxiliarySeats[0]->code)->where('type_seat_id',$auxiliarySeats[0]->type_seat_id)->sum('amount');
        return View('auxiliarySeats.view', compact('auxiliarySeats','total'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    public function status()
    {
        try {
            $token = $this->convertionObjeto();

            DB::beginTransaction();
            $auxiliary = $this->auxiliarySeatRepository->token($token->token);
            $seat = $this->arraySeating($auxiliary);
            $this->auxiliarySeatRepository->getModel()->where('token',$auxiliary->token)->update(['seating_id'=>$seat]);

            $balance=$this->updateBalance($token->token);
            $auxiliary = $this->auxiliarySeatRepository->updateWhere($token->token, 'aplicado', 'status');
            $this->seatingRepository->updateWhere($token->token, 'aplicado', 'status');
            $this->seatingPartRepository->updateWhere($token->token, 'aplicado', 'status');
           if ($auxiliary > 0):
                $this->typeSeatRepository->updateWhere('DGA');
                $this->typeSeatRepository->updateWhere('DG');
                DB::commit();
                return $this->exito("Se ha aplicado con exito!!!");
            endif;
            DB::rollback();
            return $this->errores('No se puedo Aplicar el asiento, si persiste contacte soporte');
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con este methodo actuializamos los saldos de cada uno de
    |   los estudiantes, cada vez que se hace un asiento auxiliar
    |
    |----------------------------------------------------------------------
    | @return token
    |----------------------------------------------------------------------
    */
    public function updateBalance($token){
        $seats = $this->auxiliarySeatRepository->getModel()->where('token',$token)->get();
        foreach($seats AS $seat):
            $student = $this->financialRecordsRepository->saldoStudent($seat->financial_records_id);

            if($seat->types->name=='debito'):
                $balance = $seat->amount + $student;
                $this->financialRecordsRepository->updateData($seat->financial_records_id,'balance',$balance);
            else:
                $balance =  $student - $seat->amount;
                $this->financialRecordsRepository->updateData($seat->financial_records_id,'balance',$balance);
            endif;

        endforeach;
        DB::commit();
        return $this->exito("se actualizo los saldos con exito");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-04-21
    |---------------------------------------------------------------------
    |@Description: Agregamos la eliminacion pasiva en las tables de asientos
    |   de contabilidad cuando se elimina una cuenta en asuxiliar.
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
    public function deleteDetail($id)
    {
        $seatAuxiliary = $this->auxiliarySeatRepository->find($id);

        $auxiliary= $this->auxiliarySeatRepository->destroy($id);

        if($auxiliary==1):

           $this->seatingRepository->destroy($seatAuxiliary->seating_id);
           // $parts=$this->seatingPartRepository->getModel()->where('seating_id',$seatAuxiliary->seating_id)->delete();
            $parts= DB::delete("DELETE FROM `seating_parts` WHERE seating_id = ".$seatAuxiliary->seating_id);

            $typeSeat = $this->typeSeatRepository->whereDuoData('DGA');
            $auxiliarySeats   = $this->auxiliarySeatRepository->whereDuo('status','no aplicado','type_seat_id',$typeSeat[0]->id,'id','ASC');
            $total=0;
            if(!$auxiliarySeats->isEmpty()):
                $total = $this->auxiliarySeatRepository->getModel()
                         ->where('status','no aplicado')
                         ->where('type_seat_id',$typeSeat[0]->id)
                         ->sum('amount');
            endif;
            return $this->exito(['total'=>$total, 'message' => 'Se ha eliminado con éxito']);
        endif;
        return $this->errores('No se puedo eliminar la fila, si persiste contacte soporte');
    }
        /*
        |---------------------------------------------------------------------
        |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
        |@Date Create: 2015-00-00
        |@Date Update: 2015-00-00
        |---------------------------------------------------------------------
        |@Description: This Method insert the data into the auxiliary table seats
        |              First generate the array and then insert the data.
        |@Pasos:
        | 1. Buscamos el id del tipo se asiento.
        | 2. Generamos el arreglo con los datos enviados
        | 3. validamos los datos para los que son requeridos
        | 4. Insertamos los datos a la tabla de asientos auxiliar.
        |@param
        | $student
        | $amount
        | $message
        | $type
        | $collection
        |@return
        |
        |----------------------------------------------------------------------
        | @return mixed
        |----------------------------------------------------------------------
        */
    public function saveMensualidad($student,$amount,$message,$type,$collection,$period,$token)
    {
        try{
            #paso 1
            $typeSeat=$this->typeSeatRepository->whereDuoData('DGA');
            #paso 2
            $Validation = $this->generationOtherSeat($student,$typeSeat,$amount,$message,$type,$collection,$period,$token);

            $auxiliarys = $this->auxiliarySeatRepository->getModel();
            #paso 3
            if ($auxiliarys->isValid($Validation)):
                #paso 4
                $auxiliarys->fill($Validation);
                $auxiliarys->save();

            endif;
            return $this->errores($auxiliarys->errors);
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-08-01
    |@Date Update: 2015-08-29
    |---------------------------------------------------------------------
    |@Description: In this method we generate monthly are charging the months that has not been
    |              generated student who has not generated.
    |@steps:
    | 1. Recibimos los datos de la vista el mes del periodo que querremos correr
    | 2. buscamos el periodo a generar el cobro
    | 3. buscamos todos los estudiantes de la institucion a generar el cobro.
    | 4. buscamos el code para generar el token para el asiento, Generamos el token para agregarlo al asiento.
    | 5. corremos todos los estudiantes de la institucion que esta trabajando.
    | 6. Buscamos los estudiantes en la tabla finantial record.
    | 7. Verificamos si existe el asiento de cobro de la mensualidad del mes elegido
    | 8. Insertamos la mensualidad de los alumnos que no se habia generado anteriormente.
    | 9. Comprobamos si tiene descuento entonces se genera el asiento.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function other(){
        #paso 1
        $period = $this->convertionObjeto();
        $periods= explode('/',$period->dateOther);
        #paso 2
        $idPeriod= $this->accountingPeriodRepository->getModel()->where('month',$periods[0])->where('year',$periods[1])->where('school_id',userSchool()->id)->get();
        #paso 3
        $Students = $this->studentRepository->listsWhere('school_id',userSchool()->id,'id');
        #paso 4
        $typeSeat=$this->typeSeatRepository->whereDuoData('DGA');
        $token =Crypt::encrypt($typeSeat[0]->abbreviation());
        #paso 5
        foreach($Students AS $Student):
            #paso 6
            $newStudent = $this->financialRecordsRepository->getModel()->where('student_id',$Student)->where('year',periodSchool()->year)->get();
            if(count($newStudent) > 0):
                #paso 7
                $temp = $this->auxiliarySeatRepository->getModel()->where('financial_records_id',$newStudent[0]->id)->where('accounting_period_id',$idPeriod[0]->id)->where('typeCollection','mensualidad')->get();
                if($temp->isEmpty()):
                    #paso 8
                    $mensualidad= $this->saveMensualidad($newStudent[0],$newStudent[0]->costs->monthly_payment,'Registro de la mensualidad ','debito','mensualidad',$idPeriod[0],$token);
                    if(!$mensualidad):
                        DB::rollback();
                        return $this->errores(['AuxiliarySeat' =>'No se pudo guardar los datos.']);
                    endif;
                 //   $this->tempAuxiliarySeatController->saveMatricula($newStudent[0],$newStudent[0]->costs->monthly_payment,'Registro de la Mensualidad del','debito',$idPeriod[0],$token);
                    #paso 9
                    if($newStudent[0]->monthly_discount > 0):
                        $this->saveMensualidad($newStudent[0],$newStudent[0]->monthly_discount,'Registro del Descuento por Mensualidad ','credito','descuento',$idPeriod[0],$token);
                    endif;
                endif;
            endif;
        endforeach;
        # Actualizamos los saldos de los estudiantes
        $this->updateBalance($token);
        $this->typeSeatRepository->updateWhere('DGA');
        return $this->exito('Se registro con exito!!!.');
    }
    /**
     * 
     * @param type $idFinantial
     * @param type $period
     * @return type
     */
    public function SaldoStudent($idFinantial, $period){
       $type = $this->typeFormRepository->nameType('debito');
      $debito =  $this->auxiliarySeatRepository->saldoStudentPeriod($idFinantial, $period, $type);
      $type = $this->typeFormRepository->nameType('credito');
      $credito =  $this->auxiliarySeatRepository->saldoStudentPeriod($idFinantial, $period, $type);
     
      $saldo = $debito-$credito;
      return $saldo;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-00-00
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
    public function recalcularSaldoStuden($finantial){

        $period =$this->accountingPeriodRepository->lists('id');

            $seatDebito = $this->auxiliarySeatRepository->saldoStudentInPeriod($finantial,$period,6);
            $seatCredito = $this->auxiliarySeatRepository->saldoStudentInPeriod($finantial,$period,7);
            $saldo = $seatDebito-$seatCredito;
            $this->financialRecordsRepository->updateData($finantial,'balance',$saldo);

    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-22
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
    public function arraySeating($data)
    {
        $typeSeat=$this->typeSeatRepository->whereDuoData('DG');
        $catalogs = $this->catalogRepository->accountNameSingleSchool('CONTROL ALUMNOS');
        $auxiliary = $this->auxiliarySeatRepository->getModel()->where('token',$data->token);
        $token = Crypt::encrypt($typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity);
         $datos= [
            'code'=>$typeSeat[0]->abbreviation.'-'.$typeSeat[0]->quatity,
            'detail'=>$data->detail,
            'date'=>$data->date,
            'amount'=>$auxiliary->sum('amount'),
            'status'=>'aplicado',
            'catalog_id'=>$catalogs[0]->id,
            'accounting_period_id'=>$data->accounting_period_id,
            'type_id'=>$data->type_id,
            'type_seat_id'=>$typeSeat[0]->id,
            'user_created'=>$data->user_created,
            'token'=>$token
        ];
        $seating = Seating::create($datos);
            if($seating){
                if($this->seatingPart($seating,$token,$auxiliary)){
                    DB::commit();
                    return $seating->id;
                }else{
                    DB::rollback();
                    \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - seatingPart.");
                    return $this->errores(['errorSave' => 'No se ha podido grabar el asiento.']);
                }
            }else{
                \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.");
                return $this->errores(['errorSave' => 'No se ha podido grabar el asiento.']);
            }

        DB::rollback();
        // Enviamos el mensaje de error
        return $this->errores($seating->errors);



    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-22
    |@Date Update: 2017-06-18
    |---------------------------------------------------------------------
    |@Description: Cambio de asiento cuando
    |
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function seatingPart($seating,$token,$auxiliary)
    {
            $period = $this->convertionObjeto();
            $catalogToken = $this->catalogRepository->token($period->catalogId);
            $type_id_child = $this->typeFormRepository->whereNot('id', $seating->type_id);
            $seating = [
                'code'=>$seating->code,
                'detail'=>$seating->detail,
                'date'=>$seating->date,
                'amount'=>$auxiliary->sum('amount'),
                'status'=>'aplicado',
                'catalog_id'=>$catalogToken->id,
                'seating_id'           => $seating->id,
                'accounting_period_id'=>$seating->accounting_period_id,
                'type_id'=>$type_id_child[0]->id,
                'type_seat_id'=>$seating->type_seat_id,
                'user_created'=>Auth::user()->id,
                'token'=>$token
            ];
                $seatingChild = SeatingPart::create($seating);
                    if($seatingChild){
                        DB::commit();
                        return $seatingChild->id;
                    }else{
                        DB::rollback();
                        \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - seatingPart.");
                        return $this->errores(['errorSave' => 'No se ha podido grabar el asiento.']);
                    }
                    DB::rollback();
                    // Enviamos el mensaje de error
                    return $this->errores($seatingChild->errors);
    }
}
