<?php

namespace AccountHon\Http\Controllers\Restaurant;


use AccountHon\Entities\General\ProcessedProduct;

use AccountHon\Entities\Restaurant\Invoice;
use AccountHon\Entities\Restaurant\MenuRestaurant;
use AccountHon\Entities\Restaurant\MenuRestaurantCookedProduct;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\CurrencyRepository;
use AccountHon\Repositories\General\InventoryRepository;
use AccountHon\Repositories\General\PaymentSupplierRepository;
use AccountHon\Repositories\Restaurant\ClosingCashDeskRepository;
use AccountHon\Repositories\Restaurant\CookedProductInvoiceRepository;
use AccountHon\Repositories\Restaurant\CurrencyByClosingRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\Restaurant\MenuRestaurantRepository;
use AccountHon\Repositories\Restaurant\ModifyOrderSalonRepository;
use AccountHon\Repositories\Restaurant\OrderSalonRepository;
use AccountHon\Repositories\Restaurant\PaymentMethodRepository;
use AccountHon\Repositories\Restaurant\RecipeRepository;
use AccountHon\Repositories\Restaurant\SupplierRepository;
use AccountHon\Repositories\Restaurant\TableSalonRepository;
use AccountHon\Repositories\UsersRepository;
use AccountHon\Traits\Convert;
use Auth;
use Carbon\Carbon;
use Codedge\Fpdf\Facades\Fpdf;
use Crypt;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Log;
use Session;

class CashController extends Controller
{
    use Convert;
    /**
     * @var BrandRepository
     */
    private $currencieRepository;
    private $invoiceRepository;
    private $closingCashDeskRepository;
    private $currencyByClosingRepository;
    private $usersRepository;
    private $tableSalonRepository;
    private $orderSalonRepository;
    private $cookedProductInvoiceRepository;
    private $paymentMethodRepository;
    private $modifyOrderSalonRepository;
    /**
     * @var InventoryRepository
     */
    private $inventoryRepository;
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var PaymentSupplierRepository
     */
    private $paymentSupplierRepository;
    /**
     * @var MenuRestaurantRepository
     */
    private $menuRestaurantRepository;

    /**
     * BrandController constructor.
     * @param CurrencyRepository $currencieRepository
     * @param InvoiceRepository $invoiceRepository
     * @param ClosingCashDeskRepository $closingCashDeskRepository
     * @param CurrencyByClosingRepository $currencyByClosingRepository
     * @param UsersRepository $usersRepository
     * @param TableSalonRepository $tableSalonRepository
     * @param OrderSalonRepository $orderSalonRepository
     * @param CookedProductInvoiceRepository $cookedProductInvoiceRepository
     * @param PaymentMethodRepository $paymentMethodRepository
     * @param ModifyOrderSalonRepository $modifyOrderSalonRepository
     * @param InventoryRepository $inventoryRepository
     * @param RecipeRepository $recipeRepository
     * @param SupplierRepository $supplierRepository
     * @param PaymentSupplierRepository $paymentSupplierRepository
     * @param MenuRestaurantRepository $menuRestaurantRepository
     * @internal param BrandRepository $brandRepository
     */
    public function __construct(
        CurrencyRepository $currencieRepository,
        InvoiceRepository $invoiceRepository,
        ClosingCashDeskRepository $closingCashDeskRepository,
        CurrencyByClosingRepository $currencyByClosingRepository,
        UsersRepository $usersRepository,
        TableSalonRepository $tableSalonRepository,
        OrderSalonRepository $orderSalonRepository,
        CookedProductInvoiceRepository $cookedProductInvoiceRepository,
        PaymentMethodRepository $paymentMethodRepository,
        ModifyOrderSalonRepository $modifyOrderSalonRepository,
        InventoryRepository $inventoryRepository,
        RecipeRepository $recipeRepository,
        SupplierRepository $supplierRepository,
        PaymentSupplierRepository $paymentSupplierRepository,
        MenuRestaurantRepository $menuRestaurantRepository
    )
    {
        $this->currencieRepository = $currencieRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->closingCashDeskRepository = $closingCashDeskRepository;
        $this->currencyByClosingRepository = $currencyByClosingRepository;
        $this->usersRepository = $usersRepository;
        $this->tableSalonRepository = $tableSalonRepository;
        $this->orderSalonRepository = $orderSalonRepository;
        $this->cookedProductInvoiceRepository = $cookedProductInvoiceRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->modifyOrderSalonRepository = $modifyOrderSalonRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->recipeRepository = $recipeRepository;
        $this->supplierRepository = $supplierRepository;
        $this->paymentSupplierRepository = $paymentSupplierRepository;
        $this->menuRestaurantRepository = $menuRestaurantRepository;
    }

    public function closed()
    {
        $currencies = $this->currencieRepository->all();
        $paymentMethods = $this->paymentMethodRepository->where('type', 'sale');
        return view('restaurant.cash.court', compact('currencies', 'paymentMethods'));
    }

