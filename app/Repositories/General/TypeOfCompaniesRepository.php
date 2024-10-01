<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 18:48
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\TypeOfCompany;
use AccountHon\Repositories\BaseRepository;

class TypeOfCompaniesRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new TypeOfCompany(); // TODO: Implement getModel() method.
    }
}