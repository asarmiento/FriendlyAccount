<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 13/07/16
 * Time: 09:27 PM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\Employes;
use AccountHon\Repositories\BaseRepository;

class EmployesRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new Employes(); // TODO: Implement getModel() method.
    }
}