<?php

namespace AccountHon\Entities\Restaurant;

use AccountHon\Entities\Entity;
use AccountHon\Entities\Restaurant\OrderSalon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableSalon extends Entity
{
	protected $fillable = ['name', 'token','school_id','restaurant','barra','color'];
	use SoftDeletes;
    protected $hidden = ['created_at', 'updated_at'];


    public function getRules()
    {
    	return [
            'name' => 'required|string',
            'school_id' => 'required|numeric',
            'token' => 'required|string',
            'barra' => 'required',
            'color' => 'required'
        ];
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
        $rules['name'] .= ',name,' . $this->id;// TODO: Implement getUnique() method.
        return $rules;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OrderSalons()
    {
        return $this->hasMany(OrderSalon::getClass());
    }
    public function status()
    {
        $order = OrderSalon::where('table_salon_id', $this->id)->where('status', 'no aplicado')->first();
        if($order){
            return 'order';
        }
        $invoice = Invoice::where('table_salon_id', $this->id)->where('cash', false)->first();
        if($invoice){
            return 'cash';
        }
        return 'open';
    }
}
