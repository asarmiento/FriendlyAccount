<?php
/**
 * Created by PhpStorm.
 * User: acondarcoi
 * Date: 2/2/16
 * Time: 7:18 PM
 */

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\MenuRestaurantCookedProduct;
use AccountHon\Repositories\BaseRepository;

class MenuRestaurantCookedProductRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return new MenuRestaurantCookedProduct();// TODO: Implement getModel() method.
    }

    public function saleXMenuRestauran($date,$result,$campo,$id)
    {

        return $this->newQuery()->where($campo,$id)->whereBetween('created_at',$date)->sum($result);
    }
}