<?php

namespace AccountHon\Entities\Accounting;


use AccountHon\Entities\AuxiliarySupplier;
use AccountHon\Entities\Entity;
use AccountHon\Entities\Restaurant\Brand;

class Supplier extends Entity
{

    protected  $fillable = [
        'code','charter','name','address', 'amount',
        'phone','token','school_id',
        'contact','phoneContact','email'];
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-12-25
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: La reglas de validación para la tabla de proveedores
    |
    |----------------------------------------------------------------------
    | @return array
    |----------------------------------------------------------------------
    */
    public function getRules()
    {
        return [
            'code'=> 'required',
            'charter'=> 'required',
            'name'=> 'required',
            'phone'=> 'required',
            'token'=> 'required',
            'school_id'=> 'required',
            'contact'=> 'required',
            'phoneContact'=> 'required',
        ];
    }

    public function getUnique($rules, $datos)
    {
       return $rules; // TODO: Implement getUnique() method.
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auxiliarySuppliers()
    {
        return $this->belongsTo(AuxiliarySupplier::getClass(),'id','supplier_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::getClass(),'brands_suppliers');
    }

}
