<?php

namespace AccountHon\Repositories;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use AccountHon\Repositories\BaseRepository;
use AccountHon\Entities\Cost;
use Illuminate\Support\Facades\Log;

/**
 * Description of CostRepository
 *
 * @author Anwar Sarmiento
 */
class CostRepository extends BaseRepository {
    /**
     * @var DegreesRepository
     */
    private $degreesRepository;
    /**
     * @var DegreeSchoolRepository
     */
    private $degreeSchoolRepository;

    /**
     * @param DegreesRepository $degreesRepository
     * @param DegreeSchoolRepository $degreeSchoolRepository
     */
    public function __construct(
        DegreesRepository $degreesRepository,
        DegreeSchoolRepository $degreeSchoolRepository
    ){

        $this->degreesRepository = $degreesRepository;
        $this->degreeSchoolRepository = $degreeSchoolRepository;
    }
    public function getModel() {
        return new Cost();
    }

    public function idCostDegree($token){
        $degrees = $this->degreesRepository->token($token);
        Log::debug(json_encode($degrees).' cambio dad');
        $degreeSchool = $this->degreeSchoolRepository->whereDuoData($degrees->id, userSchool()->id);
        Log::debug(json_encode($degreeSchool).' cambio dad1');
        $costs = $this->getModel()->where('degree_school_id',$degreeSchool[0]->id)->where('year',periodSchool()->year)->get();
        Log::debug(json_encode($costs).' cambio dad2');
        if($costs->isEmpty()){
            return false;
        }else{
            return $costs[0];
        }
    }

    public function costSchool(){
      return $this->newQuery()->join('degree_school','degree_school.id','=','degree_school_id')->where('school_id',userSchool()->id)->get();

    }
}
