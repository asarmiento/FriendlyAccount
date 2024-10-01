<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 03/03/2017
 * Time: 02:06 PM
 */

namespace AccountHon\Repositories\Workshops;


use AccountHon\Entities\Workshops\TechnicalCellphone;
use AccountHon\Repositories\BaseRepository;

class TechnicalCellphoneRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new TechnicalCellphone(); // TODO: Implement getModel() method.
    }
}