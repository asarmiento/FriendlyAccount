<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 07:07 PM
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Accounting\SupplierRepository;
use AccountHon\Repositories\General\BrandRepository;
use AccountHon\Repositories\General\ProcessedProductRepository;
use AccountHon\Repositories\General\RawMaterialRepository;
use AccountHon\Repositories\Restaurant\RecipeRepository;
use AccountHon\Traits\Convert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RawMaterialController extends Controller
{
    use Convert;
    /**
     * @var RawMaterialRepository
     */
    private $rawMaterialRepository;
    /**
     * @var BrandRepository
     */
    private $brandRepository;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var RecipeRepository
     */
    private $recipeRepository;
    /**
     * @var ProcessedProductRepository
     */
    private $cookedProductRepository;

    /**
     * RawMaterialController constructor.
     *
     * @param RawMaterialRepository $rawMaterialRepository
     * @param BrandRepository $brandRepository
     * @param SupplierRepository $supplierRepository
     * @param RecipeRepository $recipeRepository
     * @param ProcessedProductRepository $cookedProductRepository
     *
     * @internal param RawMaterialRepository $rawMaterialRepository
     */
    public function __construct(
        RawMaterialRepository $rawMaterialRepository,
        BrandRepository $brandRepository,
        SupplierRepository $supplierRepository,
        RecipeRepository $recipeRepository,
        ProcessedProductRepository $cookedProductRepository
    )
    {

        $this->brandRepository = $brandRepository;
        $this->supplierRepository = $supplierRepository;
        $this->recipeRepository = $recipeRepository;
        $this->cookedProductRepository = $cookedProductRepository;
        $this->rawMaterialRepository = $rawMaterialRepository;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-02-25
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
        $rawProducts = $this->rawMaterialRepository->getModel()->where('school_id',userSchool()->id)->with('brands')->get();
        return view('general.rawMaterials.index',compact('rawProducts'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-02-25
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
    public function saleRecipes()
    {
        $rawProducts = $this->rawMaterialRepository->allFilterScholl();
            $recipes= [];
            foreach($rawProducts AS $rawProduct):
                foreach($this->saleCookedProductId() AS $cookedProduct):
                    $recipe =  $this->recipeRepository->getModel()->where('cooked_product_id',$cookedProduct->id)
                        ->where('rawProduct_id',$rawProduct->id)->sum('amount');

                     if($recipe>0):
                         if(key_exists($rawProduct->id,$recipes)):
                             $recipes[$rawProduct->id] = ($recipes[$rawProduct->id]+$recipe);
                         else:
                             $recipes[$rawProduct->id] = $recipe;
                         endif;
                     endif;
                endforeach;
            endforeach;
        return $recipes;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-02-25
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
    public function saleCookedProductId()
    {
        $cookedProducts =$this->cookedProductRepository->getModel()->select('cooked_products.*')->whereHas('cookedProductInvoice',function($query)
        {
            $date = new Carbon();
            $dateOut = $date->format('Y-m-d');
            $query->whereBetween('invoices.date',[$date->subDay(7)->format('Y-m-d'),$dateOut]);
        }
        )->orderBy('id','desc')->get();

        return $cookedProducts;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
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
    public function create()
    {
        $brands = $this->brandRepository->allFilterScholl();
        $units = ['unidades'=>'Unidades','gr'=>'Gramos','lb'=>'Libras','kg'=>'Kilo Gramos','cup'=>'Tazas','tbs'=>'Cucharadas','tps'=>'Cucharaditas','ml'=>'militros','l'=>'Litros','G'=>'Galon','oz'=>'Onzas'];
        $rawProduct = $this->rawMaterialRepository->getModel()->orderBy('code','DESC')->first();
        $code = '001';
        if(is_object( $rawProduct)):
            if($rawProduct->code > 0):
                $code = 1 + $rawProduct->code ;

            endif;
        endif;

        return view('general.rawMaterials.create',compact('brands','units','code'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2016-01-12
    |---------------------------------------------------------------------
    |@Description: Con esta accion guardamos los datos del nuevo produto
    |   crudo en la base de datos, enviamos log en caso de error
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function store()
    {
        $rawProduct = $this->convertionObjeto();
        try{
            DB::beginTransaction();

        $search = $this->rawMaterialRepository->ConsultExist('code',$rawProduct->codeRawProduct);
        /**
         * Verificación de datos duplicados en el codigo del producto
         */
        if(!$search->isEmpty()):
            $this->warningLogs('Dato duplicado',__CLASS__,__FUNCTION__,__LINE__,$search);
            return $this->errores('No se Aceptan codigos duplicados favor elija otro diferente a :'.$rawProduct->codeRawProduct);
        endif;
        $search = $this->rawMaterialRepository->ConsultExist('description',$rawProduct->descriptionRawProduct);
        /**
         * Verificación de datos duplicados en el nombre del producto
         */
        if(!$search->isEmpty()):
            $this->warningLogs('Dato duplicado',__CLASS__,__FUNCTION__,__LINE__,$search);
            return $this->errores('No se Aceptan Nombres duplicados favor elija otro diferente a :'.$rawProduct->descriptionRawProduct);
        endif;


        $dataRawProduct = $this->CreacionArray($rawProduct,'RawProduct');

        $brand = $this->brandRepository->token($dataRawProduct['brand_id']);
        if(!$brand):
            $this->saveLogs('Error en las marcas',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            return $this->errores('Se genero un error con la marca que selecciono favor verifique,
            o informe al soporte tecnico <strong> soporte@sistemasamigables.com </strong> ');
        endif;
        $dataRawProduct['brand_id']= $brand->id;
        $rawProducts = $this->rawMaterialRepository->getModel();

        if($rawProducts->isValid($dataRawProduct)):
            $rawProducts->fill($dataRawProduct);
            $rawProducts->save();
            if($rawProduct->cocidoRawProduct == false):
                $cookedProduct = $this->cookedProductRepository->getModel();
                $dataRawProduct['name'] = $dataRawProduct['description'];
                $dataRawProduct['numberOfDishes']= 1;
                $dataRawProduct['type']= 'bebida';
                $dataRawProduct['token']= $dataRawProduct['token'].rand(1,9999);

                if ($cookedProduct->isValid($dataRawProduct)):
                    $cookedProduct->fill($dataRawProduct);
                    $cookedProduct->save();
                    Db::commit();
                    return $this->exito('Seguardo con Exito el Producto');
                else:
                    return $this->errores($cookedProduct->errors);
                endif;
            else:
                Db::commit();
                return $this->exito('Seguardo con Exito el Producto');
            endif;

        endif;
        $this->warningLogs('error generado',__CLASS__,__FUNCTION__,__LINE__,$rawProducts->errors);
        return $this->errores($rawProducts->errors);
        }catch (Exception $e)
        {
            Db::rollback();
            \Log::error($e);
            return $this->errores(array('task Save' => 'Verificar la información del tarea, sino contactarse con soporte de la applicación'));
        }
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Enviamos los datos a la vista de edicion
    |
    |----------------------------------------------------------------------
    | @return view
    |----------------------------------------------------------------------
    */
    public function edit($token)
    {
        $brands = $this->brandRepository->allFilterScholl();
        if(!$brands):
            $this->saveLogs('Error en las marcas',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            $this->returnAbort(503,'en la busqueda de las mascas');
        endif;
        $rawProducts = $this->rawMaterialRepository->token($token);
        if(!$rawProducts):
            $this->saveLogs('Error en las marcas',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
           $this->returnAbort(503,'en la busqueda del produto seleccionado');
        endif;
        $units = ['unidades','gr','cup','tbs','tps','ml','l','G','lb','kg','oz'];
        return view('general.rawMaterials.edit',compact('brands','rawProducts','units'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion actualizamos los datos de los productos
    |   crudos y enviamos un log en caso de error
    |----------------------------------------------------------------------
    | @return json
    |----------------------------------------------------------------------
    */
    public function update()
    {
        $rawProduct = $this->convertionObjeto();

        $dataRawProduct = $this->CreacionArray($rawProduct,'RawProduct');

        $brand = $this->brandRepository->token($dataRawProduct['brand_id']);

        if(!$brand):
            $this->saveLogs('Error en las marcas',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            return $this->returnJson('la marca enviada tiene un error');
        endif;
        $dataRawProduct['brand_id']= $brand->id;

        $rawProducts = $this->rawMaterialRepository->token($rawProduct->token);
        if(!$rawProducts):
            $this->saveLogs('Error en las marcas',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            return $this->returnJson('la producto aeditar tiene un error');
        endif;
        if($rawProducts->isValid($dataRawProduct)):
            $rawProducts->fill($dataRawProduct);
            $rawProducts->update();

            return $this->exito('Se actualizo con Exito el Producto');
        endif;

        return $this->errores($rawProducts->errors);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-24
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
    public function productDetail($token)
    {
        $product = $this->rawMaterialRepository->token($token);

        $suppliers= $this->supplierRepository->getModel()->with('brands')
            ->join('brands_suppliers','brands_suppliers.supplier_id','=','suppliers.id')
            ->where('brands_suppliers.brand_id',$product->brand_id)->get();

        return view('suppliers.supplierProduct',compact('product','suppliers'));
    }
}