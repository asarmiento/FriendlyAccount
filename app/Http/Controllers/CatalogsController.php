<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\Catalog;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class CatalogsController extends Controller
{
    use Convert;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;

    /**
     * @param CatalogRepository $catalogRepository
     * @param TypeFormRepository $typeFormRepository
     * @param AccountingPeriodRepository $accountingPeriodRepository
     */
    public function __construct(
        CatalogRepository $catalogRepository,
        TypeFormRepository $typeFormRepository,
        AccountingPeriodRepository $accountingPeriodRepository
    ){

        $this->catalogRepository = $catalogRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $catalogs = $this->catalogRepository->oneWhere('school_id',userSchool()->id,'code');
        return View('catalogs.index',compact('catalogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $catalogs = $this->catalogRepository->oneWhere('school_id',userSchool()->id,'style');
        $types = $this->typeFormRepository->getModel()->all();
        $levels = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',1)->orderBy('code','ASC')->get();
        return View('catalogs.create',compact('catalogs','types','levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            #convertimos a objeto los datos
            $catalogs = $this->convertionObjeto();
            #Verificamos y seperamos la cocatenacion y generamos el token
            $datosVerificados = $this->CreacionArray($catalogs, 'Catalog');
            DB::beginTransaction();
            #buscamos los datos de la cuenta madre con el token
            $catalogMother = $this->catalogRepository->token($catalogs->groupCatalog);
            if(!$catalogMother):
                \Log::error("error en la clase: ".__CLASS__." método ".__METHOD__." - store.".__LINE__.' Resultado de Consulta'.$catalogMother);
                return $this->errores(['errorSave' => 'La Compra no se puede Generar por que no Existe la cuenta']);
            endif;

            #generamos el codigo
            $code = $this->createCode($catalogs->levelCatalog, $catalogMother);
            #comprobamos y cargamos note
            $datosVerificados['note'] = 'false';
            if($catalogs->noteCatalog):
                $datosVerificados['note'] = 'true';
            endif;

            $datosVerificados['style'] = 'Detalle';
            if($catalogs->styleCatalog=='G'):
                $datosVerificados['style'] = 'Grupo';
            endif;
            # rellenamos las variables faltantes
            $datosVerificados['catalog_id'] = $catalogMother->id;
            $datosVerificados['type'] = $catalogMother->type;
            $datosVerificados['school_id'] = userSchool()->id;
            $datosVerificados['user_created'] = currentUser()->id;
            $datosVerificados['code'] = $code;
            $catalog = $this->catalogRepository->getModel();
            if ($catalog->isValid($datosVerificados)):
                $catalog->fill($datosVerificados);
                $catalog->save();
                if($catalog->type==5):
                    $this->createCodeBuy($catalog);
                endif;
                DB::commit();
            else:
                return $this->errores($catalog->errors);
                DB::rollback();
            endif;

            return $this->exito('Se ha generado con exito la nueva cuenta!!!');
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('catalogo  Save' => 'Verificar la información de la cuenta, sino contactarse con soporte de la applicación'));
        }
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-07
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta accion crearemos la cuenta por pagar de los
    |   proveedores para utilizarla en el formulario de compras al registrar
    |   una compra al credito.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    private function createCodeBuy($datosVerificados)
    {
        $catalogCtaxPagar = $this->catalogRepository->accountNameSchool('PROVEEDORES POR PAGAR');
        $code = $this->createCode(4, $catalogCtaxPagar[0]);
        $data = [
            'code'=>$code,
            'name'=>'CTA x PAGAR DE '.$datosVerificados->name,
            'style'=>'Detalle',
            'note'=>false,
            'type'=>2,
            'level'=>4,
            'school_id'=>userSchool()->id,
            'permission'=>'locked',
            'catalog_id'=>$catalogCtaxPagar[0]->id,
            'token'=>bcrypt($datosVerificados->name),
            'user_created'=>currentUser()->id
        ];

        $catalog = $this->catalogRepository->getModel();
        if ($catalog->isValid($data)):
            $catalog->fill($data);
            $catalog->save();
            DB::commit();
        else:
            echo json_encode($catalog->errors);
            die;
            return $this->errores($catalog->errors);
            DB::rollback();
        endif;
    }
    /**
     * @param $catalog
     * @return string
     */
    public function createCode($catalog,$catalogId){

        switch($catalog){
            case 2:
                $separaCode = substr($catalogId->code,0,2);
                $codeNew = $this->countCode($catalogId->id,$catalog);
                $codeComplent = $separaCode.'-'.$codeNew.'-00-00-000';
                return $codeComplent;
                break;
            case 3:
                $separaCode = substr($catalogId->code,0,5);
                $codeNew = $this->countCode($catalogId->id,$catalog);
                $codeComplent = $separaCode.'-'.$codeNew.'-00-000';
                return $codeComplent;
                break;
            case 4:
                $separaCode = substr($catalogId->code,0,8);
                $codeNew = $this->countCode($catalogId->id,$catalog);
                $codeComplent = $separaCode.'-'.$codeNew.'-000';
                return $codeComplent;
                break;
            case 5:
                $separaCode = substr($catalogId->code,0,11);
                $codeNew = $this->countCode($catalogId->id,$catalog);
                $codeComplent = $separaCode.'-'.$codeNew;
                return $codeComplent;
                break;
        }
    }


    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-01-18
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |@param $level
    |@param $id
    |----------------------------------------------------------------------
    | @return string
    |----------------------------------------------------------------------
    */
    public function countCode($id,$level){

        $catalog= $this->catalogRepository->oneWhere('catalog_id',$id,'code');
        #comprobamos si ya exixten relaciones con este id
        if($catalog->isEmpty()):
            #si esta en blanco inicia el conteo
            $numero = '01';
            #si esta en el level 5 agrega un cero mas
            if($level==5):
                $numero = '0'.$numero;
            endif;
        else:
            #si existe ya relaciones suma 1 para seguir el conteo
            $numero =  $catalog->count()+1;

            if($numero < 10):
                $numero = '0'.$numero;
            endif;

            if($level==5):
                if($numero < 100):
                    $numero = '0'.$numero;
                endif;
            endif;
        endif;
        return $numero;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function level(Request $request)
    {
        $level= $this->convertionObjeto();
        $nivel = ($level->level-1);

        $levels = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)
        ->where('style','Grupo')
        ->where('level',$nivel)->orderBy('code','ASC')->get();
        return View('catalogs.level',compact('levels'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($token)
    {
        $catalog = $this->catalogRepository->token($token);
        return View('catalogs.edit',compact('catalog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
        $data= $this->convertionObjeto();
        $catalog = $this->catalogRepository->getModel()
        ->where('token',$data->token)
        ->update(['name'=>strtoupper($data->nameCatalog),'note'=>$data->noteCatalog]);

        if($catalog>0){
            return $this->exito('Se Actualizo con exito!!!');
        }
        return $this->errores(['error'=>'Se genero un error favor revise los datos, oh contacte a soporte']);

    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-21
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
    public function estadoCuenta()
    {
        $periods = $this->accountingPeriodRepository->oneWhere('school_id',userSchool()->id,'month');
        $catalogs = $this->catalogRepository->whereDuo('school_id',userSchool()->id,'style','Detalle','code','ASC');
        return view('catalogs.estadoCuenta',compact('periods','catalogs'));
    }

}
