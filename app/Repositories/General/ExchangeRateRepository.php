<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 24/01/2017
 * Time: 11:05 AM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\ExchangeRate;
use AccountHon\Repositories\BaseRepository;

class ExchangeRateRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new ExchangeRate();// TODO: Implement getModel() method.
    }
}