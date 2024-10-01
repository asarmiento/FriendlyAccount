<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 20:54
 */

namespace AccountHon\Repositories\Accounting;


use AccountHon\Entities\Accounting\ListOfAccount;
use AccountHon\Repositories\BaseRepository;

class ListOfAccountRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new ListOfAccount();  // TODO: Implement getModel() method.
    }
}