<?php

namespace AccountHon\Repositories\Accounting;

use AccountHon\Entities\Accounting\Supplier;
use AccountHon\Repositories\BaseRepository;

class SupplierRepository extends BaseRepository
{

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new Supplier();// TODO: Implement getModel() method.
    }
}