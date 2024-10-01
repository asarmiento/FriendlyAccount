<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use AccountHon\Entities\SchoolsMonthsFiscal;

class School extends Entity {

    use SoftDeletes;

    public $timestamps = true;
    // Don't forget to fill this array
    protected $fillable = ['name', 'charter', 'route','business_name', 'phoneOne', 'phoneTwo', 'fax', 'address', 'town', 'token'];

    public function users() {
        return $this->belongsToMany(User::getClass());
    }

    public function userSchool($id) {
        return $this->belongsToMany(User::getClass(), 'school_user')->wherePivot('user_id', $id);
    }

    public function fiscal() {
        return $this->hasMany(SchoolsMonthsFiscal::getClass(), 'school_id')->orderBy('id','desc')->first();
    }

    public function degrees() {
        return $this->belongsToMany(Degree::getClass(),'degree_school')->withPivot('id')->withTimestamps();
    }

    public function isValid($data) {
        $rules = [
            'name' => 'required',
            'charter' => 'required|unique:schools',
            'phoneOne' => 'required',
            'address' => 'required',
            'town' => 'required',
            'token' => 'required|unique:schools'];

        if ($this->exists) {
            $rules['charter'] .= ',charter,' . $this->id;
            $rules['token'] .= ',token,' . $this->id;
        }

        $validator = \Validator::make($data, $rules);
        if ($validator->passes()) {
            return true;
        }

        $this->errors = $validator->errors();

        return false;
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($data, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
