<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 21/08/16
 * Time: 10:47 PM
 */

namespace AccountHon\Repositories\General;


use AccountHon\Entities\General\EmployesSchedule;
use AccountHon\Repositories\BaseRepository;

class EmployesScheduleRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new EmployesSchedule(); // TODO: Implement getModel() method.
    }
}