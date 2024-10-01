<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 22/11/2016
 * Time: 11:12 AM
 */

namespace AccountHon\Repositories\Restaurant;


use AccountHon\Entities\Restaurant\CommissionAgent;
use AccountHon\Repositories\BaseRepository;

class CommissionAgentRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
       return new CommissionAgent(); // TODO: Implement getModel() method.
    }
}