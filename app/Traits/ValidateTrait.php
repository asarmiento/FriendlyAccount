<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 19/5/17
 * Time: 11:34
 */

namespace AccountHon\Traits;


use AccountHon\Entities\General\Brand;
use Illuminate\Support\Facades\DB;

trait ValidateTrait
{

    public function uniqueCompany($data,$table,$column)
    {
        return DB::table($table)->where('school_id',userSchool()->id)->where($column,$data)->count();
    }
}