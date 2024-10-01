<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 19/01/16
 * Time: 07:27 PM
 */

namespace AccountHon\Http\Controllers\Restaurant;

use AccountHon\Entities\Restaurant\InvoicesService;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\TableSalonRepository;
use AccountHon\Repositories\Restaurant\GroupMenuRepository;
use AccountHon\Repositories\Restaurant\MenuRestaurantRepository;
use AccountHon\Repositories\Restaurant\OrderSalonRepository;
use AccountHon\Repositories\Restaurant\ModifyOrderSalonRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\Restaurant\CookedProductInvoiceRepository;
use AccountHon\Repositories\General\ProcessedProductRepository;
use AccountHon\Repositories\Restaurant\PaymentMethodRepository;
use AccountHon\Repositories\Restaurant\CurrencyRepository;
use AccountHon\Repositories\UsersRepository;
use AccountHon\Traits\AuthTrait;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use AccountHon\Events\KitchenOrder;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Log;
use AccountHon\Traits\Convert;

class LivingController extends Controller
{
    use Convert;
    use AuthTrait;

    /**
     * LivingController constructor.
     *
     * @param TableSalonRepository $tableSalonRepository
     * @param GroupMenuRepository $groupMenuRepository
     * @param MenuRestaurantRepository $menuRestaurantRepository
     * @param OrderSalonRepository $orderSalonRepository
     * @param ModifyOrderSalonRepository $modifyOrderSalonRepository
     * @param InvoiceRepository $invoiceRepository
     * @param CookedProductInvoiceRepository $cookedProductInvoiceRepository
     * @param ProcessedProductRepository $cookedProductRepository
     * @param PaymentMethodRepository $paymentMethodRepository
     * @param CurrencyRepository $currencyRepository
     * @param UsersRepository $usersRepository
     */
    public function __construct(
        TableSalonRepository $tableSalonRepository,
        GroupMenuRepository $groupMenuRepository,
        MenuRestaurantRepository $menuRestaurantRepository,
        OrderSalonRepository $orderSalonRepository,
        ModifyOrderSalonRepository $modifyOrderSalonRepository,
        InvoiceRepository $invoiceRepository,
        CookedProductInvoiceRepository $cookedProductInvoiceRepository,
        ProcessedProductRepository $cookedProductRepository,
        PaymentMethodRepository $paymentMethodRepository,
        CurrencyRepository $currencyRepository,
        UsersRepository $usersRepository
        ){
		$this->tableSalonRepository = $tableSalonRepository;
        $this->groupMenuRepository = $groupMenuRepository;
        $this->menuRestaurantRepository = $menuRestaurantRepository;
        $this->orderSalonRepository = $orderSalonRepository;
        $this->modifyOrderSalonRepository = $modifyOrderSalonRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->cookedProductInvoiceRepository = $cookedProductInvoiceRepository;
        $this->cookedProductRepository = $cookedProductRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->currencyRepository = $currencyRepository;
        $this->usersRepository = $usersRepository;
	}

    public function livingTables()
    {
    	$tablesActives = $this->tableSalonRepository->allTablesActive();
    	$allTables = $this->tableSalonRepository->allFilterScholl();

        return view('restaurant.salon.index', compact('allTables','tablesActives'));
    }


    /**
     * ---------------------------------------------------------------------
     * @Author     : Francisco Gamonal "fgamonal@sistemasamigables.com"
     * @Date       Create: 2016
     * @Time       Create: ${TIME}
     * @Date       Update: 0000-00-00
     * ---------------------------------------------------------------------
     * @Description: Con este metodo mostramos la vista de los grupo de
     *             menu que tiene el restaurante para seleccionarlos
     * @Pasos      :
     * @param Request $request
     * @param         $token
     * ----------------------------------------------------------------------
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * ----------------------------------------------------------------------
     */
    public function validateToken(Request $request, $token)
    {
        $table = $this->tableSalonRepository->token($token);
        $groups = $this->groupMenuRepository->getModel()->where('school_id',userSchool()->id)->with('menus')->get();
        //$orders = $this->orderSalonRepository->orders($table);
        $table  = $this->tableSalonRepository->token($token);
        $orders = $this->orderSalonRepository
            ->getModel()
            ->select('order_salons.*')
            ->where('table_salon_id', $table->id)
            ->where('status', 'no aplicado')
            ->where('canceled', false)
            ->with('menuRestaurant')
            ->with('modifyMenu')
            ->get();
        $foods = $this->orderSalonRepository
            ->getModel()
            ->whereHas('menuRestaurant',function ($q){
                $q->where('type',0);
            })
            ->where('table_salon_id', $table->id)
            ->where('status', 'no aplicado')
            ->where('canceled', false)
            ->count();
        $drinks =$this->orderSalonRepository
            ->getModel()
            ->whereHas('menuRestaurant',function ($q){
                $q->where('type',1);
            })
            ->where('table_salon_id', $table->id)
            ->where('status', 'no aplicado')
            ->where('canceled', false)
            ->count();
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares

        if($table){
            return view('restaurant.salon.ordered', compact('table','groups', 'foods','orders','drinks', 'tc'));
        }else{
            return redirect()->back();
        }
    }


