<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 21/5/17
 * Time: 09:03
 */

namespace AccountHon\Entities\General;


use AccountHon\Entities\Entity;

class SalesSummaryForProcessedProduct extends Entity
{
    protected $fillable =['date','amount','processed_product_id','user_id'];

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}