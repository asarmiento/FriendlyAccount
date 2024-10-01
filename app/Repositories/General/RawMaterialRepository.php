<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 08:16 PM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\RawMaterial;
use AccountHon\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class RawMaterialRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new RawMaterial();// TODO: Implement getModel() method.
    }
    public function updatePrice($id,$amount){
        return $this->newQuery()->where('id',$id)->update(['cost'=>$amount]);
    }
    public function consultIndex()
    {
        return DB::table('raw_materials')
            ->select(DB::raw(
                'raw_materials.*, (SELECT name FROM brands WHERE brands.id=raw_materials.brand_id) as brand')
                )->get();
    }
    public function ConsultExist($data,$id){
        return $this->newQuery()->where($data,$id)->where('school_id',userSchool()->id)->get();
    }
}