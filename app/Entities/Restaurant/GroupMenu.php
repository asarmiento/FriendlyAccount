<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/02/16
 * Time: 09:15 PM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class GroupMenu extends Entity
{
    protected $fillable = ['name','school_id','token'];

    public function getRules()
    {
        return ['school_id'=>'required',
            'token'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
       return $rules; // TODO: Implement getUnique() method.
    }

    public function menus(){
        return $this->hasMany(MenuRestaurant::getClass(), 'group_menu_id', 'id');
    }
}