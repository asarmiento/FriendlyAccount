<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 17/10/2016
 * Time: 01:26 PM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\RawMaterialInventory;
use AccountHon\Repositories\BaseRepository;

class InventoryRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new RawMaterialInventory(); // TODO: Implement getModel() method.
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-10-17 ${TIME}   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @param $product
     * @param $amount
     ****************************************************/
    public function increase($product,$amount){
       $verification = $this->getModel()->where('raw_material_id',$product)->count();

        if($verification > 0):
            $before = $this->newQuery()->where('raw_material_id',$product)->sum('amount');
            $after = $before + $amount;
            $this->getModel()->where('raw_material_id',$product)->update(['amount'=>$after]);
        else:
            $this->getModel()->create(['raw_material_id'=>$product,'amount'=>$amount]);
        endif;
    }
    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-10-17 ${TIME}   @Update 0000-00-00
     ***************************************************
     * @Description: Con esta funcion disminuimos del
     * inventario cadavez que se modifica el inventario
     *
     *
     * @Pasos:
     *
     *
     * @param $product
     * @param $amount
     ****************************************************/
    public function decrease($product,$amount){
        $verification = $this->newQuery()->where('raw_material_id',$product)->count();

        if($verification > 0):
            $before = $this->newQuery()->where('raw_material_id',$product)->sum('amount');
            $after = $before - $amount;
            $this->newQuery()->where('raw_material_id',$product)->update(['amount'=>$after]);
        else:
            RawMaterialInventory::create(['raw_material_id'=>$product,'amount'=>(0-$amount)]);
        endif;
    }
}