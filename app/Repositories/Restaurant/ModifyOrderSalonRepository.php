<?php

namespace AccountHon\Repositories\Restaurant;

use AccountHon\Entities\Restaurant\ModifyOrderSalon;
use AccountHon\Repositories\BaseRepository;
use DB;

class ModifyOrderSalonRepository extends BaseRepository {
    public function getModel() {
        return new ModifyOrderSalon();
    }
}
