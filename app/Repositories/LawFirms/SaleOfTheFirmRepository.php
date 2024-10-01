<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 11/12/2016
 * Time: 08:43 PM
 */

namespace AccountHon\Repositories\LawFirms;


use AccountHon\Entities\LawFirm\SaleOfTheFirm;
use AccountHon\Repositories\BaseRepository;

class SaleOfTheFirmRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new SaleOfTheFirm(); // TODO: Implement getModel() method.
    }


    public function invoicesAll()
    {
        return $this->getModel()->whereHas('customer',function ($q){
            $q->where('school_id',userSchool()->id);
        })->get();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function totalInvoicesTheSale($user)
    {
        return $this->newQuery()->where('status',0)
            ->where('user_id',$user)->sum('amount');
    }

    public function updateSaleInvoice($saleOfTheFirm,$invoices)
    {
        return $this->newQuery()->where('status',0)
            ->where('user_id',$saleOfTheFirm->user_id)
            ->update(['customer_id'=>$invoices['customer_id'],'status'=>1,
                'invoice_id'=>$saleOfTheFirm->id,'date'=>$saleOfTheFirm->date,
                'number'=>$saleOfTheFirm->numeration]);
    }


}