<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 21/5/17
 * Time: 09:21
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\SalesSummaryForProcessedProduct;
use AccountHon\Repositories\BaseRepository;
use Carbon\Carbon;

class SalesSummaryForProcessedProductRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new SalesSummaryForProcessedProduct();// TODO: Implement getModel() method.
    }

    public  function createUpdate($productId, $amount)
    {
            SalesSummaryForProcessedProduct::create([
                    'processed_product_id'  =>$productId,
                    'amount'                =>$amount,
                    'date'                  =>Carbon::now()->format('Y-m-d'),
                    'user_id'               =>currentUser()->id
                ]);

    }
}