    public function search(Request $request)
    {


        $invoices = $this->invoiceRepository->invoiceCourt();
        $totalPayment = [];
        $type='';
        if($invoices->count()){
            if(userSchool()->regime_type=='tradicional'):
                $type=1;
            // Sale
            $sale = $this->invoiceRepository->invoiceCourtSum('subtotal');
            // IVA
            $iva = $this->invoiceRepository->invoiceCourtSum('tax');
            else:
                $type=0;
                // Sale
                $sale = $this->invoiceRepository->invoiceCourtSum('subtotal') +
                    $this->invoiceRepository->invoiceCourtSum('tax');
                $iva = 0;
            endif;
            // Service
            $service = ($this->invoiceRepository->invoiceCourtSum('service'));
            // Total Sales
            $total_sales = ($this->invoiceRepository->invoiceCourtSum('total'));
            // Monto pagado a Proveedores
            $payment_supplier = ($this->paymentSupplierRepository->invoiceCourtSum('amount'));
            if($payment_supplier == 0 || $payment_supplier ==""):
                $payment_supplier =0;
            endif;

            $totalOut = $total_sales - $payment_supplier;
            $totalSales = $this->multipleOfFive($total_sales);
            $total_sales = $this->multipleOfFive($totalOut);


            // Total paymentMethods
            $paymentMethods = $this->paymentMethodRepository->where('type', 'sale');

            foreach ($paymentMethods as $key => $value) {
                $totalPayment[$value->id] = $this->invoiceRepository->invoiceCourtSumPayment($value->id);
            }

            // Missing
            $missing = $this->invoiceRepository->invoiceCourtSum('missing');

            return ['sale' => ($sale),
                    'iva' => ($iva),
                    'type' => ($type),
                    'service' => ($service),
                    'total_sales' => ($total_sales),
                    'totalSales' => ($totalSales),
                    'totalOut' => ($totalOut),
                    'payment_supplier' => ($payment_supplier),
                    'paymentMethods' => $totalPayment,
                    'missing' => ($missing)
                    ];
        }
        return ['sale' => (0),
                'iva' => (0),
                'type' => ($type),
                'service' => (0),
                'payment_supplier' => (0),
                'totalSales' => (0),
                'totalOut' => (0),
                'total_sales' => (0),
                'paymentMethods' => $totalPayment,
                'missing' => (0)
                ];
    }

    public function court(Request $request)
    {
        $data       = $this->convertionObjeto($request->data);
        $date_ini   = $data->date_ini;
        $date_end   = $data->date_end;
        $currencies = $data->currencies;
        \Log::info(json_encode($data));
        \Log::info($date_ini);
        \Log::info($date_end);
        \Log::info(json_encode($currencies));
        if(userSchool()->regime_type=='tradicional'):
        // Sale
        $sale = ($this->invoiceRepository->invoiceCourtSum( 'subtotal'));
        // IVA
        $iva = ($this->invoiceRepository->invoiceCourtSum( 'tax'));
        else:
            $sale = ($this->invoiceRepository->invoiceCourtSum( 'subtotal') +
                ($this->invoiceRepository->invoiceCourtSum( 'tax')));
            // IVA
            $iva = 0;

        endif;
        // Service
        $service = ($this->invoiceRepository->invoiceCourtSum( 'service'));

        // Total Sales
        $total_sales = ($this->invoiceRepository->invoiceCourtSum( 'total'));
        // Monto pagado a Proveedores
        $payment_supplier = ($this->paymentSupplierRepository->invoiceCourtSum( 'amount'));
        if($payment_supplier == 0 || $payment_supplier ==""):
            $payment_supplier =0;
        endif;
        $totalOut = $total_sales - $payment_supplier;
        $totalSales = $this->multipleOfFive($total_sales);
        $total_sales = $this->multipleOfFive($totalOut);

        if( $data->total_sales == 0){
            return $this->errores("Los montos deben ser mayores a 0.");
        }

        if($total_sales ==  $data->total_sales){
            // Create Closing Cash Desk
            DB::beginTransaction();
            $closingCash = $this->closingCashDeskRepository->getModel();
            $dataClosing = [];
            $dataClosing['payment_supplier'] = $payment_supplier;
            $dataClosing['taxed_sales']      = $sale;
            $dataClosing['tax_sales']        = $iva;
            $dataClosing['service_sales']    = $service;
            $dataClosing['total_sales']      = $total_sales;
            $dataClosing['cash_desk_id']     = 1; // Caja por default
            $dataClosing['user_id']          = Auth::user()->id;
            $dataClosing['validate_user_id'] = Session::get('validateUser');

            if(isset($data->missing)){
                $dataClosing['missing'] = $data->missing;
            }else{
                $dataClosing['surplus'] = $data->surplus;
            }

            $closingCash->fill($dataClosing);
            $closingCash->save();

            // Create Currency by Closing
            foreach ($currencies as $currencie) {
                $currencyByClosing = $this->currencyByClosingRepository->getModel();
                $dataCurrency = [];
                $dataCurrency['amount']               = $currencie->amount;
                $dataCurrency['currency_id']          = $currencie->id;
                $dataCurrency['closing_cash_desk_id'] = $closingCash->id;

                $currencyByClosing->fill($dataCurrency);
                $currencyByClosing->save();
            }

            // Update invoices
            $this->invoiceRepository->invoiceCourtCase($closingCash->id);
            $invoices = $this->invoiceRepository->getModel()->where('closed_cash_desk_id',$closingCash->id)->get();
            // Update Payment Supplier
            $this->paymentSupplierRepository->invoiceCourtCase();

            DB::commit();
            return view('restaurant.cash.print', compact('closingCash','invoices','payment_supplier', 'totalSales','sale','iva','service','total_sales','currencies'));
        }
        return $this->errores("Los montos totales no coinciden.");
    }

