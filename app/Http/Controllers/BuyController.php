<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Repositories\Accounting\SupplierRepository;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\AuxiliarySupplierRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\SeatingPartRepository;
use AccountHon\Repositories\SeatingRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuyController extends Controller
{
    use Convert;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;
    /**
     * @var AuxiliarySupplierController
     */
    private $auxiliarySupplierController;
    /**
     * @var AuxiliarySupplierRepository
     */
    private $auxiliarySupplierRepository;

    /**
     * @param \AccountHon\Repositories\TypeSeatRepository $typeSeatRepository
     * @param \AccountHon\Repositories\SeatingRepository $seatingRepository
     * @param \AccountHon\Repositories\SeatingPartRepository $seatingPartRepository
     * @param \AccountHon\Repositories\CatalogRepository $catalogRepository
     * @param \AccountHon\Repositories\TypeFormRepository $typeFormRepository
     * @param \AccountHon\Repositories\Accounting\SupplierRepository $supplierRepository
     * @param \AccountHon\Repositories\AccountingPeriodRepository $accountingPeriodRepository
     * @param \AccountHon\Http\Controllers\AuxiliarySupplierController $auxiliarySupplierController
     * @param AuxiliarySupplierRepository $auxiliarySupplierRepository
     */
    public function __construct(
        TypeSeatRepository $typeSeatRepository,
        SeatingRepository $seatingRepository,
        SeatingPartRepository $seatingPartRepository,
        CatalogRepository $catalogRepository,
        TypeFormRepository $typeFormRepository,
        SupplierRepository $supplierRepository,
        AccountingPeriodRepository $accountingPeriodRepository,
        AuxiliarySupplierController $auxiliarySupplierController,
        AuxiliarySupplierRepository $auxiliarySupplierRepository
    )
    {
        $this->typeSeatRepository = $typeSeatRepository;
        $this->seatingRepository = $seatingRepository;
        $this->seatingPartRepository = $seatingPartRepository;
        $this->catalogRepository = $catalogRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->supplierRepository = $supplierRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
        $this->auxiliarySupplierController = $auxiliarySupplierController;
        $this->auxiliarySupplierRepository = $auxiliarySupplierRepository;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-01
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta vista enviamos todos las compras creados a la
    |vista
    |
    |@Pasos:
    | Verificamos que exista el tipo de asiento compras, luego buscamos todos
    | los asientos en seating y en seatingparts
    |----------------------------------------------------------------------
    | @return \Illuminate\View\View
    |----------------------------------------------------------------------
    */
    public function index()
    {
        $typeSeat = $this->typeSeatRepository->whereDuoData('COMP');
        if($typeSeat->isEmpty()):
            Log::warning('No existe tipo de asiento COMP: en la institucion '.userSchool()->name);
            abort(500,'prueba');
        endif;

        $seatings = $this->seatingRepository->whereDuo('status','aplicado','type_seat_id',$typeSeat[0]->id,'id','ASC');
        $seatingsParts = $this->seatingPartRepository->whereDuo('status','aplicado','type_seat_id',$typeSeat[0]->id,'id','ASC');

        return View('buy.index', compact('seatings', 'seatingsParts'));

    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-01
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
            $catalogs = $this->catalogRepository->accountNameSchool('BANCOS');
            $typeSeat = $this->typeSeatRepository->whereDuoData('COMP');
            /**pendiente solo debe enviar los estudiantes de la institucion*/
            $suppliers = $this->supplierRepository->allFilterScholl();
            return View('buy.create', compact('typeSeat','suppliers','catalogs'));
        endif;
        return $this->errores('No tiene periodos contables Creados');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-01
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: aqui guardamos los datos en la tabla de seating y
    |   seatingPart para registro de cheques
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function store()
    {
        try{
            DB::beginTransaction();
            $seatings= $this->convertionObjeto();

            if($seatings->referenceBuy == ""):
                return $this->errores(['reference'=>'El numero de referencia es Obligatorio']);
            endif;
            $supplier = $this->supplierRepository->token($seatings->supplierBuy);
            $bill = $this->auxiliarySupplierRepository->whereDuo('supplier_id',$supplier->id,'bill',$seatings->referenceBuy,'id','ASC');

            if(count($bill) > 0):
                return $this->errores(['reference'=>'La factura ya fue registrada # asiento '.$bill[0]->code]);
            endif;
            $Validation = $this->CreacionArray($seatings, 'Buy');

            $data= $this->createArray($Validation);


            /* Declaramos las clases a utilizar */
            $seating = $this->seatingRepository->getModel();
            /* Validamos los datos para guardar tabla menu */


            $this->auxiliarySupplierController->store();

            if ($seating->isValid($data)):
                $seating->fill($data);
                if( $seating->save() ){

                    if($this->seatingPart($seatings,$seating)){
                        $this->typeSeatRepository->updateWhere('COMP');
                        $this->typeSeatRepository->updateWhere('ACOMP');
                        DB::commit();
                        return $this->exito('Se grabo con exito la Factura');
                    }else{
                        DB::rollback();
                        \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - seatingPart.".__LINE__);
                        return $this->errores(['errorSave' => 'No se ha podido grabar el asiento de cheque.']);
                    }
                }else{
                    \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.".__LINE__);
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
    |@Date Create: 2015-10-01
    |@Date Update: 2015-10-27
    |---------------------------------------------------------------------
    |@Description: En esta accion creamos el arreglo para insertar los datos
    |   en la tabla seatingPart para los cheques.
    |   buscamos las cuentas que vamos afectar en la comrpa, luego carbamos
    |   el monto en un arreglo con el id de la cuenta donde se guardara
    |   comprobamos si viene impuesto y descuento si no viene omitimos esa
    |   cuentas.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function seatingPart($seatings, $seatingParent)
    {

        $supplier=$this->catalogRepository->accountCodeSchool('05-01-00-00-000');
        if($supplier->isEmpty()):
            \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.".__LINE__.' Resultado de Consulta'.$supplier);
            return $this->errores(['errorSave' => 'La Compra no se puede Generar por que no Existe la cuenta: Impuesto de Ventas con codigo: 05-01-00-00-000 ']);
        endif;
        $supplierIvi=$this->catalogRepository->accountCodeSchool('02-01-02-01-000');
        if($supplierIvi->isEmpty()):
            \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.".__LINE__.' Resultado de Consulta'.$supplierIvi);
            return $this->errores(['errorSave' => 'La Compra no se puede Generar por que no Existe la cuenta: Impuesto de Ventas con codigo: 02-01-02-01-000 ']);
        endif;
        $supplierDesc=$this->catalogRepository->accountCodeSchool('04-01-03-00-000');
        if($supplierDesc->isEmpty()):
            \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.".__LINE__.' Resultado de Consulta'.$supplierDesc);
            return $this->errores(['errorSave' => 'La Compra no se puede Generar por que no Existe la cuenta: Impuesto de Ventas con codigo: 04-01-03-00-000 ']);
        endif;
            $mountBuy = [$supplier[0]->id => ($seatings->totalExcentoBuy+$seatings->totalGravadoBuy+$seatings->otherBuy)];

            #si la factura es tipo iva se le resta el impuesta tambien
            if($seatings->typeInvoice==2):
                if($seatings->totalExcentoBuy > 0):
                    $mountBuy[$supplier[0]->id] = $mountBuy[$supplier[0]->id]+$seatings->discountBuy;
                endif;
            endif;

            # Si existe impuesto almacenara la cantidad en la cuenta respectiva
            if($seatings->ivaBuy > 0):
                $mountBuy[$supplierIvi[0]->id] = $seatings->ivaBuy;
            endif;

            # si existe descuento almacenara la cantidad en la cuenta respectiva
            if($seatings->discountBuy > 0):
                $mountBuy[$supplierDesc[0]->id] = ($seatings->discountBuy+$seatings->subsidizedBuy)
                ;
            endif;
            $verificacion=0;
            foreach( $mountBuy AS $key => $value):

                $amount     = $value;
                $detail     = $seatingParent->detail.' factura #'.$seatings->referenceBuy;
                $catalog     = $key;
                $type_id_child = $this->typeFormRepository->whereNot('id', $seatingParent->type_id);
                $type=$type_id_child[0]->id;
                $this->arraySeatingPart($seatingParent,$amount,$catalog,$detail,$type);
            $verificacion += $amount;
            endforeach;
        if($seatingParent->amount ==$verificacion):
            return true;
        endif;
        \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.".__LINE__.' Resultado de Consulta'.$seatingParent->amount.' ver '.$verificacion);
        return $this->errores(['errorSave' => 'Hay una Diferencia en la factura que trata de Ingresar']);


    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-14
    |@Date Update: 2015-10-27
    |---------------------------------------------------------------------
    |@Description: con esta accion creamos el array para insertar los datos
    |   a  la tabla part de asientos y enviamos ya al insert los datos para
    |   que se almacenen
    ||----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function arraySeatingPart($seatingParent,$amount,$catalog,$detail,$type)
    {
        $seating = [
            'code'                 => $seatingParent->code,
            'detail'               => $detail,
            'date'                 => $seatingParent->date,
            'amount'               => $amount,
            'status'               => 'aplicado',
            'catalog_id'           => $catalog,
            'seating_id'           => $seatingParent->id,
            'accounting_period_id' => $seatingParent->accounting_period_id,
            'type_id'              => $type,
            'type_seat_id'         => $seatingParent->type_seat_id,
            'token'                => $seatingParent->token,
            'user_created'         => Auth::user()->id
        ];

         return $this->insertSeatPart($seating);
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-14
    |@Date Update: 2015-10-27
    |---------------------------------------------------------------------
    |@Description: primero comprobamos que se esten recibiendo todos los
    |   campos requeridos correctamente y luego guardamos.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function insertSeatPart($seating)
    {
        $income = $this->catalogRepository->find($seating['catalog_id']);
        $type_id_child = $this->typeFormRepository->whereNot('id', $seating['type_id']);
        $seatingChild = $this->seatingPartRepository->getModel();

        if($income->type == 4):
            $seating['type_id']=$type_id_child[0]->id;
        endif;

        if ($seatingChild->isValid($seating)):
            $seatingChild->fill($seating);
            $seatingChild->save();

        endif;

        return $this->errores($seatingChild);
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-01
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta accion creamos el arreglo para insertar los datos
    |   en la tabla seating para los cheques.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function createArray($Validation)
    {
        $cedito    = $this->typeFormRepository->nameAllDataType('credito');
        $debito    = $this->typeFormRepository->nameAllDataType('debito');
        $typeSeat = $this->typeSeatRepository->token($Validation['tokenTypeSeat']);
        unset($Validation['tokenTypeSeat']);
        $Validation['type_seat_id']= $typeSeat->id;
        $Validation['code']= $Validation['typeSeat'];
        unset($Validation['typeSeat']);
        $period = $this->accountingPeriodRepository->token($Validation['accoutingPeriod']);
        unset($Validation['accoutingPeriod']);
        $Validation['accounting_period_id']=$period->id;
        $supplier = $this->supplierRepository->token($Validation['supplier']);
        $amount = $Validation['total'];

        unset($Validation['supplier']);
        unset($Validation['totalExcento']);
        unset($Validation['totalGravado']);
        unset($Validation['dateExpiration']);
        unset($Validation['discount']);
        unset($Validation['iva']);
        unset($Validation['ivi']);
        unset($Validation['other']);
        unset($Validation['total']);
        unset($Validation['reference']);
        if($Validation['type']==0):
            $catalog = $this->catalogRepository->accountCodeSchool('01-01-01-01-001');
            $Validation['catalog_id'] = $catalog[0]->id;
            $Validation['type_id']=$cedito->id;
            $Validation['amount']=$amount;
        elseif($Validation['type']==1):
            $catalog = $this->catalogRepository->token($Validation['transf']);
            $Validation['catalog_id'] = $catalog->id;
            $Validation['type_id']=$cedito->id;
            $Validation['amount']=$amount;
        elseif($Validation['type']==2):
            $catalog = $this->catalogRepository->accountCodeSchool('02-01-01-00-000');
            $Validation['catalog_id'] = $catalog[0]->id;
            $Validation['type_id']=$cedito->id;
            $Validation['amount']=$amount;
        endif;
        unset($Validation['type']);
        unset($Validation['transf']);
        $Validation['detail'] = 'Compra a '.$supplier->name;
        $Validation['status'] = 'aplicado';
        $Validation['user_created'] = Auth::user()->id;

        return $Validation;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-01
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
                    if($this->typeSeatRepository->updateWhere('COMP', userSchool()->id) > 0){
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
    |@Date Create: 2015-10-01
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
                $typeSeat = $this->typeSeatRepository->whereDuoData('COMP');
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
