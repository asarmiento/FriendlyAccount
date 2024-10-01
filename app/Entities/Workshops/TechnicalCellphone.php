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

class TechnicalCellphone extends Entity
{
    protected $fillable = ['token','diagnosis','work_done','answer_used','delivered','final_cost','cellphone_id','token','recommendations'];
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
    public function cellphone()
    {
        return $this->belongsTo(Cellphone::getClass());
    }



}