<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 04:40 PM
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;
use AccountHon\Entities\General\CashDesk;
use AccountHon\Entities\General\Customer;
use AccountHon\Entities\LawFirm\SaleOfTheFirm;

class Invoice extends Entity
{

    protected $fillable = ['date','due_date','numeration','percent_discount', 'changing',
        'invoices_type_id','payment_method_id', 'school_id', 'user_id','tax', 'paid',
        'subtotal_taxed', 'subtotal_exempt','discount', 'waiter','total','subtotal',
        'service','client','table_salon_id','token','missing','surplus', 'user_auth_id',
        'dues','colones','colones_t','tc','dolares', 'cash','card','emails','fe', 'closed_cash_desk_id'
    ];
    public function getRules()
    {
       return [
           'date'=>'required',
           'numeration'=>'required',
           'invoices_type_id'=>'required',
           'payment_method_id'=>'required',
           'school_id'=>'required',
           'user_id'=>'required',
            'total'=>'required',
           'token' => 'required'

       ]; // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function next_numeration_sale()
    {
        $last_id = $this->where('invoices_type_id', 2)->orderBy('id', 'desc')->first();
        if($last_id){
            return $last_id->numeration + 1;
        }else{
            return 1;
        }
    }

    public function tableSalon(){
        return $this->belongsTo(TableSalon::getClass(), 'table_salon_id', 'id');
    }

    public function menuRestaurant()
    {
        return $this->belongsToMany(MenuRestaurant::getClass(),'cooked_product_invoices');
    }

    public function rawProduct()
    {
        return $this->belongsToMany(RawMaterial::getClass(),'rawproduct_invoices')->withPivot('amount','price','discount');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::getClass(), 'payment_method_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(OrderSalon::getClass(), 'invoice_id', 'id');
    }

    public function orders_applied()
    {
        return $this->hasMany(OrderSalon::getClass(), 'invoice_id', 'id')->where('canceled', false);
    }

    public function customer()
    {
        return $this->belongsToMany(Customer::getClass(),'sale_of_the_firms')->withPivot('description','amount','school_id');
    }
}
