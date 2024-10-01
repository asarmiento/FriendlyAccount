<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 26/06/2015
 * Time: 12:36 AM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\AuxiliaryReceipt;

class AuxiliaryReceiptRepository extends BaseRepository {

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new AuxiliaryReceipt();
    }
    public function updateWhere($token, $status,$data){
        $cambio= $this->newQuery()->where('token', $token)->update([$data=>$status]);
        return $cambio;
    }
    public function whereSelect($data,$id,$order,$dataTwo, $idTwo){
        return $this->newQuery()->where($dataTwo, $idTwo)->where($data, $id)->orderBy($order,'DESC')->get();
    }
    public function allToken($token){
        return $this->newQuery()->where('token', $token)->get();

    }
    public function allId($id){
        return $this->newQuery()->where('id', $id)->get();

    }
    public function updateDataWhere($upData, $token,$data, $status,$type){
        return $this->newQuery()->where($upData, $token)->where('type_seat_id', $type)->update([$data=>$status]);

    }
    public function sumToken($token){
        return $this->newQuery()->where('token', $token)->sum('amount');

    }
    public function sumId($token){
        return $this->newQuery()->where('id', $token)->sum('amount');

    }
    public function whereDuoFirst($column1, $filter1, $column2, $filter2, $column3, $array){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->first();
    }
    public function whereDuoLast($column1, $filter1, $column2, $filter2, $column3, $array){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->get()->last();
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
    public function dataCourCase($financialRecords_ids)
    {
        return $this->newQuery()->whereIn('financial_record_id',$financialRecords_ids)->where('status','aplicado')->where('court_case_id',null)->orderBy('date','DESC');
    }
}