<?php

namespace AccountHon\Entities\General;

use AccountHon\Entities\Entity;

use AccountHon\Crypt;

class UserSession extends Entity
{
    //
    public function getCode(){
    	return Crypt::decrypt($this->code);
    }

    public function getRules()
    {
        // TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }
}
