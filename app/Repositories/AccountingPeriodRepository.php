<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 05/07/2015
 * Time: 09:58 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\AccountingPeriod;

class AccountingPeriodRepository extends BaseRepository {

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new AccountingPeriod();
    }

    public function beforePeriod($period)
    {


        $data= $this->newQuery()->where('school_id',userSchool()->id)->where('period',$period)->get();
        if($data->isEmpty()):
            return false;
        endif;
        $month = $data[0]['month']-1;
        $year= $data[0]['year'];
        if($data[0]['month'] == '01'){
            $month = 12;
            $year= $data[0]['year']-1;
        }


        if(strlen($month)==1){
            $month = '0'.$month;
        }
       
        $data= $this->newQuery()->where('school_id',userSchool()->id)->where('month',$month)->where('year',$year)->get();

        return $data[0];
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
    public function listAccountPeriod($in,$out)
    {

        return $this->newQuery()->where('school_id',userSchool()->id)->where('month','>=',$in->month)->where('year','>=',$in->year)
            ->where('month','<=',$out->month)->where('year','<=',$out->year)->lists('id');
    }

    public function lists($keyList){
        return $this->newQuery()->where('school_id', userSchool()->id)->where('year',periodSchool()->year)->lists($keyList);
    }

    public function listsFiscal($keyList,$year){
        return $this->newQuery()->where('school_id', userSchool()->id)->where('year',$year)->lists($keyList);
    }
}