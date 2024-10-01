<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 04:45 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\PaymentMethod;
use AccountHon\Repositories\BaseRepository;

class PaymentMethodRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new PaymentMethod();// TODO: Implement getModel() method.
    }
}