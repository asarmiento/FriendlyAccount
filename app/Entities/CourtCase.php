<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCase extends Entity
{
    use SoftDeletes;

    protected $fillable = ['date', 'type_seat_id', 'token', 'abbreviation'];

    public function getRules()
    {
        return ['date' => 'required',
            'type_seat_id' => 'required',
            'token' => 'required',
            'abbreviation' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
    public function seatings(){
        return $this->hasMany(Seating::getClass(),'code','abbreviation');
    }



}
