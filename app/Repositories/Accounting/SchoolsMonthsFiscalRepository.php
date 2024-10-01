<?php
namespace AccountHon\Repositories\Accounting;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use AccountHon\Entities\SchoolsMonthsFiscal;
use AccountHon\Repositories\BaseRepository;

/**
 * Description of SchoolMonthFiscalRepository
 *
 * @author Anwar Sarmiento
 */
class SchoolsMonthsFiscalRepository extends BaseRepository
{

    /**
     * @return SchoolsMonthsFiscal|mixed
     */
    public function getModel() {
        return new SchoolsMonthsFiscal();
    }

    /**
     * @param $year
     * @return mixed
     */
    public function lastFiscal($year){
        return $this->newQuery()->where('year',$year)->where('school_id',userSchool()->id)->first();
    }
}
