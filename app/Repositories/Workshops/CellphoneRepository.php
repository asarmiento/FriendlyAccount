<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 03/03/2017
 * Time: 02:06 PM
 */

namespace AccountHon\Repositories\Workshops;


use AccountHon\Entities\Workshops\Cellphone;
use AccountHon\Repositories\BaseRepository;

class CellphoneRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new Cellphone(); // TODO: Implement getModel() method.
    }
}