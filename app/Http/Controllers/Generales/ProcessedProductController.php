<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 06:31 PM
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\ConvertionRepository;
use AccountHon\Repositories\Restaurant\CookedProductInvoiceRepository;
use AccountHon\Repositories\General\ProcessedProductRepository;
use AccountHon\Repositories\Restaurant\GroupMenuRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\Restaurant\MenuRestaurantCookedProductRepository;
use AccountHon\Repositories\Restaurant\MenuRestaurantRepository;
use AccountHon\Repositories\General\RawMaterialRepository;
use AccountHon\Repositories\Restaurant\RecipeRepository;
use AccountHon\Traits\Convert;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProcessedProductController extends Controller
{
    use Convert;
    /**
     * @var ProcessedProductRepository
     */
    private $cookedProductRepository;
    /**
     * @var RawMaterialRepository
     */
    private $rawMaterialRepository;
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var ConvertionRepository
     */
    private $convertionRepository;
    /**
     * @var MenuRestaurantRepository
     */
    private $menuRestaurantRepository;
    /**
     * @var GroupMenuRepository
     */
    private $groupMenuRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;
    /**
     * @var MenuRestaurantCookedProductRepository
     */
    private $menuRestaurantCookedProductRepository;
    /**
     * @var CookedProductInvoiceRepository
     */
    private $cookedProductInvoiceRepository;
    /**
     * @var ProcessedProductRepository
     */
    private $processedProductRepository;

    /**
     * ProcessedProductController constructor.
     *
     * @param ProcessedProductRepository $processedProductRepository
     * @param RawMaterialRepository $rawMaterialRepository
     * @param RecipeRepository $recipeRepository
     * @param ConvertionRepository $convertionRepository
     * @param MenuRestaurantRepository $menuRestaurantRepository
     * @param GroupMenuRepository $groupMenuRepository
     * @param InvoiceRepository $invoiceRepository
     * @param MenuRestaurantCookedProductRepository $menuRestaurantCookedProductRepository
     * @param CookedProductInvoiceRepository $cookedProductInvoiceRepository
     *
     * @internal param ProcessedProductRepository $cookedProductRepository
     */
    public function __construct(
        ProcessedProductRepository $processedProductRepository,
        RawMaterialRepository $rawMaterialRepository,
        RecipeRepository $recipeRepository,
        ConvertionRepository $convertionRepository,
        MenuRestaurantRepository $menuRestaurantRepository,
        GroupMenuRepository $groupMenuRepository,
        InvoiceRepository $invoiceRepository,
        MenuRestaurantCookedProductRepository $menuRestaurantCookedProductRepository,
        CookedProductInvoiceRepository $cookedProductInvoiceRepository
    )
    {

        $this->rawMaterialRepository = $rawMaterialRepository;
        $this->recipeRepository = $recipeRepository;
        $this->convertionRepository = $convertionRepository;
        $this->menuRestaurantRepository = $menuRestaurantRepository;
        $this->groupMenuRepository = $groupMenuRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->menuRestaurantCookedProductRepository = $menuRestaurantCookedProductRepository;
        $this->cookedProductInvoiceRepository = $cookedProductInvoiceRepository;
        $this->processedProductRepository = $processedProductRepository;
    }

    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
     * @Create     : ${DATE}
     * @Update     : 2017-05-27
     ********************************************************
     * @Description: Cambio de nombre de tabla y creacion de
     * repositorio para limpiar codigo.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     ********************************************************/
    public function index()
    {
        $processedProducts =$this->processedProductRepository->listsPE();
        return view('general.processedProduct.index', compact('processedProducts'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-31
    |@Date Update: 2017-05-27
    |---------------------------------------------------------------------
    |@Description: Con esta accion mostramos la vista de agregar productos
    |   cocidos
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function create()
    {
        $lastCode = $this->processedProductRepository->listsPE();
        $code = $this->code($lastCode);
        return view('general.processedProduct.create',compact('code'));
    }

    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
     * @Create     : 2017-05-27
     * @Update     : 0000-00-00
     ********************************************************
     * @Description: Generando el codigo para los productos
     *               Elaborados.
     *
     * @param $lastCode
     *
     * @return string
     ********************************************************/
    private function code($lastCode)
    {
        $code = '0001';
        if(!$lastCode->isEmpty()):
            $code = $lastCode->last()->code+1;
            if($code < 10):
                $code = '000'.$code;
            elseif($code < 100):
                $code = '00'.$code;
            endif;
        endif;

        return $code;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-31
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |----------------------------------------------------------------------
    | @return \Illuminate\Http\RedirectResponse
    |----------------------------------------------------------------------
    */
    public function store()
    {
        $cooked = $this->convertionObjeto();
        $dataCookedProduct = $this->CreacionArray($cooked, 'ProcessedProduct');
        $dataCookedProduct['type']= 'comida';
        $dataCookedProduct['price']= ($dataCookedProduct['price']/((userSchool()->tax_rate/100)+1));
        $processedProduct = $this->processedProductRepository->getModel();


        if ($processedProduct->isValid($dataCookedProduct)):
            $processedProduct->fill($dataCookedProduct);
            $processedProduct->save();
            $token = $processedProduct->token;
            return $this->exito(array('href'=>'/institucion/inst/recetas/'.$token,'redirect'=>true));
            //return redirect()->route('crear-recipes',$token);
        endif;
    return $this->errores($processedProduct->errors);
    }

    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-01-03
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
    public function edit($token)
    {
        $cooked = $this->processedProductRepository->token($token);
        return view('general.processedProduct.edit',compact('cooked'));
    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-31
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |----------------------------------------------------------------------
    | @return \Illuminate\Http\RedirectResponse
    |----------------------------------------------------------------------
    */
    public function update()
    {
        $cooked = $this->convertionObjeto();
        $dataCookedProduct = $this->CreacionArray($cooked, 'ProcessedProduct');

        $cookedProduct = $this->processedProductRepository->token($cooked->token);
        $dataCookedProduct['price']= ($dataCookedProduct['price']/((userSchool()->tax_rate/100)+1));


        $cookedProduct->fill($dataCookedProduct);
            $cookedProduct->update();
        $rawProducts = $this->rawMaterialRepository->allFilterScholl();
        if($rawProducts->isEmpty()):
            return $this->exito(array('href'=>'/institucion/inst/productos-elaborados/ver','redirect'=>true));
        endif;
            return $this->exito(array('href'=>'/institucion/inst/recetas/'.$cooked->token,'redirect'=>true));


    }

    /**
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
    public function recipes($token)
    {
        $cooked = $this->processedProductRepository->token($token);
        $rawProducts = $this->rawMaterialRepository->allFilterScholl();
        if($rawProducts->isEmpty()):
            Log::info('no se encontraron productos class: '.__CLASS__.' function: '.__FUNCTION__.' line: '.__LINE__);
            abort(503,'Este Restaurante no tiene catalogo de productos crudos, debe ir al menu de productos crudos y agregarlos');
        endif;
        $units = $this->units($rawProducts[0]->units);
        $totalrecipt=0;
       if(count($cooked->recipts)>0){
           foreach($cooked->recipts AS $recipe):

                $totalrecipt += $recipe->amount * $recipe->rawProduct->cost;

        endforeach;
       }


        return view('general.processedProduct.recipes',compact('cooked','units','rawProducts','totalrecipt'));
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
        public function changeUnits()
    {
        $data = $this->convertionObjeto();
        $datos = $this->rawMaterialRepository->token($data->token);
        return $this->units($datos->units);

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
    public function units($unit)
    {
        switch($unit)
        {
            case 'unidades':
                return ['unidades'];
                break;
            case 'kg':
                return ['gr','lb','kg'];
            break;
            case 'gr':
                return ['gr','lb','kg'];
                break;
            case 'G':
                return ['gr','lb','kg'];
                break;
            case 'lb':
                return ['gr','lb','kg'];
                break;
            case 'ml':
                return ['ml','l','cups','Tbs','Tps'];
                break;
            case 'l':
                return ['ml','l','cups','Tbs','Tps'];
                break;
            case 'cups':
                return ['ml','l','cups','Tbs','Tps'];
                break;
            case 'tbs':
                return ['ml','l','cups','Tbs','Tps'];
                break;
            case 'tps':
                return ['ml','l','cups','Tbs','Tps'];
                break;
        }


    }

    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
     * @Create     : ${DATE}
     * @Update     : 2017-05-27
     ********************************************************
     * @Description:
     *
     *
     *
     * @Pasos      :
     *
     *
     *
     * @return mixed
     ********************************************************/
    public function storeRecipes()
    {
        $recipe = $this->convertionObjeto();

        $dataRecipe = $this->CreacionArray($recipe, 'Recipes');
        $rawProduct = $this->rawMaterialRepository->token($dataRecipe['rawProduct']);
        $dataRecipe['rawProduct_id']=$rawProduct->id;
        $dataRecipe['cooked_product_id']= $this->processedProductRepository->token($dataRecipe['processedProduct'])->id;
        $token = $dataRecipe['processedProduct'];
        unset($dataRecipe['rawProduct']);
        unset($dataRecipe['cookedProduct']);
        unset($dataRecipe['school_id']);
        if($dataRecipe['units']==$rawProduct->units):
            $dataRecipe['units']=$dataRecipe['units'];
            $amount = $dataRecipe['amount'] * 1;
            $units=$dataRecipe['units'];
        else:
            $convertion = $this->convertionRepository->dataUnits($dataRecipe['units'],$rawProduct->units);
            if($convertion->isEmpty()):
                $this->saveLogs('No se encontro de la tabla convertions',__CLASS__,__FUNCTION__,__LINE__,$convertion);
                $this->errores('La consulta a la conversion no genero datos');
            endif;
            $amount = $dataRecipe['amount'] * $convertion[0]->amountOut;
            $units= $convertion[0]->unitsOut;
        endif;
        $dataRecipe['unitsIn'] = $dataRecipe['units'];
        $dataRecipe['amount'] = $amount;
        $dataRecipe['units'] = $units ;


        $recipes = $this->recipeRepository->getModel();

        if ($recipes->isValid($dataRecipe)):
            $recipes->fill($dataRecipe);
            $recipes->save();
            return $this->exito(array('href'=>'/institucion/inst/recetas/'.$token,'redirect'=>true));
        endif;
        return $this->errores($recipes->errors);
    }

    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     ********************************************************/
    public function deleteRecipes()
    {
        $data = $this->convertionObjeto();

        $recipt = $this->recipeRepository->token($data->tokenCookedProduct);

        if ($recipt->delete()):
            return $this->exito('El componente se elimino satisfactoriamente');
        endif;

       return redirect('/');
    }

    public function recordSale()
    {
        return view('general.processedProduct.reporte-sale');
    }


    public function reportPdf()
    {
        $date = $this->convertionObjeto();

        Session::put('dateIn', $date->dateInCookedProduct);
        Session::put('dateOut', $date->dateOutCookedProduct);
        return $this->exito("Se lograron filtrar los datos para el reporte");

    }

    public function salePdf()
    {
        $inDate = Session::get('dateIn');
        $OutDate = Session::get('dateOut');
        $dateIn = new Carbon($inDate);
        $dateOut = new Carbon($OutDate);

        $pdf  = Fpdf::AddPage('P','letter');
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,8,utf8_decode(userSchool()->name),0,1,'C');
        $pdf .= Fpdf::Cell(0,8,'HISTORIAL DE VENTAS',0,1,'C');
        $pdf .= Fpdf::Cell(0,8,$dateIn->format('Y-m-d').'  a  '.$dateOut->format('Y-m-d'),0,1,'C');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','B',14);
        $pdf .= Fpdf::Cell(0,10,'Productos  ',0,0,'C');
        $pdf .= Fpdf::Cell(0,10,'Cocidos',0,0,'C');
        $pdf .= Fpdf::Cell(0,10,'  Vendidos',0,1,'C');

        $cokeeds = $this->processedProductRepository->orderByFilterSchool('name','ASC');

        $i= 0;
        $total=0;
        $counts=0;
        foreach($cokeeds AS $cokeed):
            $count = $this->cookedProductInvoiceRepository->saleXMenuRestauran([$dateIn->format('Y-m-d'),$dateOut->format('Y-m-d')],$cokeed->id);


            if($count>0): $i++;
            $pdf .= Fpdf::SetFont('Arial','I',12);
            $pdf .= Fpdf::Cell(10,10,utf8_decode($i.'.-'),0,0,'L');
            $pdf .= Fpdf::Cell(82,10,utf8_decode(ucwords(strtolower($cokeed->name))),0,0,'L');
            $pdf .= Fpdf::Cell(15,10,number_format($count,0),0,0,'L');
            $pdf .= Fpdf::Cell(40,10,number_format($count*$cokeed->price,2),0,1,'L');
            $total += $count*$cokeed->price;
                $counts +=$count;
            endif;
        endforeach;
        $service = $this->invoiceRepository->invoiceDate($dateIn->format('Y-m-d'),$dateOut->format('Y-m-d'),'service');
        $iva = $this->invoiceRepository->invoiceDate($dateIn->format('Y-m-d'),$dateOut->format('Y-m-d'),'tax');
        $pdf .= Fpdf::SetFont('Arial','BI',14);
        $pdf .= Fpdf::Cell(85,10,utf8_decode('Total Venta'),0,0,'R');
        $pdf .= Fpdf::Cell(20,10,number_format($counts,0),0,0,'C');
        $pdf .= Fpdf::Cell(40,10,number_format($total,2),0,1,'L');
        $pdf .= Fpdf::Cell(85,10,utf8_decode('Total Impuestro de Ventas'),0,0,'R');
        $pdf .= Fpdf::Cell(20,10,number_format($iva,0),0,1,'C');
        $pdf .= Fpdf::Cell(85,10,utf8_decode('Total Impuestro de Servicio'),0,0,'R');
        $pdf .= Fpdf::Cell(20,10,number_format($service,0),0,1,'C');




        $pdf .= Fpdf::Ln();
      /*  $pdf .= Fpdf::Cell(0,10,'Productos cocidos No Vendidos',0,1,'C');

        $cokeeds = $this->cookedProductRepository->orderByFilterSchool('name','ASC');
        $i= 0;
        $total=0;
        $counts=0;
        foreach($cokeeds AS $cokeed): $i++;
            $count = $this->menuRestaurantCookedProductRepository->saleXMenuRestauran([$dateIn,$dateOut],'amount','cooked_product_id',$cokeed->id);
            if($count <=0):
                $pdf .= Fpdf::SetFont('Arial','I',12);
                $pdf .= Fpdf::Cell(10,10,utf8_decode($i.'.-'),0,0,'L');
                $pdf .= Fpdf::Cell(82,10,utf8_decode(ucwords(strtolower($cokeed->name))),0,0,'L');
                $pdf .= Fpdf::Cell(15,10,number_format($count,0),0,0,'L');
                $pdf .= Fpdf::Cell(40,10,number_format($count*$cokeed->price,2),0,1,'L');
                $total += $count*$cokeed->price;
                $counts +=$count;
            endif;
        endforeach;
        $pdf .= Fpdf::SetFont('Arial','BI',14);
        $pdf .= Fpdf::Cell(85,10,utf8_decode('Total'),0,0,'R');
        $pdf .= Fpdf::Cell(20,10,number_format($counts,0),0,0,'C');
        $pdf .= Fpdf::Cell(40,10,number_format($total,2),0,1,'L');
        $pdf .= Fpdf::Ln();*/

        Fpdf::Output('ventas de Menu Restaurante ','I');
        exit;
    }
}
