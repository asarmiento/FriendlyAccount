<?php

namespace AccountHon\Http\Controllers\Accounting;

use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\CatalogRepository;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckController extends Controller
{
    use Convert;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var SeatingRepository
     */
    private $seatingRepository;
    /**
     * @var SeatingPartRepository
     */
    private $seatingPartRepository;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;

    /**
     * @param \AccountHon\Repositories\TypeSeatRepository $typeSeatRepository
     * @param \AccountHon\Repositories\SeatingRepository $seatingRepository
     * @param \AccountHon\Repositories\SeatingPartRepository $seatingPartRepository
     * @param \AccountHon\Repositories\CatalogRepository $catalogRepository
     * @param \AccountHon\Repositories\TypeFormRepository $typeFormRepository
     * @param \AccountHon\Repositories\AccountingPeriodRepository $accountingPeriodRepository
     */
    public function __construct(
        TypeSeatRepository $typeSeatRepository,
        SeatingRepository $seatingRepository,
        SeatingPartRepository $seatingPartRepository,
        CatalogRepository $catalogRepository,
        TypeFormRepository $typeFormRepository,
        AccountingPeriodRepository $accountingPeriodRepository
    )
    {
        $this->typeSeatRepository = $typeSeatRepository;
        $this->seatingRepository = $seatingRepository;
        $this->seatingPartRepository = $seatingPartRepository;
        $this->catalogRepository = $catalogRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta vista enviamos todos los cheques creados a la
    |vista
    |
    |@Pasos:
    | Verificamos que exista el tipo de asiento cheque, luego buscamos todos
    | los asientos en seating y en seatingparts
    |----------------------------------------------------------------------
    | @return \Illuminate\View\View
    |----------------------------------------------------------------------
    */
    public function index()
    {
        $typeSeat = $this->typeSeatRepository->whereDuoData('CK');
        if($typeSeat->isEmpty()):
            Log::warning('No existe tipo de asiento CK: en la institucion '.userSchool()->name);
            abort(500,'prueba');
        endif;

        $seatings =  $this->seatingRepository->getModel()->Has('seatingPart')->where('status','aplicado')
            ->where('type_seat_id',$typeSeat[0]->id)->whereBetween('date',['2017-06-30',Carbon::now()->toDateString()])->orderBy('id','DESC')->paginate(100);

        return View('checks.index', compact('seatings'));

    }


    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta accion enviamos los datos a la vista para
    |   registrar los cheques emitidos
    |----------------------------------------------------------------------
    | @return \Illuminate\View\View
    |----------------------------------------------------------------------
    */
    public function create()
    {
        if(periodSchool()):

            $typeSeat = $this->typeSeatRepository->whereDuoData('CK');
            if($typeSeat->isEmpty()):
                Log::warning('No existe tipo de asiento CK: en la institucion '.userSchool()->name);
                abort(500,'Tipo Asiento');
            endif;
                $types = $this->typeFormRepository->nameAllDataType('credito');
                /**pendiente solo debe enviar los estudiantes de la institucion*/
                $catalogs = $this->catalogRepository->whereDuo('school_id',userSchool()->id,'style','Detalle','code','ASC');
                $catalogBanks = $this->catalogRepository->accountNameSchool('BANCOS');
            return View('checks.create', compact('types','typeSeat','catalogs','catalogBanks'));

        endif;

        return $this->errores('No tiene periodos contables Creados');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2016-01-12
    |---------------------------------------------------------------------
    |@Description: aqui guardamos los datos en la tabla de seating y
    |   seatingPart para registro de cheques,
    |   Se agrego codigo para permitir anular un cheque
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function store()
    {
        try{
            $seatings= $this->convertionObjeto();

            $verification=$this->seatingRepository->whereDuo('code',$seatings->codeCheck,'accounting_period_id',$seatings->accoutingPeriodCheck,'id','ASC');
            $Validation = $this->CreacionArray($seatings, 'Check');
            if(count($verification)>0):
                $Validation['token']= $verification[0]->token;
            endif;
            /* Verifica si es ck para anular de lo contrario pasa*/
            if($seatings->anularCheck==null):
                foreach ($Validation['accountPart'] as $key => $accountPart) {
                    if($Validation['account'] == $accountPart):
                        return $this->errores(['error' => 'Las cuentas no puede ser iguales']);
                    endif;
                    if(!($Validation['amount'][$key] > 0)){
                        return $this->errores(['error' => 'El monto debe ser mayor 0.']);
                    }
                }
                $Validation['detail'] = "Cheque generando para pago a: ".$Validation['paguesen'];
            endif;


            //unset($Validation['accountPart']);
           $credito    = $this->typeFormRepository->nameAllDataType('credito');

            $amount=0;
            foreach ($Validation['amount'] as $amountArr) {
                $amount += $amountArr;
            }

            $Validation['type_id'] = $credito->id;
            $Validation['amount'] = $amount;

           if($seatings->anularCheck):
                $Validation['amount'] = 1;
                $Validation['accountPart'][0] = $Validation['account'];
            endif;

            $data= $this->createArray($Validation,$verification);
        //    echo json_encode($data);die;

            /* Declaramos las clases a utilizar */
            $seating = $this->seatingRepository->getModel();
            /* Validamos los datos para guardar tabla menu */

            DB::beginTransaction();
            if ($seating->isValid($data)):
                $seating->fill($data);
                if( $seating->save() ){
                    if($this->seatingPart($seatings,$seating)){
                        DB::commit();
                        $this->typeSeatRepository->updateWhere('CK');

                        return $this->exito('Se registro con exito!!!');
                    }else{
                        DB::rollback();
                        \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - seatingPart.");
                        return $this->errores(['errorSave' => 'No se ha podido grabar el asiento de cheque.']);
                    }
                }else{
                    \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.");
                    return $this->errores(['errorSave' => 'No se ha podido grabar el asiento de cheque.']);
                }
            endif;
            DB::rollback();
            /* Enviamos el mensaje de error */
            return $this->errores($seating->errors);
        }catch (Exception $e) {
            Db::rollback();
            \Log::error($e);
            return $this->errores(array('checkSeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta accion creamos el arreglo para insertar los datos
    |   en la tabla seatingPart para los cheques.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function seatingPart($seatings, $seatingParent)
    {

        $type_id_child = $this->typeFormRepository->whereNot('id', $seatingParent->type_id);

        try{
            $seating = [
                'code'                 => $seatingParent->code,
                'detail'               => $seatingParent->detail,
                'date'                 => $seatingParent->date,
                'amount'               => 0,
                'status'               => 'Aplicado',
                'catalog_id'           => '',
                'seating_id'           => $seatingParent->id,
                'accounting_period_id' => $seatingParent->accounting_period_id,
                'type_id'              => $type_id_child[0]->id,
                'type_seat_id'         => $seatingParent->type_seat_id,
                'token'                => $seatingParent->token,
                'user_created'         => Auth::user()->id
            ];

            for( $i=0; $i < count($seatings->amountCheck); $i++):
                $amount                = $seatings->amountCheck[$i];
                $seating['detail']     = $seatings->detailCheck[$i];
                $seating['amount']     = $amount;
                $seating['catalog_id'] = $this->catalogRepository->token($seatings->accountPartCheck[$i])->id;
                if($seatings->anularCheck):
                    $seating['amount'] = 1;
                    $seating['catalog_id'] = $seatingParent->catalog_id;
                    $seating['detail']     = "Anulado";
                endif;

                $seatingChild = $this->seatingPartRepository->getModel();
                if ($seatingChild->isValid($seating)):
                    $seatingChild->fill($seating);
                    if( !$seatingChild->save() ){
                        return false;
                    }
                endif;
            endfor;
            return true;
        }catch (Exception $e) {
            \Log::error($e);
            return false;
        }
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta accion creamos el arreglo para insertar los datos
    |   en la tabla seating para los cheques.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function createArray($Validation,$verification)
    {

        if($verification->count()>0):
            $Validation['token']= $verification[0]->token;
            $Validation['date']= $verification[0]->date;
        endif;


        $typeSeat = $this->typeSeatRepository->token($Validation['tokenTypeSeat']);
        unset($Validation['tokenTypeSeat']);
        $Validation['type_seat_id']= $typeSeat->id;
        $catalog = $this->catalogRepository->token($Validation['account']);
        unset($Validation['account']);
        $Validation['catalog_id'] = $catalog->id;
        $period = $this->accountingPeriodRepository->token($Validation['accoutingPeriod']);
        unset($Validation['accoutingPeriod']);
        $Validation['accounting_period_id']=$period->id;
        $Validation['status'] = 'Aplicado';
        $Validation['user_created'] = Auth::user()->id;


        return $Validation;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta Accion Aplicamos los cheques registrados
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function status()
    {
        try {
            $token = $this->convertionObjeto();
            DB::beginTransaction();
            $seating = $this->seatingRepository->updateWhere($token->token, 'aplicado', 'status');

            if ($seating > 0):
                if($this->seatingPartRepository->updateWhere($token->token, 'aplicado', 'status') > 0){
                    if($this->typeSeatRepository->updateWhere('DG', userSchool()->id) > 0){
                        DB::commit();
                        return $this->exito("Se ha aplicado con exito!!!");
                    }
                    DB::rollback();
                    return $this->errores('No se puedo Aplicar el asiento, si persiste contacte soporte');
                }else{
                    DB::rollback();
                    return $this->errores('No se puedo Aplicar el asiento, si persiste contacte soporte');
                }
            endif;
            DB::rollback();
            return $this->errores('No se puedo Aplicar el asiento, si persiste contacte soporte');
        }catch (Exception $e) {
            DB::rollback();
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta Accion eliminamos la fila de la cuenta que
    | deseamos que nos hayamos equivocados
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function deleteDetail($id)
    {
        try {
            DB::beginTransaction();
           // $this->seatingPartRepository->getModel()->where('seating_id', $id)->delete();
            //$seating = $this->seatingRepository->getModel()->where('id', $id)->delete();
            DB::delete("DELETE FROM `seating_parts` WHERE seating_id = ".$id);
            $seating =  DB::delete("DELETE FROM `seatings` WHERE id = ".$id);

            if ($seating == 1):
                $typeSeat = $this->typeSeatRepository->whereDuoData('CK');
                $seatings = $this->seatingRepository->whereDuo('status', 'no aplicado', 'type_seat_id', $typeSeat[0]->id,'id','ASC');
                $total = 0;
                if (!$seatings->isEmpty()):
                    $total = $this->seatingRepository->getModel()->where('code', $seatings[0]->code)->groupBy('code')->sum('amount');
                endif;
                DB::commit();
                return $this->exito(['total' => $total, 'message' => 'Se ha eliminado con éxito']);
            endif;
            DB::rollback();
            return $this->errores('No se puedo eliminar la fila, si persiste contacte soporte');
        }catch (Exception $e) {
            DB::rollback();
            \Log::error($e);
            return $this->errores(array('auxiliarySeat Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }
}
