<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 30/06/2015
 * Time: 11:18 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\Catalog;

class CatalogRepository extends BaseRepository {

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new Catalog();
    }

    public function accountSchool(){
        return $this->whereDuo('school_id',userSchool()->id,'style','Detalle','id','ASC');

    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: con esta consulta traemos los datos de una cuenta buscada
    |   por el nombre y la institucion a la que pertenese
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */

    public function accountNameSchool($name){

       $group=  $this->whereDuo('school_id',userSchool()->id,'name',$name,'id','ASC');

        if($group->isEmpty()):
            return "no hay registro";
        endif;
        return $this->oneWhere('catalog_id',$group[0]->id,'name');

    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-30
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: con esta consulta traemos los datos de una cuenta buscada
    |   por el nombre y la institucion a la que pertenese
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */

    public function accountNameSingleSchool($name){

        return $this->whereDuo('school_id',userSchool()->id,'name',$name,'id','ASC');
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-13
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: con esta consulta traemos los datos de una cuenta buscada
    |   por el codigo y la institucion a la que pertenese
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */

    public function accountCodeSchool($code){

        return $this->whereDuo('school_id',userSchool()->id,'code',$code,'id','ASC');

    }
    public function code($code) {
        $consults = $this->newQuery()->where('school_id',userSchool()->id)->where('code', $code)->get();
        if ($consults):
            foreach ($consults as $consult):
                return $consult;
            endforeach;
        endif;

        return false;
    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-22
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: con esta consulta traemos los datos de una cuenta buscada
    |   por el nombre y la institucion a la que pertenese
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */

    public function accountIncome(){

        return $this->newQuery()->where('school_id',userSchool()->id)
            ->where('style','Detalle')
            ->where('type' ,'>=',1)->orderby('code','AS')->get();
    }

    public function balanceAccount($code)
    {
        return $this->newQuery()->where('code',$code)->where('school_id',userSchool()->id)->lists('id');
    }
}