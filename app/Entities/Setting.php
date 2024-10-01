<?php

namespace AccountHon\Entities;


class setting extends Entity
{

    protected $fillable = ['catalog_id', 'type_seat_id','token'];

    public function getRules()
    {
        return [
            'catalog_id' => 'required',
            'token' => 'required',
            'type_seat_id' => 'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function catalogs() {
        return $this->belongsTo(Catalog::getClass(),'catalog_id','id');
    }

    public function typeSeat() {
        return $this->belongsTo(TypeSeat::getClass(),'type_seat_id','id');
    }




}
