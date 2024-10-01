<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 04:46 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\RawProductInvoice;
use AccountHon\Repositories\BaseRepository;

class RawProductInvoiceRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new RawProductInvoice(); // TODO: Implement getModel() method.
    }


    public function priceCost(){
        return $this->lasts('amount');
    }
}