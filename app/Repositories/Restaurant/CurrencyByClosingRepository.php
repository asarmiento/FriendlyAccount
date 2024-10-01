<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\CurrencyByClosing;
use AccountHon\Repositories\BaseRepository;

class CurrencyByClosingRepository extends BaseRepository {
    
    public function getModel() {
        return new CurrencyByClosing();
    }
}
