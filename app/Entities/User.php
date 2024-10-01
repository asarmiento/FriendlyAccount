<?php

namespace AccountHon\Entities;

use AccountHon\Entities\General\CashDesk;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class User extends Entity implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'last', 'email', 'password', 'type_user_id', 'token'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     *  Inicio Relaciones.
     */
    /* Relacion con la tabla Tipo de usuarios */

    public function typeUsers() {
        return $this->belongsTo(TypeUser::getClass(), 'type_user_id', 'id');
    }

    public function tasks() {
        return $this->belongsToMany(Task::getClass(),'task_user','user_id','task_id')->withPivot('status', 'menu_id');
    }

    public function menus() {
        return $this->belongsToMany(Menu::getClass(), 'task_user')->withPivot('status', 'task_id')->orderBy('priority', 'asc')->orderBy('name', 'asc');
    }

    /* Relacion con la tabla schools */

    public function schools() {
        return $this->belongsToMany(School::getClass());
    }

    public function cashDesk() {
        return $this->belongsToMany(CashDesk::getClass(),'user_by_cash_desks');
    }
    /**
     * Fin Relaciones.
     */

    /* creacion de string del id de schools */

    public function idSchools($schools) {
        if ($schools):
            $id = '';
            foreach ($schools as $school):
                $id .= $school->id . ',';

            endforeach;
            $id = substr($id, 0, -1);

            return $id;
        endif;

        return false;
    }

    /* creacion de string del name de schools */

    public function nameSchools($schools) {
        if ($schools):
            $name = '';
            foreach ($schools as $school):
                $name .= $school->name . ',';

            endforeach;
            $name = substr($name, 0, -1);

            return $name;
        endif;

        return false;
    }

    public function is($type) {
        return $this->typeUsers->id === $type;
    }

    public function admin() {
        return $this->typeUsers->id === 1;
    }
    public function nameComplete(){
        return $this->name.' '.$this->last;
    }

    /*public function validate($password){
        if($password == \Auth::user()->password){
            return true;
        }
        return false;
    }*/
    public function getRules()
    {
        return ['email' => 'required|unique:users',
            'name' => 'required',
            'last' => 'required',
            'password' => 'required|min:4',
            'type_user_id' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
      //z  $rules['email'] .= ',email,' . $datos->id;
        //$rules['password'] .= ',password,' . $datos->id; // TODO: Implement getUnique() method.
        return $rules;
    }
}
