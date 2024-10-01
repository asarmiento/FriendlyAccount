<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 29/12/15
 * Time: 04:44 PM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\Invoice;
use AccountHon\Repositories\BaseRepository;

class InvoiceRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new Invoice();// TODO: Implement getModel() method.
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 11/07/16 10:58 PM   @Update 0000-00-00
    ***************************************************
    * @Description: Mandamos a sumar segun el parametro
    *   que enviemos y en el rango de fechas que se le
    *   indique, y se envia tambien si es ya con el
    *   corte de caja realizado o no.
    * @Pasos:
    * @param $date_ini
    * @param $date_end
    * @param $court
    * @param $sum
    * @return mixed
    ***************************************************/
    public function invoiceDate($date_ini, $date_end,$sum)
    {
        return $this->getModel()
            ->where('school_id',userSchool()->id)
            ->whereBetween('date',[$date_ini, $date_end])->where('status','activo')->sum($sum);
    }

    public function invoiceCourt()
    {
    	return $this->getModel()->where('court', false)
            ->where('school_id',userSchool()->id)
            ->where('status','activo')->get();

    }

    public function invoiceCourtSum($param)
    {
        return $this->getModel()->where('court', false)
            ->where('school_id',userSchool()->id)->where('status','activo')->sum($param);

    }

    public function invoiceCourtCase($id)
    {
        return $this->getModel()->where('court', false)
            ->where('school_id',userSchool()->id)
            ->update(array('court' => true,'closed_cash_desk_id'=>$id));
    }

    public function saleXMenuRestauran($id,$date,$result,$table)
    {

        return $this->newQuery()->whereHas('invoices', function($q,$id,$table){
            $q->where($table,'id',$id);
        })->whereBetween('date',$date)
            ->where('school_id',userSchool()->id)
            ->where('status','activo')->sum($result);
    }

    public function invoiceCourtSumPayment( $paymentMethod)
    {
        return $this->getModel()->where('court', false)
                                ->where('school_id',userSchool()->id)
                                ->where('status','activo')
                                ->where('payment_method_id', $paymentMethod)
                                ->sum('total');
    }

    public function numberLawFirms()
    {
        return $this->newQuery()->where('school_id',userSchool()->id)->orderBy('id','DESC')->first();
    }
}