    /**
     * ---------------------------------------------------------------------
     * @Author     : Francisco Gamonal "fgamonal@sistemasamigables.com"
     * @Date       Create: 2016
     * @Time       Create: ${TIME}
     * @Date       Update: 0000-00-00
     * ---------------------------------------------------------------------
     * @Description: Con este cuadro mostramos la ventana flotante para elegir
     *             la cantidad de platos.
     * @Pasos      :
     * @param Request $request
     * @param         $token_table
     * @param         $token
     * ----------------------------------------------------------------------
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * ----------------------------------------------------------------------
     */
    public function menuToken(Request $request, $token_table, $token)
    {
        $menuElement = $this->menuRestaurantRepository->getModel()->with('processedProduct')->where('token',$token)->first();
        return view('restaurant.menu.detail',compact('menuElement','token_table'));
    }

    /*******************************************************
     * @Author     : Francisco Gamonal
     * @Email      : fgamonal@sistemasamigables.com
     * @Create     : ${DATE}
     * @Update     : 0000-00-00
     ********************************************************
     * @Description:
     *
     *
     *
     * @Pasos      :
     *
     *
     *
     * @param Request $request
     *
     * @return mixed
     ********************************************************/
    public function store(Request $request)
    {
        $verificar = $request->all();
        $is_cash_express = false;
        if(array_key_exists('type',$verificar)):
            $table = $this->tableSalonRepository->getModel()
                        ->where('restaurant','express')
                        ->where('school_id', userSchool()->id)
                        ->lists('token');
            $request->menu_token  = $verificar['token'];
            $request->table_token = $table[0];
            $request->modify_menu = 0;
            $request->qty         = 1;
            $is_cash_express = true;
        endif;

        if($request->type=='cooked'):
            $menuRestaurant = $this->cookedProductRepository->token($request->menu_token);
        else:
            $menuRestaurant = $this->menuRestaurantRepository->token($request->menu_token);
        endif;

        $tableSalon = $this->tableSalonRepository->token($request->table_token);
        $orderSalon = $this->orderSalonRepository->getModel();
        $amount     = $request->qty;

        $dataOrderSalon['user_id']            = Auth::user()->id;
        $dataOrderSalon['menu_restaurant_id'] = $menuRestaurant->id;
        $dataOrderSalon['table_salon_id']     = $tableSalon->id;
        $dataOrderSalon['qty']                = $amount;
        $dataOrderSalon['date']               = Carbon::now()->toDateString();
        $dataOrderSalon['modify']             = $request->modify_menu;
        $dataOrderSalon['token']              = md5(uniqid(Auth::user()->id, true));

        if($orderSalon->isValid($dataOrderSalon)):
            $orderSalon->fill($dataOrderSalon);
            DB::beginTransaction();
            $orderSalon->save();
            // Send kitchenOrder from table
            //$this->kitchenOrder($orderSalon, $menuRestaurant, $menuRestaurant->processedProduct, $tableSalon);

            // Add items in modify_orders
            if($request->modify_menu == '1'){
                foreach ($request->items_menu as $key => $value) {
                    $cookedProduct = $this->cookedProductRepository->token($value);

                    $modifyOrder = $this->modifyOrderSalonRepository->getModel();

                    $data['user_id'] = Auth::user()->id;
                    $data['order_salon_id'] = $orderSalon->id;
                    $data['cooked_product_id'] = $cookedProduct->id;
                    $data['type'] = 'Base';

                    $modifyOrder->fill($data);
                    $modifyOrder->save();
                }

                // Aditionals
                if($request->aditionals){
                    foreach ($request->aditionals as $key => $value) {
                        $cookedProduct = $this->cookedProductRepository->token($value);

                        $modifyOrder = $this->modifyOrderSalonRepository->getModel();

                        $data['user_id'] = Auth::user()->id;
                        $data['order_salon_id'] = $orderSalon->id;
                        $data['cooked_product_id'] = $cookedProduct->id;
                        $data['type'] = 'Adicional';
                        $modifyOrder->fill($data);
                        \Log::info(__FUNCTION__.' '.json_encode($data));
                        $modifyOrder->save();
                    }
                }
            }
            DB::commit();
            if($is_cash_express)
            {
                return $this->exito($orderSalon);
            }
            return $this->exito('Se Registró el menú correctamente');
        endif;

        return $this->errores($orderSalon->errors);
    }

