<?php

namespace AccountHon\Entities\Accounting;

use AccountHon\Entities\Catalog;
use AccountHon\Entities\Entity;
use Illuminate\Database\Eloquent\Model;

class AccountControl extends Entity
{
    protected $table = 'account_controls';

    protected $fillable = ['name', 'auxiliary_table_name', 'catalog_id', 'user_id'];
    //
    public function getRules()
    {
        // TODO: Implement getRules() method.
    }


    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::getClass());
    }
}
