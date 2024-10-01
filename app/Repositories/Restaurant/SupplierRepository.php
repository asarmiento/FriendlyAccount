<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\Supplier;
use AccountHon\Repositories\BaseRepository;

class SupplierRepository extends BaseRepository {
    
    public function getModel() {
        return new Supplier();
    }
}
