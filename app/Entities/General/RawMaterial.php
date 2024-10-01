<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 08:30 PM
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;
use AccountHon\Entities\Restaurant\CookedProductInvoice;
use AccountHon\Entities\Restaurant\Invoice;
use AccountHon\Entities\Restaurant\RawProductInvoice;
use AccountHon\Entities\Restaurant\Recipe;

class RawMaterial extends Entity
{
    protected $fillable = ['code', 'description','brand_id','school_id','units','token','type','cost'];

    protected $hidden = [ 'created_at', 'updated_at'];

    public function getRules()
    {
        return ['code'=>'required',
				'description'=>'required',
                'brand_id'=>'required',
                'school_id'=>'required',
                'token'=>'required','type'=>'required'];// TODO: Implement getRules() method.
    }

    public function getUnique($data,$datos)
    {
		\Log::info($data);
      /*  $datos['code'] .= ',code,'.$data->id;
        $datos['description'] .= ',description,'.$data->id;*/
        return $data;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brands()
    {
        return $this->belongsTo(Brand::getClass(),'brand_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inventory()
    {
        return $this->hasOne(RawMaterialInventory::getClass());
    }

    public function rawProductInvoices()
    {
        return $this->belongsToMany(Invoice::getClass(),'rawProduct_invoices')->withPivot('amount','price');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rawProductsInv()
    {
        return $this->belongsTo(RawProductInvoice::getClass(),'id','raw_product_id');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Francisco Gamonal <fgamonal@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |----------------------------------------------------------------------
    | @return int
    |----------------------------------------------------------------------
    */
    public function stock()
    {
        return ($this->in() - $this->out());
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Francisco Gamonal <fgamonal@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |----------------------------------------------------------------------
    | @return int
    |----------------------------------------------------------------------
    */
    public function out()
    {
        $output_list = Invoice::where('invoices_type_id', 3)->orWhere('invoices_type_id', 2)->lists('id');
        $cookedProduct = CookedProductInvoice::whereIn('invoice_id',$output_list)->lists('cooked_product_id');
        $totalCooked = Recipe::whereIn('cooked_product_id',$cookedProduct)->where('rawProduct_id',$this->id)->sum('amount');

        $output_tot = RawProductInvoice::where('raw_product_id',$this->id)->whereIn('invoice_id',$output_list)->sum('amount');
        return $output_tot+$totalCooked;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Francisco Gamonal <fgamonal@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |----------------------------------------------------------------------
    | @return int
    |----------------------------------------------------------------------
    */
    public function in()
    {
        $input_list = Invoice::where('invoices_type_id', 1)->lists('id');
        $input_tot = RawProductInvoice::where('raw_product_id',$this->id)->whereIn('invoice_id',$input_list)->sum('amount');

        return $input_tot;
    }
}