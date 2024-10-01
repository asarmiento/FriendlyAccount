<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 02/01/16
 * Time: 10:14 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\Recipe;
use AccountHon\Repositories\BaseRepository;

class RecipeRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new Recipe();// TODO: Implement getModel() method.
    }
}