<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\Currency;
use AccountHon\Repositories\BaseRepository;

class CurrencyRepository extends BaseRepository {
    
    public function getModel() {
        return new Currency();
    }
}
