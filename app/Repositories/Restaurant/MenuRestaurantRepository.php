<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 01/02/16
 * Time: 01:31 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\MenuRestaurant;
use AccountHon\Repositories\BaseRepository;

class MenuRestaurantRepository extends BaseRepository
{

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |@Pasos:
    |
    |
    |
    |
    |
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function getModel()
    {
       return new MenuRestaurant(); // TODO: Implement getModel() method.
    }
}