<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 9/5/17
 * Time: 16:58
 */

namespace AccountHon\Entities\Restaurant;


use AccountHon\Entities\Entity;

class InvoicesService extends Entity
{
    protected $table ='invoice_services';

    protected $fillable =['number','customer','card','email','amount','user_id','date'];

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