    public function cashInvoiceSplit(Request $request){
        $data  = $this->convertionObjeto($request->data);

        $orders           = $data->orders;
        $clients_pay      = $data->clients_pay;
        $clients_selected = $data->clients_selected;
        $password         = $data->password;
        $table            = $this->tableSalonRepository->token($data->table);
        $tc               = $this->currencieRepository->find(10)->value; // Currience dolares
        $user_auth        = null;
        $invoices_print   = array();
        // Validate table
        if(!$table){
            return $this->errores('La mesa seleccionada no existe.');
        }

        // Orders
        $sum_orders_orig = $this->orderSalonRepository->orders_not_applied_sum_qty($table);
        $sum_orders = 0;
        // Validate qty
        foreach ($orders as $key => $order)
        {
            $sum_orders += $order->total;
        }
        if($sum_orders != $sum_orders_orig)
        {
            return $this->errores('La cantidad total de la orden modificada no cuadra con la orden original.');
        }

        DB::beginTransaction();

        for($i = 0; $i < count($orders); $i++)
        {
            // Validate PaymentMethods
            foreach ($clients_pay as $client_pay) {
                $paymentMethod = $this->paymentMethodRepository->getModel()
                                 ->where('type', 'sale')
                                 ->where('id', $client_pay->paymentMethod)
                                 ->first();
                if(!$paymentMethod){
                    DB::rollback();
                    Log::info('Error método de pago no existe, class '.__class__.', function '.__function__.' Datos no válido:'.$client_pay->paymentMethod);
                    return $this->errores('El método de pago no existe.');
                }

                // Validate Discount
                $discount = $client_pay->discount;
                if($discount > 0){
                    if($discount > 100){
                        DB::rollback();
                        return $this->errores('El descuento debe ser menor o igual a 100.');
                    }
                    if(strlen($password) == 0){
                        DB::rollback();
                        return $this->errores('Debe ingresar la contraseña para el descuento.');
                    }
                    $validate = $this->validateUserCash($password);
                    if(is_numeric($validate)){
                        $user_auth = $validate;
                    }else{
                        DB::rollback();
                        return $this->errores($validate);
                    }
                }
            }

            // Update orders
            if(isset($orders[$i]->split)){
                // Create new order split
                $orderSalon     = $this->orderSalonRepository->getModel();
                $dataOrderSalon = $orders[$i];

                $dataOrderSalon->table_salon_id = $table->id;
                $dataOrderSalon->qty            = $dataOrderSalon->total;
                $dataOrderSalon->split_user_id  = $clients_selected[$i];
                if($orderSalon->isValid((array) $dataOrderSalon))
                {
                    $orderSalon->fill((array) $dataOrderSalon);
                    $orderSalon->save();
                }else{
                    DB::rollback();
                    Log::info('Error al momento de partir las facturas, class '.__class__.', function '.__function__.' Datos no válidos:'.json_encode($orderSalon->errors));
                    return $this->errores("Sucedió un error inesperado, por favor comuníquese con el administrador.");
                }
            }else{
                // Update order
                $order                = $this->orderSalonRepository->token($orders[$i]->token);
                $order->qty           = $orders[$i]->total;
                $order->split_user_id = $clients_selected[$i];
                $order->save();
            }
        }

        // Each clients for invoice
        foreach ($clients_pay as $key => $client) {
            $orders_client = $this->orderSalonRepository->newQuery()
                             ->where('status', 'no aplicado')
                             ->where('split_user_id', $client->name)
                             ->where('table_salon_id', $table->id)
                             ->get();
            $total = 0;
            $arr_cooked_product_invoice = array();
            $orders_update = array();

            foreach ($orders_client as $keyOrder => $order)
            {
                array_push($orders_update, $order->id);
                if($order->menuRestaurant->money == 'dolares'){
                    $total += $order->menuRestaurant->costo * $tc * $order->qty;
                }else{
                    $total += $order->menuRestaurant->costo * $order->qty;
                }

                // Menu Edit
                if($order->modify == 1)
                {
                    $products_modify_base = $this->modifyOrderSalonRepository->whereDuoList('order_salon_id', $order->id, 'type', 'Base' ,'cooked_product_id');

                    $products_modify_aditional = $this->modifyOrderSalonRepository->whereDuoList('order_salon_id', $order->id, 'type', 'Adicional' ,'cooked_product_id');

                    // new array
                    $cooked_products = $order->menuRestaurant->cookedProductBaseNotIn('cooked_product_id', $products_modify_base);

                    for($j = 0; $j < $order->qty; $j++)
                    {
                        foreach($cooked_products as $cookedProduct)
                        {
                            $data = array();
                            $data['cooked_product_id']  = $cookedProduct->id;
                            $data['menu_restaurant_id'] = $order->menu_restaurant_id;
                            array_push($arr_cooked_product_invoice, $data);
                        }

                        foreach($products_modify_aditional as $cookedProductAditional)
                        {
                            $data = array();
                            $data['cooked_product_id']  = $cookedProductAditional;
                            $data['menu_restaurant_id'] = $order->menu_restaurant_id;
                            array_push($arr_cooked_product_invoice, $data);
                        }
                    }
                // Menu
                }else{
                    // Prepared Cooked Product
                    for($k = 0; $k < $order->qty; $k++)
                    {
                        foreach($order->menuRestaurant->cookedProduct as $cookedProduct)
                        {
                            $data = array();
                            $data['cooked_product_id']  = $cookedProduct->id;
                            $data['menu_restaurant_id'] = $order->menu_restaurant_id;
                            array_push($arr_cooked_product_invoice, $data);
                        }
                    }
                }
            }

            // Validate total
            $dataTotal = $this->dataTotal((array)$client, $total, $tc, $table, $user_auth);

            $dataInvoice = array();

            if(is_array($dataTotal)){
                foreach ($dataTotal as $key => $value) {
                    $dataInvoice[$key] = $value;
                }
            }else{
                DB::rollback();
                return $this->errores($dataTotal);
            }

            // Create Invoice
            $invoice = new Invoice;

            $dataInvoice['date']              = Carbon::now()->toDateString();
            $dataInvoice['numeration']        = $invoice->next_numeration_sale();
            $dataInvoice['invoices_type_id']  = 2; // Sale
            $dataInvoice['payment_method_id'] = $client->paymentMethod;
            $dataInvoice['school_id']         = userSchool()->id;
            $dataInvoice['user_id']           = Auth::user()->id;
            $dataInvoice['client']            = $client->name;
            $dataInvoice['table_salon_id']    = $table->id;
            $dataInvoice['token']             = Crypt::encrypt($dataInvoice['numeration']);

            if($invoice->isValid($dataInvoice))
            {
                $invoice->fill($dataInvoice);
                $invoice->save();
                array_push($invoices_print, $invoice);
            }

            // Update order
            $this->orderSalonRepository->getModel()
                 //->where('table_salon_id', $table->id)
                 //->where('status', 'no aplicado')
                 ->whereIn('id', $orders_update)
                 ->update(array('status' => 'aplicado', 'invoice_id' => $invoice->id));

            foreach ($arr_cooked_product_invoice as $key => $product_invoice)
            {
                // Create Cooked Products Invoice
                $cookedProductInvoice = $this->cookedProductInvoiceRepository->getModel();

                $dataProductInvoice = array();
                foreach ($product_invoice as $key => $product)
                {
                    switch ($key) {
                        case 'cooked_product_id':
                            $dataProductInvoice['cooked_product_id'] = $product;
                            break;
                        case 'menu_restaurant_id':
                            $dataProductInvoice['menu_restaurant_id'] = $product;
                            break;
                    }
                }
                $dataProductInvoice['invoice_id'] = $invoice->id;
                $dataProductInvoice['amount'] = 1;

                if($cookedProductInvoice->isValid($dataProductInvoice))
                {
                    $cookedProductInvoice->fill($dataProductInvoice);
                    $cookedProductInvoice->save();
                    $this->updateInventory($cookedProductInvoice->cooked_product_id);
                }
            }
        }
        DB::commit();

        $view = view('restaurant.salon.print-invoice-split', compact('invoices_print', 'table', 'tc'));
        return $view;
    }

