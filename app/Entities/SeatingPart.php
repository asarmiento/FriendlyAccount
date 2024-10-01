<?php

namespace AccountHon\Entities;




use Illuminate\Database\Eloquent\SoftDeletes;

class SeatingPart extends Entity
{
    protected  $timestamp = true;
    protected $fillable = ['code', 'detail', 'date', 'amount',
        'status', 'catalog_id', 'seating_id', 'accounting_period_id',
        'type_id','type_seat_id', 'user_created', 'user_updated', 'token'];

    use SoftDeletes;

    public function getRules()
    {
        return [
            'code'=> 'required',
            'detail'=> 'required',
            'date'=> 'required',
            'amount'=> 'required',
            'status'=> 'required',
            'catalog_id'=> 'required',
            'seating_id'=> 'required',
            'accounting_period_id'=> 'required',
            'type_id'=> 'required',
            'type_seat_id'=> 'required',
            'token'=> 'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function catalogs(){
        return $this->belongsTo(Catalog::getClass(),'catalog_id','id');
    }

    public function types(){
        return $this->belongsTo(TypeForm::getClass(),'type_id','id');
    }
    public function typeSeat(){
        return $this->belongsTo(TypeSeat::getClass(),'type_seat_id','id');
    }
    public function accountingPeriods(){
        return $this->belongsTo(AccountingPeriod::getClass(),'accounting_period_id','id');
    }

	public function catalogPeriod($catalog,$period,$type){

		return $this->where('type_id',$type)
			->where('accounting_period_id',$period)
			->where('status','Aplicado')
			->where('catalog_id',$catalog)->sum('amount');
	}
}
