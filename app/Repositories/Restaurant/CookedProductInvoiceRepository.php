<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 31/12/15
 * Time: 03:02 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\CookedProductInvoice;
use AccountHon\Repositories\BaseRepository;

class CookedProductInvoiceRepository extends  BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new CookedProductInvoice();// TODO: Implement getModel() method.
    }

    public function saleXMenuRestauran($date,$id)
    {
        return $this->newQuery()->whereHas('invoice',function ($q) use($date){
            $q->whereBetween('date',$date);
        })->where('cooked_product_id',$id)->sum('amount');
    }
}