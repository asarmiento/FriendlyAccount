<?php


namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class UserTask extends Entity
{
protected $table = 'task_user';
protected $fillable =['task_id', 'menu_id', 'user_id', 'status'];
    public $timestamps = false;
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
