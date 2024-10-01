<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 07/10/15
 * Time: 07:49 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\AuxiliarySupplier;

class AuxiliarySupplierRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new AuxiliarySupplier();// TODO: Implement getModel() method.
    }

    public function totalBuySupplier($id)
    {
        return $this->newQuery()->where('supplier_id',$id)->sum('amount');
    }

    public function totalDuoBuySupplier($id,$data,$parm)
    {
        return $this->newQuery()->where($data,$parm)->where('supplier_id',$id)->sum('amount');
    }
}