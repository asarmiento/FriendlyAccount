<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 04:42 PM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class RawProductInvoice extends Entity
{

    protected $table = 'rawproduct_invoices';

    protected $fillable =['invoice_id','raw_product_id','amount','price','discount','units'];

    public function getRules()
    {
        return [
            'invoice_id'=>'required',
            'raw_product_id'=>'required',
            'amount'=>'required',
            'price'=>'required',
            'discount'=>'required'
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function rawProducts()
    {
        return $this->belongsTo(RawMaterial::getClass(),'raw_product_id','id');
    }

    public function sumaInv()
    {
        return $this->sum('amount');
    }

}