<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 11:04 AM
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Accounting\Supplier;
use AccountHon\Entities\Entity;
use AccountHon\Entities\Workshops\ModelWorkshop;
use Illuminate\Database\Eloquent\Model;

class Brand extends Entity
{

    protected  $fillable = [ 'name','token','school_id'];

    public function getRules()
    {
        return [ 'name'=>'required',
                 'token'=>'required',
                 'school_id'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        $rules['name'] .= ',name,' . $datos->id; // TODO: Implement getUnique() method.
        return $rules;
    }

    public function products()
    {
    	return $this->hasMany(RawMaterial::getClass());
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::getClass(),'brands_suppliers');
    }

    public function modelWorkshop()
    {
        return $this->hasMany(ModelWorkshop::getClass());
    }
}