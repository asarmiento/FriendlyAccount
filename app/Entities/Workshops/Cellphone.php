<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 24/02/2017
 * Time: 03:35 PM
 */

namespace AccountHon\Entities\Workshops;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\Brand;
use AccountHon\Entities\General\Customer;

class Cellphone extends Entity
{
    protected $fillable = ['cost','description','priority','serie','elaborate_work','damage','password','deu_date','date_of_receipt',
    'warranty_date','date_of_delivery','brand_id','school_id','customer_id','modelWorkshop_id','authorized',
        'authorizedSign','equipment','color','otherType','charger','chargerSeries','case','physicalSigns','recommendations',
        'additionalRequests','reportedProblems','firm','token','diagnosis','work_done','answer_used','delivered','final_cost'];
    public function getRules()
    {
        return [];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        return $rules;// TODO: Implement getUnique() method.
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::getClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::getClass());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modelWorkshop()
    {
        return $this->belongsTo(ModelWorkshop::getClass(),'modelWorkshop_id','id');
    }

}