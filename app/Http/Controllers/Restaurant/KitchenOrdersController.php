<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 30/12/15
 * Time: 02:31 PM
 */

namespace AccountHon\Http\Controllers\Restaurant;


use AccountHon\Entities\Restaurant\RawMaterial;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\Restaurant\RawProductInvoiceRepository;
use AccountHon\Repositories\Restaurant\RawMaterialRepository;
use AccountHon\Repositories\Restaurant\OrderSalonRepository;
use AccountHon\Traits\Convert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class KitchenOrdersController extends Controller
{
    use Convert;
    /**
     * @var RawMaterialRepository
     */
    private $rawMaterialRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;
    /**
     * @var RawProductInvoiceRepository
     */
    private $rawProductInvoiceRepository;

    /**
     * KitchenOrdersController constructor.
     * @param RawMaterialRepository $rawMaterialRepository
     * @param InvoiceRepository $invoiceRepository
     * @param RawProductInvoiceRepository $rawProductInvoiceRepository
     */
    public function __construct(
        RawMaterialRepository $rawMaterialRepository,
        InvoiceRepository $invoiceRepository,
        RawProductInvoiceRepository $rawProductInvoiceRepository,
        OrderSalonRepository $orderSalonRepository
    )
    {

        $this->rawMaterialRepository = $rawMaterialRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->rawProductInvoiceRepository = $rawProductInvoiceRepository;
        $this->orderSalonRepository = $orderSalonRepository;
    }

    public function create()
    {
        $rawProducts = $this->rawMaterialRepository->allFilterScholl();
        $units = ['unidades','gr','cup','tbs','tps','ml','l','lb','kg','oz'];
        return view('restaurant.kitchenOrders.create',compact('rawProducts', 'units'));
    }

    public function store()
    {
        try{

            $data = $this->convertionObjeto();
            $dataKitchen = $this->CreacionArray($data,'KitchenOrders');
            DB::beginTransaction();
            $invoices = $this->invoices();
            $invoice = $this->invoiceRepository->getModel();

            if(!$invoice->isValid($invoices)):
                DB::rollback();
                return $this->errores($invoice->errors);
            endif;

            $invoice->fill($invoices);
            $invoice->save();

            for($i=0; $i<count($dataKitchen['amount']); $i++):

                if($dataKitchen['amount'][$i] > 0):

                    $product =$this->rawMaterialRepository->token($dataKitchen['products'][$i]);

                    $productInvoice = $this->productinvoice($product,$dataKitchen['amount'][$i],$dataKitchen['units'][$i],$invoice->id);
                    /* Productos factura el detalle */

                        $rawProduct = $this->rawProductInvoiceRepository->getModel();
                        $rawProduct->fill($productInvoice);
                        $rawProduct->save();
                endif;
            endfor;
            DB::commit();
            return $this->exito('Se guardaron todos los registros corectamente');
        }catch (Exception $e) {
            Log::error($e);
            return $this->errores(array('inventories Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    public function productinvoice($product,$amount,$units,$invoice)
    {

           return [
                'invoice_id'=>$invoice,
                'raw_product_id'=>$product->id,
                'amount'=>$amount,
                'units'=>$units,
                'price'=>0,
                'discount'=>0
            ];

    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-01-03
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Creamos el array para insertar los datos de la factura
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function invoices()
    {
        return [
            'date'=>dateShort(),
            'due_date'=>'',
            'numeration'=>$this->reference(),
            'invoices_type_id'=>3,
            'payment_method_id'=>6,
            'school_id'=>userSchool()->id,
            'user_id'=>currentUser()->id,
            'token'=>Crypt::encrypt($this->reference()),
            'total'=>0
        ];

    }

    public function orders(){
        $orders = $this->orderSalonRepository->getModel()
                  ->select(DB::raw('SUM(qty) as total'),'menu_restaurant_id', 'user_id', 'table_salon_id' ,'created_at','token')
                  ->where('status','no aplicado')
                  ->where('cooked', false)
                  ->groupBy('menu_restaurant_id')
                  ->with('menuRestaurant')
                  ->with('menuRestaurant.processedProduct')
                  ->with('user')
                  ->with('tableSalon')
                  ->orderBy('id', 'desc')
                  ->get();
                  
        return view('restaurant.kitchenOrders.index', compact('orders'));
    }

    public function cookedOrder(Request $request){
        $data = $this->convertionObjeto();
        // Get order
        $order = $this->orderSalonRepository->getModel()->where('token', $data->token)->first();

        // Update Order
        $this->orderSalonRepository->getModel()
             ->where('menu_restaurant_id', $order->menu_restaurant_id)
             ->where('table_salon_id', $order->table_salon_id)
             ->where('cooked', '0')
             ->update(array('cooked' => true));

        return $this->exito('Se guardaron los cambios correctamente.');
    }  

    private function reference()
    {
        $referencies = 'ORD-0001';
        $inventaries = $this->invoiceRepository->oneWhere('invoices_type_id',3,'numeration');

        if(!$inventaries->isEmpty()):
            $referencies = $inventaries[0]->numeration + 1;
        endif;

        return $referencies;
    }
}