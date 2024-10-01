<?php

namespace AccountHon\Entities;



use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Entity
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'style', 'note',
        'type', 'level', 'school_id','permission',
        'catalog_id','token', 'user_created', 'user_updated'];

    public function getRules()
    {
        return [
            'code'=> 'required',
            'name'=> 'required',
            'style'=> 'required',
            'note'=> 'required',
            'type'=> 'required',
            'level'=> 'required',
            'school_id'=> 'required',
            'token'=> 'required',
        ];// TODO: Implement getRules() method.
    }

    public function getUnique($rules, $datos)
    {
        // TODO: Implement getUnique() method.
    }

    public function catalogs(){
        return $this->belongsTo(Catalog::getClass());
    }
    public function parentCatalog(){
        return $this->belongsTo(Catalog::getClass(),'catalog_id','id');
    }
    public function childCatalogs(){
        return $this->hasMany(Catalog::getClass());
    }

    public function schools(){
        return $this->belongsTo(School::getClass());
    }


    public function codeName(){
        return $this->code.' - '.$this->name;
    }


}