    public function cashInvoice(Request $request)
    {

        if($request->is_express):
            $table = $this->tableSalonRepository->express();
        else:
            $tableV         = $this->tableSalonRepository->token($request->table);
            if($tableV->restaurant == 'si'):
                $this->tableSalonRepository->changeBase($tableV->id);
                $table = $this->tableSalonRepository->base();
            else:
                $table = $this->tableSalonRepository->token($request->table);
            endif;
        endif;
        $client        = $request->client;
        $card        = $request->card;
        $email        = $request->email;
        $fe = false;
        if($request->fe == 'on'){
            $fe        = true;
        }
        $orders        = $this->orderSalonRepository->orders_not_applied($table);
        $tc            = $this->currencieRepository->find(10)->value; // Currience dolares
        $paymentMethod = $this->paymentMethodRepository->getModel()
                         ->where('type', 'sale')
                         ->where('id', $request->paymentMethod)
                         ->first();

        // Validate table
        if(!$table){
            return $this->errores('La mesa seleccionada no existe.');
        }

        if(!$paymentMethod){
            return $this->errores('El método de pago no existe.');
        }

        $user_auth = null;
        // Discount
        $discount = $request->discount;
        if($discount > 0){
            if($discount > 100){
                return $this->errores('El descuento debe ser menor o igual a 100.');
            }
            if(strlen($request->pass) == 0){
                return $this->errores('Debe ingresar la contraseña para el descuento.');
            }
            $validate = $this->validateUserCash($request->pass);
            if(is_numeric($validate)){
                $user_auth = $validate;
            }else{
                return $this->errores($validate);
            }
        }

        // Verify Orders
        if( $orders->count() )
        {
            $total = 0;
            $arr_cooked_product_invoice = array();
            foreach ($orders as $key => $order)
            {
                if($order->menuRestaurant->money == 'dolares'){
                    $total += $order->menuRestaurant->costo * $tc * $order->qty;
                }else{
                    $total += $order->menuRestaurant->costo * $order->qty;
                }
                // Menu Edit
                if($order->modify == 1)
                {
                    $products_modify_base = $this->modifyOrderSalonRepository->whereDuoList('order_salon_id', $order->id, 'type', 'Base' ,'cooked_product_id');

                    $products_modify_aditional = $this->modifyOrderSalonRepository->whereDuoList('order_salon_id', $order->id, 'type', 'Adicional' ,'cooked_product_id');

                    // new array
                    $cooked_products = $order->menuRestaurant->cookedProductBaseNotIn('cooked_product_id', $products_modify_base);

                    for($i = 0; $i < $order->qty; $i++)
                    {
                        foreach($cooked_products as $cookedProduct)
                        {
                            $data = array();
                            $data['cooked_product_id']  = $cookedProduct->id;
                            $data['menu_restaurant_id'] = $order->menu_restaurant_id;
                            array_push($arr_cooked_product_invoice, $data);
                        }

                        foreach($products_modify_aditional as $cookedProductAditional)
                        {
                            $data = array();
                            $data['cooked_product_id']  = $cookedProductAditional;
                            $data['menu_restaurant_id'] = $order->menu_restaurant_id;
                            array_push($arr_cooked_product_invoice, $data);
                        }
                    }
                // Menu
                }else{
                    // Prepared Cooked Product
                    for($i = 0; $i < $order->qty; $i++)
                    {
                        foreach($order->menuRestaurant->cookedProduct as $cookedProduct)
                        {
                            $data = array();
                            $data['cooked_product_id']  = $cookedProduct->id;
                            $data['menu_restaurant_id'] = $order->menu_restaurant_id;
                            array_push($arr_cooked_product_invoice, $data);
                        }
                    }
                }
            }

            // Validate total
            $dataTotal = $this->dataTotal($request->all(), $total, $tc, $table, $user_auth);
            $dataInvoice = array();
            if(is_array($dataTotal)){
                foreach ($dataTotal as $key => $value) {
                    $dataInvoice[$key] = $value;
                }
            }else{
                return $this->errores($dataTotal);
            }

            DB::beginTransaction();

            // Create Invoice
            $invoice = $this->invoiceRepository->getModel();
            $dataInvoice['date']              = Carbon::now()->toDateString();
            $dataInvoice['numeration']        = $invoice->next_numeration_sale();
            $dataInvoice['invoices_type_id']  = 2; // Sale
            $dataInvoice['payment_method_id'] = $paymentMethod->id;
            $dataInvoice['school_id']         = userSchool()->id;
            $dataInvoice['user_id']           = Auth::user()->id;
            $dataInvoice['client']            = $client;
            $dataInvoice['card']            = $card;
            $dataInvoice['emails']            = $email;
            $dataInvoice['fe']            = $fe;
            $dataInvoice['table_salon_id']    = $table->id;
            $dataInvoice['token']             = Crypt::encrypt($dataInvoice['numeration']);
            //dd($dataInvoice);
            if($invoice->isValid($dataInvoice))
            {
                $invoice->fill($dataInvoice);
                $invoice->save();
            }

            // Update order
            $this->orderSalonRepository->getModel()
                 ->where('table_salon_id', $table->id)
                 ->where('status', 'no aplicado')
                 ->update(array('status' => 'aplicado', 'invoice_id' => $invoice->id));

            foreach ($arr_cooked_product_invoice as $key => $product_invoice)
            {
                // Create Cooked Products Invoice
                $cookedProductInvoice = $this->cookedProductInvoiceRepository->getModel();

                $dataProductInvoice = array();
                foreach ($product_invoice as $key => $product)
                {
                    switch ($key) {
                        case 'cooked_product_id':
                            $dataProductInvoice['cooked_product_id'] = $product;
                            break;
                        case 'menu_restaurant_id':
                            $dataProductInvoice['menu_restaurant_id'] = $product;
                            break;
                    }
                }
                $dataProductInvoice['invoice_id'] = $invoice->id;
                $dataProductInvoice['amount'] = 1;

                if($cookedProductInvoice->isValid($dataProductInvoice))
                {
                    $cookedProductInvoice->fill($dataProductInvoice);
                    $cookedProductInvoice->save();
                    $this->updateInventory($cookedProductInvoice->cooked_product_id);
                }
            }

            DB::commit();

            $lists =array();
            $cantidad = 0;
            $menuRestaurants = $this->menuRestaurantRepository->getModel()->get();
            foreach ($menuRestaurants AS $menuRestaurant):
                $order = $this->orderSalonRepository
                    ->orders_applied_group($invoice,$menuRestaurant->id,$table);
                if($order):

                    $cantidad = $order;
                    $lists[] = ['menu'=>$menuRestaurant->name,
                        'costo' => $menuRestaurant->costo,
                        'money' => $menuRestaurant->money,
                        'cantidad'=> $cantidad];
                endif;
            endforeach;
            if($request->is_express):
                $table = $this->tableSalonRepository->express();
            else:
                if($tableV->restaurant == 'si'):
                    $this->tableSalonRepository->tokenTableSoftDelete($tableV->token,'si')->delete();
                endif;
            endif;
            $view = view('restaurant.salon.print-invoice', compact('invoice', 'table', 'tc','lists'));

            return $view;
        }else{
            return $this->errores('No se tiene ordenes pendientes para facturar.');
        }
    }

