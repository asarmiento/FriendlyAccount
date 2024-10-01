<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class Degree extends Entity
{
    use SoftDeletes;

    public $timestamps = true;
    protected $fillable = ['code','name', 'token'];

    public function getRules()
    {
        return ['name' => 'required',
            'code'=>'required',
            'token'=>'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        $rules['name'] .= ',name,' . $this->id;// TODO: Implement getUnique() method.
        return $rules;
    }
    /**
     * @return $this
     */
    public function schools() {
        return $this->belongsToMany(School::getClass())->withPivot('id','created_at','updated_at')->withTimestamps();
    }

    public function whereSchools($data,$id){

        return $this->belongsToMany(School::getClass(),'degree_school')->wherePivot($data,$id);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auxiliarySeats() {
        return $this->hasMany(AuxiliarySeat::getClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function costs() {
        return $this->belongsTo(Cost::getClass());
    }

}
