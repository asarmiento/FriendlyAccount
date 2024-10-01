<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 25/02/16
 * Time: 11:43 AM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\Convertion;

use AccountHon\Repositories\BaseRepository;

class ConvertionRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new  Convertion();// TODO: Implement getModel() method.
    }

    public function dataUnits($dataIn, $dataOut)
    {
        return $this->newQuery()->where('unitsIn', $dataIn)->where('unitsOut', $dataOut)->get();

    }
}