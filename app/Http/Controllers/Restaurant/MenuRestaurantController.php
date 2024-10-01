<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/01/16
 * Time: 08:52 PM
 */

namespace AccountHon\Http\Controllers\Restaurant;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\CookedProductInvoiceRepository;
use AccountHon\Repositories\Restaurant\GroupMenuRepository;
use AccountHon\Repositories\Restaurant\MenuRestaurantRepository;
use AccountHon\Repositories\General\ProcessedProductRepository;
use AccountHon\Repositories\Restaurant\MenuRestaurantCookedProductRepository;
use AccountHon\Repositories\Restaurant\RecipeRepository;
use AccountHon\Traits\Convert;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class MenuRestaurantController extends Controller
{
    use Convert;
    /**
     * @var MenuRestaurantRepository
     */
    private $menuRestaurantRepository;

    /**
     * @var ProcessedProductRepository
     */
    private $processedProductRepository;

    /**
     * @var MenuRestaurantCookedProductRepository
     */
    private $menuRestaurantCookedProductRepository;
    /**
     * @var GroupMenuRepository
     */
    private $groupMenuRepository;
    /**
     * @var CookedProductInvoiceRepository
     */
    private $cookedProductInvoiceRepository;
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;

    /**
     * MenuRestaurantController constructor.
     *
     * @param MenuRestaurantRepository $menuRestaurantRepository
     * @param ProcessedProductRepository $processedProductRepository
     * @param MenuRestaurantCookedProductRepository $menuRestaurantCookedProductRepository
     * @param GroupMenuRepository $groupMenuRepository
     * @param CookedProductInvoiceRepository $cookedProductInvoiceRepository
     * @param RecipeRepository $recipeRepository
     *
     * @internal param ProcessedProductRepository $cookedProductRepository
     */
    public function __construct(
        MenuRestaurantRepository $menuRestaurantRepository,
        ProcessedProductRepository $processedProductRepository,
        MenuRestaurantCookedProductRepository $menuRestaurantCookedProductRepository,
        GroupMenuRepository $groupMenuRepository,
        CookedProductInvoiceRepository $cookedProductInvoiceRepository,
        RecipeRepository $recipeRepository
    )
    {

        $this->menuRestaurantRepository = $menuRestaurantRepository;
        $this->menuRestaurantCookedProductRepository = $menuRestaurantCookedProductRepository;
        $this->groupMenuRepository = $groupMenuRepository;
        $this->cookedProductInvoiceRepository = $cookedProductInvoiceRepository;
        $this->recipeRepository = $recipeRepository;
        $this->processedProductRepository = $processedProductRepository;
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
    public function index()
    {
        $menuRestaurants = $this->menuRestaurantRepository->allFilterScholl();
        return view('restaurant.menu.index',compact('menuRestaurants'));
    }


    public function create()
    {
        $groupMenus = $this->groupMenuRepository->allFilterScholl();
        return view('restaurant.menu.create',compact('groupMenus'));
    }


    public function store()
    {
        $menu = $this->convertionObjeto();

        $dataMenu = $this->CreacionArray($menu,'MenuRestaurant');
        $dataMenu['user_id'] = currentUser()->id;
        $groupMenu = $this->groupMenuRepository->token($dataMenu['groupMenuId']);
        $dataMenu['group_menu_id'] = $groupMenu->id;
        unset($dataMenu['groupMenuId']);
        \Log::info($dataMenu);
        $menuRestaurant = $this->menuRestaurantRepository->getModel();
        if($menuRestaurant->isValid($dataMenu)):
            $menuRestaurant->fill($dataMenu);
            $menuRestaurant->save();

            return $this->exito(array('href'=>'/institucion/inst/menu-restaurante/componentes/'.$menuRestaurant->token,'redirect'=>true));

        endif;

        return $this->errores($menuRestaurant->errors);


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
    public function component($token)
    {
        $menuElement = $this->menuRestaurantRepository->token($token);
        $cookedProducts = $this->processedProductRepository->allFilterScholl();
        if(!$menuElement):
            Log::info("No se encuentra informacion con ese token Class: ".__CLASS__.' Function: '.__FUNCTION__.' line: '.__LINE__);
            abort(503,'El Menu que trata de agregarle un componente no Existe');
        endif;
        $cookedProductsAdded = $this->menuRestaurantCookedProductRepository
            ->oneWhere('menu_restaurant_id',$menuElement->id,'amount');
        $totalCost =0;
        $totalPrice =0;
        foreach ($cookedProductsAdded AS $cookedProduct):
                $recipes = $this->recipeRepository->getModel()
                    ->where('cooked_product_id',$cookedProduct->cooked_product_id)->get();
                foreach ($recipes AS $recipe):
                    $cookedProduct->cost += $recipe->amount * $recipe->rawProduct->cost;
                endforeach;
            $cookedProduct->price = $this->processedProductRepository->find($cookedProduct->cooked_product_id)->price;
            $totalCost += $cookedProduct->cost;
            $totalPrice += $cookedProduct->price;
        endforeach;

        return view('restaurant.menu.component',compact('menuElement','cookedProducts','cookedProductsAdded','totalCost','totalPrice'));
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
    public function postComponent()
    {
        $component = $this->convertionObjeto();
        
        $dataComponent = $this->CreacionArray($component, 'Components');
        $dataComponent['cooked_product_id']= $this->processedProductRepository->token($dataComponent['processedProduct'])->id;
        $dataComponent['menu_restaurant_id']= $this->menuRestaurantRepository->token($dataComponent['menu'])->id;
        $dataComponent['user_id']= currentUser()->id;
        $nameMenu =  $this->menuRestaurantRepository->token($dataComponent['menu'])->name;
        $nameCookedProduct =  $this->processedProductRepository->token($dataComponent['processedProduct'])->name;
        $verify = $this->menuRestaurantCookedProductRepository->getModel()
                ->where('cooked_product_id',$dataComponent['cooked_product_id'])
                ->where('menu_restaurant_id',$dataComponent['menu_restaurant_id'])->get();
        if(!$verify->isEmpty()):
            return $this->errores('El Componente: "'.$nameCookedProduct.'" que esta agregando ya existe en el Menu');
        endif;



        $menuRestaurantCookedProduct = $this->menuRestaurantCookedProductRepository->getModel();

        if ($menuRestaurantCookedProduct->isValid($dataComponent)):
            $menuRestaurantCookedProduct->fill($dataComponent);
            $menuRestaurantCookedProduct->save();
            return $this->exito('Se Agrego con exito el componente '.$nameCookedProduct.' a '.$nameMenu);
            //return $this->exito(array('href'=>'/institucion/inst/menu-restaurante/componentes/'.$token,'redirect'=>true));
        endif;
        return $this->errores($menuRestaurantCookedProduct->errors);
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
    public function edit($token)
    {
        $menu = $this->menuRestaurantRepository->token($token);
         $groupMenus = $this->groupMenuRepository->allFilterScholl();
        return view('restaurant.menu.edit',compact('menu','groupMenus'));
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
    public function update()
    {
        $menu = $this->convertionObjeto();

        $dataMenuRestaurant = $this->CreacionArray($menu, 'MenuRestaurant');
        $dataMenuRestaurant['user_id']= currentUser()->id;

        $groupMenu = $this->groupMenuRepository->token($dataMenuRestaurant['group_menu_id']);
        $dataMenuRestaurant['group_menu_id'] = $groupMenu->id;
        $menuElement = $this->menuRestaurantRepository->token($menu->token);

        if($menuElement->isValid($dataMenuRestaurant)):
            $menuElement->fill($dataMenuRestaurant);
            $menuElement->update();
            return $this->exito('Se Actualizo con exito el Menu de Restaurante');
            //return $this->exito(array('href'=>'/institucion/inst/menu-restaurante/editar/'.$menu->token,'redirect'=>true));
        endif;

        return $this->errores($menuElement->errors);
    }

    /**
     * ---------------------------------------------------------------------
     * @Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
     * @Date Create: 2016-05-10
     * @Date Update: 2016-00-00
     * ---------------------------------------------------------------------
     * @Description:Eliminacion de Menu
     *
     *
     * @Pasos:
     * ----------------------------------------------------------------------
     * @return mixed
     * ----------------------------------------------------------------------
     */
    public function destroy(){
        $token = $this->convertionObjeto();

        $menu = $this->menuRestaurantRepository->token($token->token);
         $factura = $this->cookedProductInvoiceRepository->getModel()->where('menu_restaurant_id',$menu->id)->get();
        if(!$factura->isEmpty()):
          return  $this->errores(['Ya este Menu ha sido facturado por lo menos en una ocasión no se puede eliminar']);
        endif;

        $componente = $this->menuRestaurantCookedProductRepository->getModel()->where('menu_restaurant_id',$menu->id)->get();
        if(!$componente->isEmpty()):
            return  $this->errores(['Tiene componentes asignados, debe quitarlos antes de Eliminar el Menu']);
        endif;

        if($menu->delete()):
            return $this->exito('Se Elimino con Exito');
        endif;

        return $this->errores(['No se ha podido eliminar el Menu']);
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
    public function deleteComponent()
    {
        $data = $this->convertionObjeto();
        $menuRestaurantCookedProduct = $this->menuRestaurantCookedProductRepository->getModel();
        $idToDelete = base64_decode($data->idMenuRestaurantCookkedProduct);

        if ($this->menuRestaurantCookedProductRepository->destroy($idToDelete)):
            return $this->exito('El componente se elimino satisfactoriamente');
        endif;

        return $this->errores($menuRestaurantCookedProduct->errors);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-26
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
    public function recordSale()
    {
        return view('restaurant.menu.reporte-sale');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-26
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
    public function reportPdf()
    {
        $date = $this->convertionObjeto();

        Session::put('dateIn', $date->dateInMenuRestaurant);
        Session::put('dateOut', $date->dateOutMenuRestaurant);
        Session::put('sale', $date->saleMenuRestaurant);
        Session::put('receipt', $date->receiptMenuRestaurant);
        Session::put('menu', $date->menuMenuRestaurant);
        return $this->exito("Se lograron filtrar los datos para el reporte");

    }

    public function salePdf()
    {
        $dateIn = Session::get('dateIn');
        $dateOut = Session::get('dateOut');
        $sale = Session::get('sale');
        $receipt = Session::get('receipt');
        $menu = Session::get('menu');
        $pdf  = Fpdf::AddPage('P','letter');
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,8,utf8_decode(userSchool()->name),0,1,'C');
        $pdf .= Fpdf::Cell(0,8,'HISTORIAL DE VENTAS',0,1,'C');
        $pdf .= Fpdf::Cell(0,8,$dateIn.'  a  '.$dateOut,0,1,'C');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','B',14);
        $pdf .= Fpdf::Cell(25,10,'Menus',0,1,'C');
        $groups = $this->groupMenuRepository->allFilterScholl();
        $i= 0;
        $total=0;
        foreach($groups AS $group): $i++;
            $pdf .= Fpdf::SetFont('Arial','I',12);
            $pdf .= Fpdf::Cell(10,10,utf8_decode($i.'.-'),0,0,'L');
            $pdf .= Fpdf::Cell(82,10,utf8_decode(ucwords(strtolower($group->name))),0,1,'L');
            $menus = $this->menuRestaurantRepository->oneWhere('group_menu_id',$group->id,'name');
            $j=0;
            foreach($menus AS $menu):$j++;
                $pdf .= Fpdf::SetFont('Arial','I',12);
                $pdf .= Fpdf::Setx(20);
                $pdf .= Fpdf::Cell(10,10,utf8_decode($j.'.-'),0,0,'L');
                // Ventas por rango de fecha
                if($sale):
                    $count = $this->menuRestaurantCookedProductRepository->saleXMenuRestauran([$dateIn,$dateOut],'amount','menu_restaurant_id',$menu->id);
                    $pdf .= Fpdf::Cell(70,10,utf8_decode(ucwords(strtolower($menu->name))),0,0,'L');
                    $pdf .= Fpdf::Cell(15,10,number_format($count,0),0,0,'L');
                    $pdf .= Fpdf::Cell(40,10,number_format($count*$menu->costo,2),0,1,'L');
                    $total += $count*$menu->costo;
                //
                elseif($menu):
                    $pdf .= Fpdf::Cell(70,10,utf8_decode(ucwords(strtolower($menu->name))),0,1,'L');

                elseif($receipt):
                    $pdf .= Fpdf::Cell(70,10,utf8_decode(ucwords(strtolower($menu->name))),0,1,'L');
                    if($menu->cookedProducts): $h=0;
                        foreach ($menu->cookedProducts AS $cookedProduct): $h++;
                            $pdf .= Fpdf::Setx(30);
                            $pdf .= Fpdf::Cell(10,10,$h.'.-',0,0,'L');
                            $pdf .= Fpdf::Cell(40,10,utf8_encode(ucwords(strtolower($cookedProduct->name))),0,1,'L');
                            $receipts = $this->recipeRepository->getModel()->where('cooked_product_id',$cookedProduct->id)->get();
                            $k=0;
                            foreach ($receipts AS $receipt): $k++;
                                $pdf .= Fpdf::Cell(10,10,$k.'.-',0,0,'L');
                                $pdf .= Fpdf::Cell(40,10,utf8_encode(ucwords(strtolower($receipt->rawProducts->name))),0,0,'L');
                                $pdf .= Fpdf::Cell(20,10,number_format($receipt->amount,2),0,1,'C');
                            endforeach;
                        endforeach;
                    endif;
                endif;
            endforeach;
        endforeach;
        if($sale):
        $pdf .= Fpdf::SetFont('Arial','BI',14);
        $pdf .= Fpdf::Cell(100,10,utf8_decode('Total'),0,0,'R');
        $pdf .= Fpdf::Cell(40,10,number_format($total,2),0,1,'L');
        endif;
        $pdf .= Fpdf::Ln();
        Fpdf::Output('ventas de Menu Restaurante.pdf','I');
        exit;
    }
}