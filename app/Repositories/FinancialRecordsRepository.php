<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 24/06/2015
 * Time: 09:20 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\FinancialRecords;

class FinancialRecordsRepository extends BaseRepository{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new FinancialRecords();
    }

    public function saldoStudent($id){
        return $this->newQuery()->where('id',$id)->sum('balance');
    }


    public function updateData($id,$data,$balance){
        return $this->newQuery()->where('id',$id)->update([$data=>$balance]);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-09-29
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Con esta consulta obtenemos todos los alumnos que se le
    |   generaran cobro en la mensualidad
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function searchStudent($Student){
        return $this->newQuery()->whereIn('student_id', $Student)->where('retirement_date', null)->where('year', periodSchool()->year)->get();

    }

    public function listStudent(){
        return $this->newQuery()->wherehas('students', function ($q){
            $q->where('school_id', userSchool()->id);
        });
    }
}