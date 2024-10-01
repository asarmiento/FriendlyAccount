<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/02/16
 * Time: 09:26 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\GroupMenu;
use AccountHon\Repositories\BaseRepository;

class GroupMenuRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new GroupMenu(); // TODO: Implement getModel() method.
    }
}