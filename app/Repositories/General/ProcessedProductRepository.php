<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 31/12/15
 * Time: 03:02 PM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\ProcessedProduct;
use AccountHon\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessedProductRepository extends  BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new ProcessedProduct();// TODO: Implement getModel() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function countSale($id)
    {
        $month = new Carbon(periodSchool()->year."-".periodSchool()->month."-01");
        $date = [$month->format('Y-m-d'),$month->endOfMonth()->format('Y-m-d')];

        return $this->newQuery()
            ->select(DB::raw('SUM(cooked_product_invoices.amount) as total'))

            ->whereHas('invoices',function ($q) use($date) {
           $q->whereBetween('date',$date);
       })
        ->join('cooked_product_invoices','cooked_product_id','=','cooked_products.id')
            ->where('cooked_products.id',$id)->get();
    }

    public function listsPE()
    {
        return $this->newQuery()->where('school_id',userSchool()->id)
            ->orderBy('id','desc')->get();
    }
}