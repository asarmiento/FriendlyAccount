<?php

namespace AccountHon\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;

class TypeSeat extends Entity
{
    use SoftDeletes;
    public $timestamps = true;
    // Don't forget to fill this array
    protected $fillable = ['abbreviation', 'name','quatity', 'year', 'school_id', 'token'];


    public function getRules()
    {
        return ['abbreviation' => 'required',
            'name' => 'required',
            'quatity' => 'required',
            'year' => 'required',
            'school_id' => 'required',
            'token' => 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($data, $datos)
    {
        $data['name'] .= ',name,' . $datos->id;// TODO: Implement getUnique() method.
        return $data;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seatings() {
        return $this->hasMany(Seating::getClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schools(){
        return $this->belongsTo(School::getClass(),'school_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templateSeatings() {
        return $this->hasMany(TemplateSeating::getClass());
    }

    /**
     * @return string
     */
    public function abbreviation(){
        return $this->abbreviation.'-'.$this->quatity;
    }

}
