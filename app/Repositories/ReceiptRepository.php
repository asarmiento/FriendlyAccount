<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 02/07/2015
 * Time: 11:35 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\AccountingReceipt;

class ReceiptRepository extends BaseRepository{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new AccountingReceipt();
    }
    public function updateWhere($token, $status,$data){
        $cambio= $this->newQuery()->where('token', $token)->update([$data=>$status]);
        return $cambio;
    }
    public function whereSelect($data,$id,$order,$dataTwo, $idTwo){
        return $this->newQuery()->where($data, $id)->where($dataTwo, $idTwo)->orderBy($order,'DESC')->get();
    }
    public function allToken($token){
        return $this->newQuery()->where('token', $token)->orderBy('date','DESC')->get();

    }
    public function updateDataWhere($upData, $token,$data, $status,$type){
        return $this->newQuery()->where($upData, $token)->where('type_seat_id', $type)->update([$data=>$status]);

    }
    public function sumToken($token){
        return $this->newQuery()->where('token', $token)->sum('amount');

    }

    public function whereDuoFisrt($column1, $filter1, $column2, $filter2, $column3, $array){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->first();
    }

    public function whereDuoInLast($column1, $filter1, $column2, $filter2, $column3, $array){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->get()->last();
    }

    public function whereDuoInSum($column1, $filter1, $column2, $filter2, $column3, $array){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->sum('amount');
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com  
    * @Create: 17/07/16 07:16 PM   @Update 0000-00-00
    ***************************************************
    * @Description:
    *
    *
    *
    * @Pasos:
    *
    *
    * @return 
    ***************************************************/
    public function dataCourCase()
    {
       return $this->newQuery()->whereHas('catalogs',function ($q){
        $q->where('style', 'detalle')->where('school_id', userSchool()->id);
         })->where('status','aplicado')->where('court_case_id',null)->orderBy('date','DESC');
    }
}