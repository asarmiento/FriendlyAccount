<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/01/16
 * Time: 11:58 AM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\Customer;
use AccountHon\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new Customer();// TODO: Implement getModel() method.
    }
}