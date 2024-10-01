<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 6/12/16
 * Time: 22:37
 */

namespace AccountHon\Entities\Workshops;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\Brand;

class ModelWorkshop extends Entity
{
    protected $table = "model_of_the_vehicles";
    protected $fillable =['name','brand_id','token','status','user_id'];
    public function getRules()
    {
       return ['name'=>'required','brand_id'=>'required']; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }


    public function brand()
    {
        return $this->belongsTo(Brand::getClass());
    }


}