    public function orders(Request $request, $table_token)
    {
        $table = $this->tableSalonRepository->token($table_token);
        $orders = $this->orderSalonRepository->orders($table);
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
        return view('restaurant.salon.detail_of_orders', compact('orders','table','tc'));
    }

    public function prints(Request $request, $table_token)
    {
        $table = $this->tableSalonRepository->token($table_token);
        $orders = $this->orderSalonRepository->orders($table);
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
        $client = 'Cliente Contado';
        return view('restaurant.salon.detail_of_orders', compact('orders','table', 'client','tc'));
    }

    /**************************************************
     * @Author: Francisco Gamonal
     * @Email: fgamonal@sistemasamigables.com
     * @Create: 30/1/17 21:53   @Update 0000-00-00
     ***************************************************
     * @Description: Aqui se envia los datos a la vista
     * de ordenes hechas para la factura previa a pagar
     * del cliente permitiendo imprimir pero matener
     * abierta la cuenta.
     * @Pasos:
     *
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     **************************************************/
    public function printOrder(Request $request){
        $table  = $this->tableSalonRepository->token($request->token);
        $orders = $this->orderSalonRepository->orders_not_applied($table);
        $lists =array();

        $cantidad = 0;
        $menuRestaurants = $this->menuRestaurantRepository->getModel()->get();
        foreach ($menuRestaurants AS $menuRestaurant):
            $order = $this->orderSalonRepository
                ->orders_not_applied_group($menuRestaurant->id,$table);
            if($order):

            $cantidad = $order;
            $lists[] = ['menu'=>$menuRestaurant->name,
                'costo' => $menuRestaurant->costo,
                'money' => $menuRestaurant->money,
                'cantidad'=> $cantidad];
            endif;
        endforeach;
        \Log::info($lists);
        //echo json_encode($orders); die;
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
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
            }
            $total_orders = array();
            $total_orders['subtotal'] = $total;
            $total_orders['tax']      = $total * iva();
            if(! $table->barra ){
                //verificamos en que regimen esta para calcular el 10%
                if(userSchool()->regime_type == 'tradicional'):
                    $total_orders['service'] = $total * isv();
                else:
                    $total_orders['service'] = taxAdd($total) * isv();
                endif;
            }else{
                $total_orders['service'] = 0;
            }