    private function dataTotal($args, $total, $tc, $table, $user_auth){
        $method = $args['paymentMethod'];
        $dataInvoice = array();

        $dataInvoice['cash'] = true;
        $discount = $args['discount'] ;
        if($discount > 0){
            $dataInvoice['subtotal']         = $total;
            $dataInvoice['discount']         = $total * $discount / 100 ;
            $dataInvoice['percent_discount'] = $discount;
            $total                           = $dataInvoice['subtotal'] - $dataInvoice['discount'];
            $dataInvoice['tax']              = $total * iva();
            if(!$table->barra ){
                //verificamos en que regimen esta para calcular el 10%
                if(userSchool()->regime_type == 'tradicional'):
                    $dataInvoice['service'] = $total * isv();
                else:
                    $dataInvoice['service'] = taxAdd($total) * isv();
                endif;
            }else{
                $dataInvoice['service'] = 0;
        }


            $dataInvoice['total'] = $dataInvoice['subtotal'] + $dataInvoice['tax'] + $dataInvoice['service'] - $dataInvoice['discount'];
            $dataInvoice['percent_discount']= number_format($dataInvoice['percent_discount'],0,'.','');
            $dataInvoice['tax']=number_format($dataInvoice['tax'],0,'.','');

            $dataInvoice['discount'] =number_format($dataInvoice['discount'] ,0,'.','');
            $dataInvoice['subtotal'] = number_format($dataInvoice['subtotal'] ,0,'.','');
            $dataInvoice['total'] = $this->multipleOfFive($dataInvoice['total']);

            $dataInvoice['user_auth_id'] = $user_auth;
        }else{
            $dataInvoice['subtotal'] = $total;
            $dataInvoice['tax']      = $total * iva();
            if(!$table->barra ){

                    //verificamos en que regimen esta para calcular el 10%
                    if(userSchool()->regime_type == 'tradicional'):
                        $dataInvoice['service'] = $total * isv();
                    else:
                        $dataInvoice['service'] = taxAdd($total) * isv();
                    endif;

            }else{
                $dataInvoice['service'] = 0;
            }
            $dataInvoice['tax'] = number_format($dataInvoice['tax'] ,0,'.','');
            $dataInvoice['service'] = number_format($dataInvoice['service'] ,0,'.','');
            $dataInvoice['subtotal'] = number_format($dataInvoice['subtotal'] ,0,'.','');
            $dataInvoice['total'] = $dataInvoice['subtotal'] + $dataInvoice['tax'] + $dataInvoice['service'];
            $dataInvoice['total'] = $this->multipleOfFive($dataInvoice['total']);
        }
        $dataInvoice['tc'] = $tc;
        $dataInvoice['total'] = $this->multipleOfFive($dataInvoice['total']);
        Log::info('is_express'.$args['pay']);
        if ($args['discount'] == '') $args['discount'] = 0;
        if($args['pass'] == '') $args['pass'] = 0;
        if($args['dues'] == '') $args['dues'] = 0;
        if($args['pay_t'] == '') $args['pay_t'] = 0;
        if($args['pay'] == '') $args['pay'] = 0;
        if($args['usd'] == '') $args['usd'] = 0;
        if($args['missing'] == '') $args['missing'] = 0;
        switch ($args['paymentMethod']) {
            // Colones
            case 3:
                if($args['pay'] + $args['missing'] >= $dataInvoice['total']){
                    $dataInvoice['colones'] = $args['pay'];
                    if($args['pay'] < $dataInvoice['total']){
                        $dataInvoice['missing'] = $dataInvoice['total'] - $args['pay'];
                    }
                    return $dataInvoice;
                }
                break;
            // Dólares
            case 4:
                if($args['usd'] * $tc + $args['missing'] * $tc >= $dataInvoice['total']){
                    $dataInvoice['dolares'] = $args['usd'];
                    if($args['usd'] * $tc < $dataInvoice['total']){
                        $dataInvoice['missing'] = $dataInvoice['total'] - $args['usd'] * $tc;
                    }
                    return $dataInvoice;
                }
                break;
            // Tarjeta
            case 5:
                if($args['pay_t'] + $args['missing'] >= $dataInvoice['total']){
                    $dataInvoice['colones_t'] = $args['pay_t'];
                    //$dataInvoice['dues'] = intval($args['dues']);
                    if($args['pay_t'] < $dataInvoice['total']){
                        $dataInvoice['missing'] = $dataInvoice['total'] - $args['pay_t'];
                    }
                    return $dataInvoice;
                }
                break;
            // Colones y Dólares
            case 7:
                if($args['pay'] + $args['usd'] * $tc + $args['missing'] >= $dataInvoice['total']){
                    $dataInvoice['colones'] = $args['pay'];
                    $dataInvoice['dolares'] = $args['usd'];
                    if($args['pay'] + $args['usd'] * $tc < $dataInvoice['total']){
                        $dataInvoice['missing'] = $dataInvoice['total'] - ($args['pay'] + $args['usd'] * $tc);
                    }
                    return $dataInvoice;
                }
                break;
            // Tarjeta y Colones
            case 8:
                if($args['pay_t'] + $args['pay'] + $args['missing'] >= $dataInvoice['total']){
                    $dataInvoice['colones'] = $args['pay'];
                    //$dataInvoice['dues'] = intval($args['dues']);
                    $dataInvoice['colones_t'] = $args['pay_t'];
                    if($args['pay'] + $args['pay_t'] < $dataInvoice['total']){
                        $dataInvoice['missing'] = $dataInvoice['total'] - ($args['pay_t'] + $args['pay']);
                    }
                    return $dataInvoice;
                }
                break;
            // Tarjeta y Dólares
            case 9:
                if($args['pay_t'] + $args['usd'] * $tc + $args['missing'] >= $dataInvoice['total']){
                    $dataInvoice['dolares'] = $args['usd'];
                    //$dataInvoice['dues'] = intval($args['dues']);
                    $dataInvoice['colones_t'] = $args['pay_t'];
                    if($args['pay_t'] + $args['usd'] * $tc < $dataInvoice['total']){
                        $dataInvoice['missing'] = $dataInvoice['total'] - ($args['pay_t'] + $args['usd'] * $tc);
                    }
                    return $dataInvoice;
                }
                break;
        }
        return "Debe verificar el monto de la factura";
    }

