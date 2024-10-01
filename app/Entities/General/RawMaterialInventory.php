<?php

namespace AccountHon\Entities\General;

use AccountHon\Entities\Entity;


class RawMaterialInventory extends Entity
{
    protected $fillable = ['id','raw_material_id','amount'];

    //
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }


}
