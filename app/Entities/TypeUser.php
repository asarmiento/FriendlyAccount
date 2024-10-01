<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class TypeUser extends Entity {
    /*
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'type_users';

    use SoftDeletes;

    public $timestamps = true;
    // Don't forget to fill this array
    protected $fillable = ['name'];

    public function users() {
        return $this->belongsTo(User::getClass());
    }


    public function getRules()
    {
        return ['name' => 'required|unique:type_users'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        $rules['name'] .= ',name,' . $datos->id;// TODO: Implement getUnique() method.
        return $rules;
    }
}