            $total_orders['total'] = number_format($total_orders['subtotal'] + $total_orders['tax'] + $total_orders['service'],0,'.','');
            \Log::info($total_orders['total']);
            $total_orders['subtotal'] = number_format($total_orders['subtotal'],0,'.','');
            $total_orders['tax'] = number_format($total_orders['tax'],0,'.','');
            $total_orders['service'] = number_format($total_orders['service'],0,'.','');
            $total_orders['dolar']= $total_orders['total'] / $tc;
            $total_orders['total'] = $this->multipleOfFive($total_orders['total']);
           // echo json_encode($total_orders['total']); die;
            $view = view('restaurant.salon.print-order', compact('total_orders','orders', 'table', 'tc','lists'));
            return $view;
        }else{
            return $this->errores('No se ha encontrado pedidos en esta mesa.');
        }
    }

    public function printInvoice($token){
        $invoice = $this->invoiceRepository->token($token);
        $table = $this->tableSalonRepository->find($invoice->table_salon_id);
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
        $orders = $this->cookedProductInvoiceRepository->getModel()->where('invoice_id', $invoice->id)->with('menuRestaurant')->get();
        \Log::info($invoice);

        $view = view('restaurant.salon.print-invoice', compact('invoice', 'orders', 'table', 'tc'));
        //return $this->exito(array('invoice' => $invoice, 'orders' => $orders, 'school' => userSchool(), 'table' => $table, 'user' => Auth::user()->nameComplete(), 'tc' => $tc));
        return $view;
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 30/1/17 21:31   @Update 0000-00-00
     ***************************************************
     * @Description: Con este methodo enviamos los platos
     * a la comanda de cocina
     *
     *
     * @Pasos:
     *
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     **************************************************/
    public function ordersKitchen(Request $request, $token){

        $table  = $this->tableSalonRepository->token($token);
        $orders = $this->orderSalonRepository->getModel()
            ->whereHas('menuRestaurant',function ($q){
                $q->where('type',0);
            })->where('table_salon_id', $table->id)
                                             ->where('cooked', false)
                                             ->where('status', 'no aplicado')
                                             ->with('menuRestaurant')
                                             ->with('modifyMenu')
                                             ->get();

        if($orders->count())
        {
            $this->orderSalonRepository->getModel()
            ->whereHas('menuRestaurant',function ($q){
                $q->where('type',0);
            })->where('table_salon_id', $table->id)
            ->where('cooked', false)
                                       ->where('status', 'no aplicado')
                                       ->update(array('cooked' => true));

            return view('restaurant.salon.detail_order',compact('orders','table'));
        }
        return $this->errores('No se tienen impresiones para cocina');
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 30/1/17 21:30   @Update 0000-00-00
     ***************************************************
     * @Description: Con este methodo enviamos las bebidas
     * a la impresion para las comandas del bar
     *
     *
     * @Pasos:
     *
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     **************************************************/
    public function ordersDrinks(Request $request, $token){
        $table  = $this->tableSalonRepository->token($token);
        $orders = $this->orderSalonRepository->getModel()
            ->whereHas('menuRestaurant',function ($q){
                $q->where('type',1);
            })->where('table_salon_id', $table->id)
            ->where('cooked', false)
            ->where('status', 'no aplicado')
            ->with('menuRestaurant')
            ->with('modifyMenu')
            ->get();

        if($orders->count())
        {
            $this->orderSalonRepository->getModel()
                ->whereHas('menuRestaurant',function ($q){
                    $q->where('type',1);
                })->where('table_salon_id', $table->id)
                ->where('cooked', false)
                ->where('status', 'no aplicado')
                ->update(array('cooked' => true));

            return view('restaurant.salon.detail_order',compact('orders','table'));
        }
        return $this->errores('No se tienen impresiones para el Bar');
    }
    /**************************************************
    * @Author: Francisco Gamonal
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 30/1/17 22:43   @Update 0000-00-00
    ***************************************************
    * @Description: Con este methodo se envian los datos
    * a la vista para generar la factura a imprimir
    *
    *
    * @Pasos:
    *
    *
    * @return
    ***************************************************/
    public function cash(Request $request){
        $table = $this->tableSalonRepository->token($request->token);
        $orders = $this->orderSalonRepository->orders_not_applied($table);
        $exchange = $this->currencyRepository->find(10)->value; // Currience dolares
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
        // Verify Orders
        if( $orders->count() )
        {
            $total = 0;
            foreach ($orders as $key => $order)
            {
                if($order->menuRestaurant->money == 'dolares'){
                    $total += $order->menuRestaurant->costo * $exchange * $order->qty;
                }else{
                    $total += $order->menuRestaurant->costo * $order->qty;
                }
            }
            $total_orders = array();
            $total_orders['subtotal'] = $total;
            $total_orders['tax']      = $total * iva();
            if(! $table->barra ){
                //verificamos en que regimen esta para calcular el 10%
                if(userSchool()->regime_type == 'tradicional'):
                    $total_orders['service'] = $total * isv();
                else:
                    $total_orders['service'] = taxAdd($total) * isv();
                endif;
            }else{
                $total_orders['service'] = 0;
            }
            $total_orders['total'] = (
                                        $total_orders['subtotal'] +
                                        $total_orders['tax'] +
                                        $total_orders['service']
                                      ) ;
            $total_orders['subtotal'] = number_format($total_orders['subtotal'],0,'.','');
            $total_orders['tax'] = number_format($total_orders['tax'],0,'.','');
            $total_orders['service'] = number_format($total_orders['service'],0,'.','');
            $total_orders['dolar']= $total_orders['total'] / $tc;
            $total_orders['total'] = $this->multipleOfFive($total_orders['total']);

            \Log::info(__FUNCTION__.' '.$total_orders['total']);
            $paymentMethods = $this->paymentMethodRepository->getModel()->where('type', 'sale')->get();
            return view('restaurant.salon.cash_order', compact('total_orders', 'paymentMethods', 'exchange', 'table'));
        }
        return $this->errores('No se tienen ordenes por facturar a la: '.$table->name);
    }

    public function deleteOrder(Request $request){
        $data = $this->convertionObjeto();
        $order_salon = $this->orderSalonRepository->token($data->token);
        if($order_salon->modify){
            $this->modifyOrderSalonRepository->getModel()->where('order_salon_id', $order_salon->id)->delete();
        }
        $order_salon->delete();
        return $this->exito('Se eliminó la orden correctamente.');
    }

    public function splitOrders(Request $request, $token){
        $table = $this->tableSalonRepository->token($token);
        if(!$table){
            return redirect('/institucion/inst/salon');
        }
        $orders = $this->orderSalonRepository->orders_not_applied($table, 'split');
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
        $paymentMethods = $this->paymentMethodRepository->getModel()->where('type', 'sale')->get();
        return view('restaurant.salon.split_orders', compact('orders','table','tc','paymentMethods'));
    }

    public function joinOrders(Request $request, $token){
        $table = $this->tableSalonRepository->token($token);
        if(!$table){
            return redirect('/institucion/inst/salon');
        }
        $tablesActive = $this->tableSalonRepository->getModel()
            ->whereHas('OrderSalons',function ($q){
                $q->where('status','no aplicado');
            })
            ->get();
        $tc = $this->currencyRepository->find(10)->value; // Currience dolares
        $paymentMethods = $this->paymentMethodRepository->getModel()->where('type', 'sale')->get();
        return view('restaurant.salon.join_orders', compact('tablesActive','table','tc','paymentMethods'));
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: ${DATE} ${TIME}
     * @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     ***************************************************/
    public function postJoinOrders(){
        $datas = $this->convertionObjeto();

        for ($i = 0; $i < count($datas->stateTable); $i++):
            /* Comprobamos cuales estan habialitadas y esas las guardamos */
            if($datas->stateTable[$i] == true):

            $tableMaster = $this->tableSalonRepository->token($datas->idTableMaster);
            $tableSclave = $this->tableSalonRepository->token($datas->idTable[$i]);
            $this->orderSalonRepository->getModel()->where('table_salon_id',$tableSclave->id)
                ->where('status','no aplicado')
                ->update(['table_salon_id'=>$tableMaster->id]);
            endif;
        endfor;

       return $this->exito('Se unieron las Cuentas con exito');
    }

    private function kitchenOrder($order, $menuRestaurant, $products, $tableSalon){
        foreach ($products as $key => $product) {
            //if($product->type == 'comida'){
                $message = array(
                            'order' => $order,
                            'table' => $tableSalon,
                            'products' => $products,
                            'menuRestaurant' => $menuRestaurant,
                            'user_nameComplete' => Auth::user()->nameComplete()
                           );
                event(new KitchenOrder($message));
                break;
            //}
        }
    }



    /**
     * ---------------------------------------------------------------------
     * @Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
     * @Date Create: 2016-06-23
     * @Date Update: 2016-00-00
     * ---------------------------------------------------------------------
     * @Description: Se anulara la factura mediante el token de la misma
     *
     * ----------------------------------------------------------------------
     * @return mixed
     * ----------------------------------------------------------------------
     */
    public function anular($token)
    {
        $verify =$this->invoiceRepository->token($token);
        if($verify->status == 'activo'):
            $this->invoiceRepository->getModel()->where('token',$token)->update(['status'=>'inactivo']);
        endif;
      return  redirect('institucion/inst/facturas-lista/ver');
    }

    public function invoice()
    {
        $invoices = $this->invoiceRepository->allFilterScholl();
        $total_service = $this->invoiceRepository->getModel()
                              ->where('date', Carbon::now()->toDateString())
                              ->sum('service');
        return view('restaurant.facturas.index',compact('invoices','total_service'));
    }

    public function canceled(Request $request)
    {
        $data  = $this->convertionObjeto();
        $order = $this->orderSalonRepository->token($data->token);

        if(!$order){
            return $this->errores('La orden que intenta anular no existe');
        }
        \Log::info($data->password);
        $validate = $this->usersRepository->validateUser($data->password);

        if(is_numeric($validate)){
            $user_auth = $validate;
        }else{
            return $this->errores($validate);
        }

        $order->description = $data->description;
        $order->canceled = true;
        $order->status = 'aplicado';
        $order->save();
        return $this->exito('Orden anulada.');
    }

    public function rePrint(Request $request, $token)
    {
        $invoice = $this->invoiceRepository->token($token);
        $table = $this->tableSalonRepository->idConsult($invoice->table_salon_id);
        $tc = $invoice->tc; // Currience dolares
        $copy = "Copia";
        $lists =array();
        \Log::info('Reimpresion: '.json_encode($invoice).' table'.$invoice->table_salon_id.' Salon'.json_encode($table));

        $cantidad = 0;
        $menuRestaurants = $this->menuRestaurantRepository->getModel()->get();
        foreach ($menuRestaurants AS $menuRestaurant):
            $order = $this->orderSalonRepository
                ->orders_not_applied_group($menuRestaurant->id,$table);
            if($order):

                $cantidad = $order;
                $lists[] = ['menu'=>$menuRestaurant->name,
                    'costo' => $menuRestaurant->costo,
                    'money' => $menuRestaurant->money,
                    'cantidad'=> $cantidad];
            endif;
        endforeach;
        $view = view('restaurant.salon.print-invoice', compact('invoice', 'table', 'tc', 'copy','lists'));
        return $view;
    }

    public function printRestaurant()
    {
        $data = Input::all();

        $numbers = InvoicesService::orderBy('number','DESC')->get();
        if(count($numbers) > 0):
            $number = $numbers->count() + 1;
        else:
            $number = 1;
        endif;
        InvoicesService::create([
            'date'=>\Carbon\Carbon::now()->format('d-m-Y'),
            'number'=>$number,
            'customer'=>$data['customerRest'],
            'amount'=>$data['amountRest'],
            'user_id'=>currentUser()->id
        ]);

        $pdf = Fpdf::AddPage('P','pos');
        $pdf .= Fpdf::SetFont('Arial','B',14);
        $pdf .= Fpdf::MultiCell(0,7,utf8_decode(userSchool()->name),'C');
        $pdf .= Fpdf::Cell(0,7,utf8_decode(userSchool()->business_name),0,1,'C');
        $pdf .= Fpdf::MultiCell(0,7,"Ced.: ".  userSchool()->charter.' '.utf8_decode(userSchool()->address).', '.userSchool()->email,0,'C');
        $pdf .= Fpdf::Cell(0,7,'Factura: 1'  ,0,1,'C');
        $pdf .= Fpdf::Cell(0,7,'FECHA: '.  \Carbon\Carbon::now()->format('d-m-Y')  ,0,1,'C');
        $pdf .= Fpdf::Cell(0,7,'CLIENTE: '.utf8_decode($data['customerRest'])  ,0,1,'C');
        $pdf .= Fpdf::MultiCell(0,7,'Mesero(a): '. utf8_decode(Auth::user()->nameComplete()) ,'C');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::SetX(5);
        $pdf .= Fpdf::Line(5,Fpdf::GetY(),65,Fpdf::GetY());
        $pdf .= Fpdf::Cell(15,7,'CANT'  ,0,0,'C');
        $pdf .= Fpdf::Cell(15,7,'DESC'  ,0,0,'C');
        $pdf .= Fpdf::Cell(15,7,'P.U.'  ,0,0,'C');
        $pdf .= Fpdf::Cell(15,7,'TOTAL'  ,0,1,'C');
        $pdf .= Fpdf::Line(5,Fpdf::GetY(),65,Fpdf::GetY());
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','I',12);
        $pdf .= Fpdf::SetX(5);
        $pdf .= Fpdf::Cell(30,7,'SERVICIO DE RESTAURANTE',0,1,'L');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(5);
        $pdf .= Fpdf::SetFont('Arial','B',14);

        $pdf .= Fpdf::Line(5,Fpdf::GetY(),65,Fpdf::GetY());
        $pdf .= Fpdf::Cell(30,7,'TOTAL:'  ,0,0,'R');
        $pdf .= Fpdf::Cell(20,7,number_format($data['amountRest'],2) ,0,1,'L');
            $pdf .= Fpdf::Line(5,Fpdf::GetY(),65,Fpdf::GetY());

        $pdf .= Fpdf::SetFont('Arial','B',12);

        if(userSchool()->regime_type == 'tradicional'):
            $pdf .= Fpdf::Cell(0,7,'Autorizado mediante oficio'  ,0,1,'C');
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Nº DGT-R-033-2019 del 20/06/2019')  ,0,1,'C');
        else:
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Regimen simplificado' ) ,0,1,'C');
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Autorizado mediante oficio' ) ,0,1,'C');
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Nº DGT-R-033-2019 del 20/06/2019' ) ,0,1,'C');
        endif;
	        $pdf .= Fpdf::Cell(0,7,'GRACIAS POR SU COMPRA'  ,0,1,'C');
        Fpdf::Output();
        exit;
    }
}
