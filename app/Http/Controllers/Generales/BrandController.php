<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 09:41 PM
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Entities\General\Brand;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\BrandRepository;
use AccountHon\Repositories\Restaurant\SupplierRepository;
use AccountHon\Traits\Convert;
use AccountHon\Traits\ValidateTrait;

class BrandController extends Controller
{
    use Convert;
    use ValidateTrait;
    /**
     * @var BrandRepository
     */
    private $brandRepository;
    private $supplierRepository;

    /**
     * BrandController constructor.
     * @param BrandRepository $brandRepository
     */
    public function __construct(
        BrandRepository $brandRepository,
        SupplierRepository $supplierRepository
    )
    {
        $this->brandRepository = $brandRepository;
        $this->supplierRepository = $supplierRepository;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Enviamos los datos a la vista de las marcas pertenecientes
    |   a la institución.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function index()
    {
        $brands = $this->brandRepository->getModel()->with('products')
            ->where('school_id',userSchool()->id)->get();
       // echo json_encode($brands[0]->products->count()); die;
        return view('restaurant.brands.index', compact('brands'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Mostramos la cista de crear marcas para una nueva
    |----------------------------------------------------------------------
    | @return view
    |----------------------------------------------------------------------
    */
    public function create()
    {
        return view('restaurant.brands.create');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Se esta almacenando las marcas nuevas en la tabla
    |   respectiva.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function store()
    {
        $brand = $this->convertionObjeto();

        $dataBrand = $this->CreacionArray($brand,'Brand');


        if($this->uniqueCompany($dataBrand['name'],"brands",'name') > 0):
            return $this->errores("El nombre ".$dataBrand['name']." ya existe en su Base de Datos");
        endif;
        $brands = $this->brandRepository->getModel();

        if($brands->isValid($dataBrand)):
            $brands->fill($dataBrand);
            $brands->save();
            return $this->exito('Se Guardo con exito la Marca: '.$brands->name);
        endif;

        return $this->errores($brands->errors);
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Enviamos los datos a la vista de las marcas pertenecientes
    |   a la institución.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function edit($token)
    {
        $brand = $this->brandRepository->token($token);
        return view('restaurant.brands.edit', compact('brand'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-28
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Se esta actualizamos las marcas nuevas en la tabla
    |   respectiva.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function update()
    {
        $brand = $this->convertionObjeto();

        $dataBrand = $this->CreacionArray($brand,'Brand');

        $brands = $this->brandRepository->token($brand->token);

        if($brands->isValid($dataBrand)):
            $brands->fill($dataBrand);
            $brands->update();
            return $this->exito('Se Actualizo con exito la Marca: Antes:'.$brands->name);
        endif;

        return $this->errores($brands->errors);
    }

    public function suppliers($token)
    {
        $brand = $this->brandRepository->token($token);

        return view('restaurant.brands.suppliers',compact('brand'));
    }
}