<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 05:54 PM
 */

namespace AccountHon\Http\Controllers\Restaurant;


use AccountHon\Http\Controllers\AuxiliarySupplierController;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Accounting\SupplierRepository;
use AccountHon\Repositories\AuxiliarySupplierRepository;
use AccountHon\Repositories\General\InventoryRepository;
use AccountHon\Repositories\Restaurant\InventoriesIncomeRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\Restaurant\PaymentMethodRepository;
use AccountHon\Repositories\Restaurant\RawProductInvoiceRepository;
use AccountHon\Repositories\Restaurant\RawMaterialRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class InventoriesIncomeController extends Controller
{
    use Convert;
    /**
     * @var InventoriesIncomeRepository
     */
    private $inventoriesIncomeRepository;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var PaymentMethodRepository
     */
    private $paymentMethodRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;
    /**
     * @var RawProductInvoiceRepository
     */
    private $rawProductInvoiceRepository;
    /**
     * @var RawMaterialRepository
     */
    private $rawMaterialRepository;
    /**
     * @var AuxiliarySupplierController
     */
    private $auxiliarySupplierController;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var AuxiliarySupplierRepository
     */
    private $auxiliarySupplierRepository;
    /**
     * @var InventoryRepository
     */
    private $inventoryRepository;

    /**
     * InventoriesIncomeController constructor.
     * @param \AccountHon\Repositories\Restaurant\InventoriesIncomeRepository $inventoriesIncomeRepository
     * @param \AccountHon\Repositories\Accounting\SupplierRepository $supplierRepository
     * @param \AccountHon\Repositories\Restaurant\PaymentMethodRepository $paymentMethodRepository
     * @param \AccountHon\Repositories\Restaurant\InvoiceRepository $invoiceRepository
     * @param \AccountHon\Repositories\Restaurant\RawProductInvoiceRepository $rawProductInvoiceRepository
     * @param \AccountHon\Repositories\Restaurant\RawMaterialRepository $rawMaterialRepository
     * @param \AccountHon\Http\Controllers\AuxiliarySupplierController $auxiliarySupplierController
     * @param \AccountHon\Repositories\TypeFormRepository $typeFormRepository
     * @param \AccountHon\Repositories\TypeSeatRepository $typeSeatRepository
     * @param AuxiliarySupplierRepository $auxiliarySupplierRepository
     * @param InventoryRepository $inventoryRepository
     */
    public function __construct(
        InventoriesIncomeRepository $inventoriesIncomeRepository,
        SupplierRepository  $supplierRepository,
        PaymentMethodRepository $paymentMethodRepository,
        InvoiceRepository $invoiceRepository,
        RawProductInvoiceRepository $rawProductInvoiceRepository,
        RawMaterialRepository $rawMaterialRepository,
        AuxiliarySupplierController $auxiliarySupplierController,
        TypeFormRepository $typeFormRepository,
        TypeSeatRepository $typeSeatRepository,
        AuxiliarySupplierRepository $auxiliarySupplierRepository,
        InventoryRepository $inventoryRepository
    )
    {

        $this->inventoriesIncomeRepository = $inventoriesIncomeRepository;
        $this->supplierRepository = $supplierRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->rawProductInvoiceRepository = $rawProductInvoiceRepository;
        $this->rawMaterialRepository = $rawMaterialRepository;
        $this->auxiliarySupplierController = $auxiliarySupplierController;
        $this->typeFormRepository = $typeFormRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->auxiliarySupplierRepository = $auxiliarySupplierRepository;
        $this->inventoryRepository = $inventoryRepository;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Enviamos lo detalles de todas las facturas de compras
    |   a la vista.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function index()
    {
        $buys = $this->inventoriesIncomeRepository->allFilterScholl();

        return view('restaurant.inventories.incomes.index',compact('buys'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-29
    |@Date Update: 2015-01-03
    |---------------------------------------------------------------------
    |@Description: Con esta accion enviamos los datos requeridos para el
    |   registro de las compras.
    |   se agrego la referencia de los registros
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function create()
    {
        $suppliers = $this->supplierRepository->allFilterScholl();
        $paymentMethods = $this->paymentMethodRepository->oneWhere('type','buy','id');

        $ferencies = $this->reference();

        return view('restaurant.inventories.incomes.create',
            compact('suppliers','paymentMethods','ferencies'));
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
    private function reference()
    {
        $ferencies = 'COMP-0001';
        $inventaries = $this->inventoriesIncomeRepository->last();
        if($inventaries):
            $ferencies = $inventaries->reference + 1;
        endif;

        return $ferencies;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
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
    public function filterSupplier()
    {
        $token = $this->convertionObjeto()->tokenSupplier;
        $supplier = $this->supplierRepository->token($token);
        $products = array();
        foreach ($supplier->brands as $key => $brand) {
            foreach ($brand->products as $key => $product) {
                $product->label = $product->code.' - '.$product->description;
                array_push($products, $product);
            }
        }
        return $this->exito($products);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-04
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
    public function store()
    {
        try {
            $data = $this->convertionObjeto();

            $dataIncome = $this->CreacionArray($data, 'InventoriesIncome');

            DB::beginTransaction();
            /* Factura de compras */
            $invoices = $this->invoices($dataIncome);

            $invoice = $this->invoiceRepository->getModel();
            DB::commit();
            if($invoice->isValid($invoices)):
                DB::commit();
                $invoice->fill($invoices);
                $invoice->save();

            endif;

           if($invoice->errors):
               DB::rollback();
               $this->saveLogs('No existe el tipo de asiento',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
               return $this->errores($invoice->errors);
           endif;

            $preparing = $this->preparing($invoices);
            if(is_array($preparing)):
                if( ($preparing['success'] == false)):
                    DB::rollback();
                    return $this->errores($preparing['message']);
                endif;
            endif;
            DB::commit();

            $messageInvoice = "La factura se Guardo con exito";

            /* Ingresos de inventario */
            $income = $this->inventorieIncomes($dataIncome, $invoice->id);

            $inventoriesIncomes = $this->inventoriesIncomeRepository->getModel();

            if(!$inventoriesIncomes->isValid($income)):
                DB::rollback();
                $this->saveLogs('No existe el tipo de asiento',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
                return $this->errores($inventoriesIncomes->errors);
            endif;
            DB::commit();
            $inventoriesIncomes->fill($income);
            $inventoriesIncomes->save();
            DB::commit();

            $messageIncome = "La Ingreso de Inventario se Guardo con exito";

            /* Productos factura el detalle */

            $products = $this->productinvoice($dataIncome, $invoice->id);
            foreach($products AS $product):
                DB::commit();
                $rawProduct = $this->rawProductInvoiceRepository->getModel();
                $rawProduct->fill($product);
                $rawProduct->save();
                $this->rawMaterialRepository->updatePrice($rawProduct->raw_product_id,$rawProduct->price);
                $this->inventoryRepository->increase($rawProduct->raw_product_id,$rawProduct->amount);
            \Log::info("prueba de datos: ".json_encode($product));
            endforeach;
            DB::commit();
            $this->typeSeatRepository->updateWhere('ACOMP');
            $this->typeSeatRepository->updateWhere('CPA');
            return $this->exito('Se guardaron todos los registros corectamente');

        }catch (Exception $e) {
            Log::error($e);
            return $this->errores(array('inventories Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-04-12
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
    public function preparing($data){
        $debito    = $this->typeFormRepository->nameAllDataType('debito');
        $token = bcrypt($debito->id);

        $type = 'contado';
        $dateExpiration=null;
        if($data['payment_method_id']==2):
            $type = 'credito';
            $dateExpiration= $data['due_date'];
        endif;

        if($data['subtotal_exempt'] > 0):
            $mountBuy = ['Productos exento  ' => $data['subtotal_exempt']];
        endif;

        if($data['subtotal_taxed'] > 0):
            $mountBuy['Productos  gravados'] =  $data['subtotal_taxed'];
        endif;

        if($data['tax'] > 0):
            $mountBuy['impuesto de Productos gravados '] = $data['tax'];
        endif;
        $typeId ='';
        if($data['discount'] > 0):
            $typeId = $debito->id;
            $mountBuy['Descuento en compra '] = $data['discount'];
        endif;
        $data['detail']='Compra realizada ';
        foreach($mountBuy AS $key => $value):
            $arreglo = $this->insertAuxiliarSupplier($data,$value,$key,$type,$token,$typeId,$dateExpiration);

            if(is_array($arreglo)):
                   if( ($arreglo['success'] == false)):
                       DB::rollback();
                       return $arreglo;
                   endif;
            endif;

            $seating = $this->auxiliarySupplierRepository->getModel();
        \Log::info('datos para guardar en auxiliar '.json_encode($arreglo));
            if ($seating->isValid($arreglo)):
                $seating->fill($arreglo);
                $seating->save();
            endif;
        endforeach;

        return $seating->errors;

    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
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
    public function insertAuxiliarSupplier($data,$amount,$message,$type,$token,$typeId)
    {


        $supplier = $this->supplierRepository->token($data['supplier']);
        $typeSeat=$this->typeSeatRepository->whereDuoData('ACOMP');
        if($typeSeat->isEmpty()):
            $this->warningLogs('Debe Crear el tipo asientos acomp ',__CLASS__,__FUNCTION__,__LINE__,$typeSeat);
           return ['success'=>false,'message'=>'Debe crear el tipo de asiento ACOMP, porque no existe'];
        endif;
        $typeSeatAux = $this->typeSeatRepository->whereDuoData('CPA');

        if($typeSeatAux->isEmpty()):
            $this->warningLogs("error en la clase: ",__CLASS__,__METHOD__,__LINE__,$typeSeatAux);
          return  ['success'=>false,'message'=>'Debe crear el tipo de asiento CPA, porque no existe'];
        endif;

        $seat=   [
            'date'=>dateShort(),
            'code'=>$typeSeat[0]->abbreviation(),
            'detail'=>$data['detail'] .' '.$data['date'],
            'dateExpiration'=>$data['due_date'],
            'dateBuy'=>$data['date'],
            'bill'=>$typeSeatAux[0]->abbreviation(),
            'type'=>$type,
            'amount'=>$amount,
            'supplier_id'=>$supplier->id,
            'type_seat_id'=>$typeSeat[0]->id,
            'accounting_period_id'=>periodSchool()->id,
            'type_id'=>'',
            'token'=>$token,
            'status'=>'aplicado',
            'success'=>true,
            'user_created' => userSchool()->id
        ];

        if($message =='Descuento en compra '):
            $seat['type_id']= $typeId;
        elseif($message =='Bonificado en compra '):
            $seat['type_id']= $typeId;
        else:
            $credito    = $this->typeFormRepository->nameAllDataType('credito');
            $seat['type_id']= $credito->id;
        endif;

        return $seat;

    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-04
    |@Date Update: 2016-00-00
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
    public function productinvoice($data, $invoice)
    {
        for($i=0; $i<count($data['amounts']); $i++):

            if(!empty($data['products'][$i])):
                $product = $this->rawMaterialRepository->token($data['products'][$i]);

                $datas[]= [
                    'invoice_id'=>$invoice,
                    'raw_product_id'=>$product->id,
                    'amount'=>$data['amounts'][$i],
                    'price'=>$data['cost'][$i],
                    'discount'=>$data['discount'][$i]
                ];
            endif;
        endfor;

        return $datas;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-03
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Creamos el array que insertaremos en la tabla de
    |   ingresos de inventario al registro de una compra
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function inventorieIncomes($dataIncome,$invoice)
    {

        return [
            'supplier_id'=> $this->supplierRepository->token($dataIncome['supplier'])->id,
            'invoice_id'=>$invoice,
            'school_id'=>$dataIncome['school_id'],
            'reference'=>$dataIncome['invoice'],
            'balance'=>$dataIncome['total'],
            'token'=>Crypt::encrypt($dataIncome['invoice']),
            'user_id'=>currentUser()->id,
        ];
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-03
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Creamos el array para insertar los datos de la factura
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function invoices($dataIncome)
    {
        return [
            'date'=>dateShort(),
            'due_date'=>$dataIncome['date'],
            'numeration'=>$this->reference(),
            'invoices_type_id'=>1,
            'payment_method_id'=>$dataIncome['method'],
            'school_id'=>$dataIncome['school_id'],
            'supplier'=>$dataIncome['supplier'],
            'user_id'=>userSchool()->id,
            'tax'=>$dataIncome['iva'],
            'subtotal'=>$dataIncome['subtotal'],
            'subtotal_taxed'=>$dataIncome['subtotalGravado'],
            'subtotal_exempt'=>$dataIncome['subtotalExcento'],
            'discount'=>$dataIncome['discountTotal'],
            'token'=>$dataIncome['token'],
            'total'=>$dataIncome['total']
        ];

    }


}