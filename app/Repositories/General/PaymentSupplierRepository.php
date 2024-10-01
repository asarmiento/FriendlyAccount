<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 23/11/16
 * Time: 09:28
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\PaymentSupplier;
use AccountHon\Repositories\BaseRepository;

class PaymentSupplierRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new PaymentSupplier(); // TODO: Implement getModel() method.
    }


    public function invoiceCourtSum( $param)
    {
        return $this->newQuery()->whereHas('supplier',function ($q){
            $q->where('school_id',userSchool()->id);
            })->where('status',0)->sum($param);
    }

    public function invoiceCourtCase()
    {
        return $this->getModel()->whereHas('supplier',function ($q){
            $q->where('school_id',userSchool()->id);
        })->update(array('status' => true));
    }
}