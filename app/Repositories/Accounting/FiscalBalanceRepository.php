<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/04/16
 * Time: 02:21 AM
 */

namespace AccountHon\Repositories\Accounting;


use AccountHon\Entities\Accounting\FiscalBalance;
use AccountHon\Repositories\BaseRepository;

class FiscalBalanceRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new FiscalBalance();// TODO: Implement getModel() method.
    }
}