    private function validateUserCash($password){
        $validate = $this->usersRepository->validateOtherUser($password, Auth::user()->id);
        if($validate['success']){
            return $validate['validateUser'];
        }else{
            return $validate['msg'];
        }
    }

    public function validateUser(Request $request){
        $validate = $this->usersRepository->validateOtherUser($request->password, Auth::user()->id);
        if($validate['success']){
            Session::put('validateUser', $validate['validateUser']);
            return $this->exito("Usuario válido");
        }else{
            Session::forget('validateUser');
            return $this->errores($validate['msg']);
        }
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-11-03 8:22   @Update 0000-00-00
     ***************************************************
     * @Description: Con esta Funcion actualizaremos el
     * inventario de cada uno d elos productos crudos.
     *
     *
     * @Pasos:
     *
     *
     * @param $id
     */
    public function updateInventory($id)
    {
        $recipes =  $this->recipeRepository->getModel()->where('cooked_product_id',$id);
        if($recipes->count() > 0):
            $recipes = $recipes->get();
        Log::info('receta de: '.$id.' receta: '.json_encode($recipes));
        foreach ($recipes AS $recipe):
            $this->inventoryRepository->decrease($recipe->rawProduct_id,$recipe->amount);
        endforeach;
        endif;
    }

    public function paymentSuppliers()
    {
        $suppliers = $this->supplierRepository->allFilterScholl();
        $paymentSuppliers = $this->paymentSupplierRepository->getModel()->whereHas('supplier',function ($q){
            $q->where('school_id',userSchool()->id);
        })->get();
        return view('restaurant.cash.paymentSuppliers',compact('suppliers','paymentSuppliers'));
    }


    /**
     * @return mixed
     */
    public function savePaymentSuppliers()
    {
        $paymentSupplierObject = $this->convertionObjeto();
        $paymentSupplierArray =  $this->CreacionArray($paymentSupplierObject, 'PaymentSuppler');
        $paymentSupplierArray['date'] = Carbon::now()->format('Y-m-d');
        $paymentSupplierArray['supplier_id'] = $this->supplierRepository->token($paymentSupplierArray['supplier'])->id;

        $paymentSupplier = $this->paymentSupplierRepository->getModel();

        if($paymentSupplier->isValid($paymentSupplierArray)):
            $paymentSupplier->fill($paymentSupplierArray);
            $paymentSupplier->save();
            return $this->exito('se guardo con Exito la factura');
        endif;

        return $this->errores($paymentSupplier->errors);
    }

    public function closedCashIndex()
    {
        $ClosedCash = $this->closingCashDeskRepository->getModel()
            ->whereHas('cashDesk' ,function ($q){
                $q->where('school_id',userSchool()->id);
            })
            ->orderBy('created_at','DESC')->get();

        return view('restaurant.cash.ClosedCash',compact('ClosedCash'));
    }


    public function ReportClosedCash()
    {
        $data = Input::all();

        $pdf  = Fpdf::AddPage();
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,7,utf8_decode( userSchool()->name),0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::Cell(0,7,'Cedula: '.userSchool()->charter,0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,7,'RESUMEN CIERRES DE CAJAS',0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);

        $pdf .= Fpdf::Cell(0,7,utf8_decode('Informe para la declaración D-104 (Tradicional)'),0,1,'C');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::Cell(0,7,utf8_decode('Fecha Informe: '.$data['dateI'].' A '.$data['dateF'] ),0,1,'C');

        $pdf .= Fpdf::SetFont('Arial','I',14);
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(60);

        $sale = $this->closingCashDeskRepository->getModel()
            ->whereBetween('created_at',[$data['dateI'],$data['dateF']])->sum('taxed_sales');

        $pdf .= Fpdf::SetX(60);
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Total de Ventas Gravadas: '.
                number_format($sale,2)
            ),0,1,'L');
        $desc = $this->closingCashDeskRepository->getModel()
            ->whereBetween('created_at',[$data['dateI'],$data['dateF']])->sum('discount');
            $pdf .= Fpdf::SetX(60);
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Total de Descuentos: '.
                number_format($desc,2)
            ),0,1,'L');
        $tax = $this->closingCashDeskRepository->getModel()
            ->whereBetween('created_at',[$data['dateI'],$data['dateF']])->sum('tax_sales');
        $pdf .= Fpdf::SetX(60);
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Total de Impuesto: '.
                number_format($tax,2)
            ),0,1,'L');
            $servi = $this->closingCashDeskRepository->getModel()
                ->whereBetween('created_at',[$data['dateI'],$data['dateF']])->sum('service_sales');
            $pdf .= Fpdf::SetX(60);
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Total de Impuesto Servicio: '.
            number_format($servi,2)
        ),0,1,'L');
        $pdf .= Fpdf::SetFont('Arial','B',16);

        $pdf .= Fpdf::Cell(0,7,utf8_decode('Total de Ventas: '.
            number_format($sale+$tax+$servi-$desc)
        ),0,1,'C');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','I',14);
        $supplier = $this->closingCashDeskRepository->getModel()
            ->whereBetween('created_at',[$data['dateI'],$data['dateF']])->sum('payment_supplier');
        $pdf .= Fpdf::SetX(60);
        $pdf .= Fpdf::Cell(0,7,utf8_decode('Total Pagos a Proveedores: -'.
            number_format($supplier,2)
        ),0,1,'L');

        $pdf .= Fpdf::SetFont('Arial','B',16);
       $pdf .= Fpdf::Cell(0,7,utf8_decode('Total de Saldo disponible: '.
            number_format(($sale+$tax+$servi)-($desc+$supplier))
        ),0,1,'C');

        Fpdf::Output();
        exit;
    }

    public function cashExpress()
    {
        return view('restaurant.cash.express');
    }

    public function listMenu(Request $request)
    {
        $data = $request->all();
        $filterProduct = $data['filterProduct'];
        $products = [];
        if (strlen($filterProduct) == 0)
        {
            $cooked_products = ProcessedProduct::where('price', '>', 0)->where('school_id', userSchool()->id)->offset(0)->limit(7)->get()->toArray();
            $menu_resturants = MenuRestaurant::where('costo', '>', 0)->where('school_id', userSchool()->id)->offset(0)->limit(8)->get()->toArray();
        }else{
            $cooked_products = ProcessedProduct::where('price', '>', 0)->where('school_id', userSchool()->id)->where('name', 'like', '%' . $filterProduct . '%')->get()->toArray();
            $menu_resturants = MenuRestaurant::where('costo', '>', 0)->where('school_id', userSchool()->id)->where('name', 'like', '%' . $filterProduct . '%')->get()->toArray();
        }
        //$products = array_merge($cooked_products, $menu_resturants);
        return $menu_resturants;
    }

    public function listOrders(Request $request)
    {
        $table = $this->tableSalonRepository->getModel()
                    ->where('restaurant','express')
                    ->where('school_id', userSchool()->id)
                    ->first();
        $orders = $this->orderSalonRepository
            ->getModel()
            ->select('order_salons.*')
            ->where('table_salon_id', $table->id)
            ->where('status', 'no aplicado')
            ->where('canceled', false)
            ->with('menuRestaurant')
            ->with('modifyMenu')
            ->get();
        $paymentMethods = $this->paymentMethodRepository->getModel()->whereIn('name', ['Colones','Tarjeta'])->get();
        return response()->json(['orders' => $orders, 'paymentMethods' => $paymentMethods]);
    }

    public function updateOrder(Request $request, $token)
    {
        $orderSalon = $this->orderSalonRepository->token($token);
        $orderSalon->qty = $request->qty;
        $orderSalon->save();
        return $this->exito($orderSalon);
    }
}
