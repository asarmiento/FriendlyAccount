<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\ClosingCashDesk;
use AccountHon\Repositories\BaseRepository;

class ClosingCashDeskRepository extends BaseRepository {
    
    public function getModel() {
        return new ClosingCashDesk();
    }
}
