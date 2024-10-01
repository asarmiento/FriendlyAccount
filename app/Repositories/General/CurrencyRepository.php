<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 12/04/16
 * Time: 11:52 PM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\Restaurant\Currency;
use AccountHon\Repositories\BaseRepository;

class CurrencyRepository extends BaseRepository
{


    /**
     * @return mixed
     */
    public function getModel()
    {
        return new Currency(); // TODO: Implement getModel() method.
    }
}