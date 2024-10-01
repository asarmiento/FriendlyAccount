<?php

namespace AccountHon\Repositories\General;

use AccountHon\Entities\General\Brand;
use AccountHon\Repositories\BaseRepository;

class BrandRepository extends BaseRepository {
    
    public function getModel() {
        return new Brand();
    }
}
