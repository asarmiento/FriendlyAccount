<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 09:04 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\InventoriesIncome;
use AccountHon\Repositories\BaseRepository;

class InventoriesIncomeRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new InventoriesIncome(); // TODO: Implement getModel() method.
    }
}