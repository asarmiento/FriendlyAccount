<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Entity {

    use SoftDeletes;

    // Don't forget to fill this array
    protected $fillable = ['name'];

    public function Users() {
        return $this->belongsToMany(User::getClass())->withPivot('status');
    }

    public function menus() {
        return $this->belongsToMany(Menu::getClass())->withPivot('status');
    }

    public function getRules()
    {
       return ['name' => 'required']; // TODO: Implement getRules() method.
    }

    public function getUnique($data, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
