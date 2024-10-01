<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\Invoice;
use AccountHon\Entities\Restaurant\OrderSalon;
use AccountHon\Entities\Restaurant\TableSalon;
use AccountHon\Repositories\BaseRepository;

class TableSalonRepository extends BaseRepository {
    
    public function getModel() {
        return new TableSalon();
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 2016-12-02   @Update 0000-00-00
    ***************************************************
    * @Description:
    *
    *
    *
    * @Pasos:
    *
    *
    ***************************************************/
    public function allTablesActive(){

        $tables =  $this->newQuery()->where('school_id',userSchool()->id)->get();
        foreach ($tables AS $table):
          $order =  OrderSalon::where('table_salon_id',$table->id)->where('status','no aplicado')->lists('id');
        endforeach;

    }

    public function consultTableSoftDelete($data)
    {
        return $this->newQuery()->withTrashed()
            ->where('name',$data['name'])
            ->where('school_id',userSchool()->id);
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 30/1/17 22:57   @Update 0000-00-00
    ***************************************************
    * @Description: esta busqueda se utiliza en
    * TableSalonController
    *
    * @Pasos:
    *
    *
    * @return
    ***************************************************/
    public function tokenTableSoftDelete($data,$type)
    {
        return $this->newQuery()
            ->where('token',$data)
            ->where('restaurant',$type)
            ->where('school_id',userSchool()->id);
    }

    public function idConsult($id)
    {
        $consults = $this->newQuery()->withTrashed()->where('school_id',userSchool()->id)->where('id', $id)->get();
        if ($consults):
            foreach ($consults as $consult):
                return $consult;
            endforeach;
        endif;

        return false;
    }

    public function base() {
        $consults = $this->newQuery()->where('school_id',userSchool()->id)->where('restaurant', 'base')->get();
        if ($consults):
            foreach ($consults as $consult):
                return $consult;
            endforeach;
        endif;

        return false;
    }

    public function express() {
        $consults = $this->newQuery()->where('school_id',userSchool()->id)->where('restaurant', 'express')->get();
        if ($consults):
            foreach ($consults as $consult):
                return $consult;
            endforeach;
        endif;

        return false;
    }

    public function changeBase($id)
    {
        $base = $this->base();
        Invoice::where('table_salon_id',$id)->update(['table_salon_id'=>$base->id]);
        OrderSalon::where('table_salon_id',$id)
            ->where('status','no aplicado')
            ->update(['table_salon_id'=>$base->id]);
       //
    }
}
