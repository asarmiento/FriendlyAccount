<?php

namespace AccountHon\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

class TypeForm extends Entity {

    use SoftDeletes;
    protected $table = 'types';
    protected $fillable = ['name', 'token'];

    public function getRules()
    {
        return ['name' => 'required|unique:types'];// TODO: Implement getRules() method.
    }

    public function getUnique($data, $datos)
    {
        $data['name'] .= ',name,' . $datos->id;// TODO: Implement getUnique() method.

        return $data;
    }

    public function notes() {
        return $this->hasMany(Note::getClass());
    }

    public function auxiliarySeats() {
        return $this->hasMany(AuxiliarySeat::getClass());
    }

    public function templateAuxiliarySeats() {
        return $this->hasMany(TemplateAuxiliarySeat::getClass());
    }

    public function auxiliaryReceipts() {
        return $this->hasMany(AuxiliaryReceipt::getClass());
    }

    public function seatings() {
        return $this->hasMany(Seating::getClass());
    }

    public function templateSeatings() {
        return $this->hasMany(TemplateSeating::getClass());
    }

    public function accountingReceipts() {
        return $this->hasMany(AuxiliaryReceipt::getClass());
    }

	public function nameType($name)
	{
		$type= $this->where('name',$name)->get();

		return $type[0]->id;
	}

}
