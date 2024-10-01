<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 14/02/2017
 * Time: 06:59 AM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\Receipt;
use AccountHon\Repositories\BaseRepository;

class ReceiptRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new Receipt();// TODO: Implement getModel() method.
    }
}