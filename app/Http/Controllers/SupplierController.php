<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Repositories\Accounting\SupplierRepository;
use AccountHon\Repositories\AuxiliarySupplierRepository;

use AccountHon\Http\Requests;
use AccountHon\Repositories\General\BrandRepository;
use AccountHon\Traits\Convert;


class SupplierController extends Controller
{
    use Convert;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var AuxiliarySupplierRepository
     */
    private $auxiliarySupplierRepository;
    /**
     * @var BrandRepository
     */
    private $brandRepository;

    /**
     * @param \AccountHon\Repositories\Accounting\SupplierRepository $supplierRepository
     * @param \AccountHon\Repositories\AuxiliarySupplierRepository $auxiliarySupplierRepository
     * @param BrandRepository $brandRepository
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        AuxiliarySupplierRepository $auxiliarySupplierRepository,
        BrandRepository $brandRepository
    )
    {

        $this->supplierRepository = $supplierRepository;
        $this->auxiliarySupplierRepository = $auxiliarySupplierRepository;
        $this->brandRepository = $brandRepository;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-26
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion mostramos los proveedores con todos sus
    |   datos en el index
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function index()
    {
        $suppliers=$this->supplierRepository;
        $auxiliarSuppliers=$this->auxiliarySupplierRepository;
        return View('suppliers.index', compact('suppliers','auxiliarSuppliers'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-25
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion mostramos la vista de crear proveedor
    |   y enviamos el codigo del siguiente proveedor
    |----------------------------------------------------------------------
    | @return view
    |----------------------------------------------------------------------
    */
    public function create()
    {
      $amount= $this->supplierRepository->getModel()->where('school_id',userSchool()->id)->count();

        $code = '0001';
        if($amount):
            $code = '000'.($amount+1);
        endif;

        return view('suppliers.create',compact('code'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-25
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta Accion enviamos a registrar los datos de los
    |   proveedores retornamos mensaje de error o exito segun sea el caso
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function store()
    {
        $supplier = $this->convertionObjeto();

        $dataSupplier = $this->CreacionArray($supplier, 'Supplier');
        $dataSupplier['school_id']= userSchool()->id;
        $suppliers = $this->supplierRepository->getModel();
        if($suppliers->isValid($dataSupplier)):
            $suppliers->fill($dataSupplier);
            $suppliers->save();

            return $this->exito('Se Guardo Con exito el Proveedor: '.$suppliers->name);
        endif;

        return $this->errores($suppliers->errors);
    }
    /*
   |---------------------------------------------------------------------
   |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
   |@Date Create: 2015-12-27
   |@Date Update: 2015-00-00
   |---------------------------------------------------------------------
   |@Description: Con esta accion mostramos la vista de editar proveedor
   |   y enviamos el codigo del que se modifico
   |----------------------------------------------------------------------
   | @return view
   |----------------------------------------------------------------------
   */
    public function edit($token)
    {
        $supplier = $this->supplierRepository->token($token);
        return view('suppliers.edit',compact('supplier'));
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-27
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: En esta Accion enviamos a registrar los datos de los
    |   proveedores retornamos mensaje de error o exito segun sea el caso
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function update()
    {
        $supplier = $this->convertionObjeto();

        $dataSupplier = $this->CreacionArray($supplier, 'Supplier');
        $dataSupplier['school_id']= userSchool()->id;
        $suppliers = $this->supplierRepository->token($supplier->token);
        if($suppliers->isValid($dataSupplier)):
            $suppliers->fill($dataSupplier);
            $suppliers->save();

            return $this->exito('Se Guardo Con exito el Proveedor: '.$suppliers->name);
        endif;

        return $this->errores($suppliers->errors);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Francisco Gamonal <fgamonal@sistemasamigables.com
    |@Date Create: 2015-01-01
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
    public function getBrands($token)
    {
        $supplier = $this->supplierRepository->token($token);
        if(!$supplier):
            return redirect()->route('ver-proveedores')->withErrors('No existe el token');
        endif;
        $brands = $this->brandRepository->allFilterScholl();
        
        return view('suppliers.brands',compact('supplier','brands'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-01-01
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: en esta accion agregamos la nueva marca agregada al
    |   proveedor, se borrar si hubiera una relacion primero para agregarla
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function storeBrands()
    {
        $data = $this->convertionObjeto();

        $cambioArray = $this->CreacionArray($data,'Sup');
        $brand = $this->brandRepository->token($data->tokenBrand);
        $supplier = $this->supplierRepository->token($data->tokenSupplier);

        $supplier->brands()->detach($brand->id);

        if(!in_array('delete',$cambioArray)):

            $supplier->brands()->attach($supplier->id,['brand_id'=>$brand->id,'user_id'=>currentUser()->id]);
            return $this->exito('Se guardo con exito');
        endif;

        return $this->exito('Se Elimino con exito');
    }

    public function deleteBrands($token)
    {

    }
}
