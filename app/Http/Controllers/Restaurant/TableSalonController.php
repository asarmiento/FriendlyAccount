<?php

namespace AccountHon\Http\Controllers\Restaurant;

use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\TableSalonRepository;
use AccountHon\Traits\Convert;

class TableSalonController extends Controller
{
    use Convert;
    /**
     * @var BrandRepository
     */
    private $tableSalonRepository;

    /**
     * BrandController constructor.
     * @param BrandRepository $brandRepository
     */
    public function __construct(
        TableSalonRepository $tableSalonRepository
    )
    {
        $this->tableSalonRepository = $tableSalonRepository;
    }

    public function index()
    {
        $tables = $this->tableSalonRepository->allFilterScholl();
        return view('restaurant.tableSalon.index', compact('tables'));
    }

    public function create()
    {
        return view('restaurant.tableSalon.create');
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: ${DATE} ${TIME}   @Update 0000-00-00
    ***************************************************
    * @Description: Se utiliza en index de salon.
    *
    *
    *
    * @Pasos:
    *
    *
    * @if (${TYPE_HINT} != "void") * @return ${TYPE_TAG} ${TYPE_HINT}
    ***************************************************/
    public function createRestaurant()
    {
        return view('restaurant.tableSalon.createRestaurant');
    }

    /**
     * @return array
     */
    public function store()
    {
        $tableSalon = $this->convertionObjeto();
        \Log::info(json_encode($tableSalon));
        $dataTableSalon = $this->CreacionArray($tableSalon, 'TableSalon');
        if($tableSalon->barraTableSalon == true):
            $dataTableSalon['barra'] = 0 ;
        else:
            $dataTableSalon['barra'] = 1;
        endif;
        \Log::info('Array para guardar mesas'.json_encode($dataTableSalon));
        $table = $this->tableSalonRepository->consultTableSoftDelete($dataTableSalon)->get();

        \Log::info(json_encode($table));
        if(!$table->isEmpty()):

            $this->tableSalonRepository->consultTableSoftDelete($dataTableSalon)->restore();
            return ['success'=>'5','url'=>$table[0]->token];
        endif;

        $tables = $this->tableSalonRepository->getModel();
        \Log::info(json_encode($dataTableSalon));
        if($tables->isValid($dataTableSalon)):
            $tables->fill($dataTableSalon);
            $tables->save();
            \Log::info("final".json_encode($tables->restaurant));

               return $this->exito('Se Guardo con exito la mesa: '.$tables->name);


        endif;

        return $this->errores($tables->errors);
    }
    
    public function edit($token)
    {
        $table = $this->tableSalonRepository->token($token);

        return view('restaurant.tableSalon.edit', compact('table'));
    }

    public function update()
    {
        $tableSalon = $this->convertionObjeto();

        $dataTableSalon = $this->CreacionArray($tableSalon,'TableSalon','True');
        
        $tables = $this->tableSalonRepository->token($dataTableSalon['token']);

        $name_before = $tables->name;

        //if($tables->isValid($dataTableSalon)):
            $tables->fill($dataTableSalon);
        //dd($tables);
            $tables->update();
            return $this->exito('Se Actualizo con exito la Mesa: Antes:'.$name_before);
       // endif;

     //   return $this->errores($tables->errors);
    }
}