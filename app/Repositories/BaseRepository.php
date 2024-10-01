<?php

namespace AccountHon\Repositories;

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of BaseRepository
 *
 * @author Anwar Sarmiento
 */
abstract class BaseRepository {


    /**
     * @return mixed
     */
    abstract public function getModel();

    public function __construct(){

    }

    public function token($token) {
        $consults = $this->newQuery()->where('token', $token)->get();
        if ($consults):
            foreach ($consults as $consult):
                return $consult;
            endforeach;
        endif;

        return false;
    }

    public function newQuery() {
        return $this->getModel()->newQuery();
    }

    public function findOrFail($id) {
        return $this->newQuery()->findOrFail($id);
    }

    public function withTrashedOrderBy($data, $type) {
        return $this->newQuery()->withTrashed()->orderBy($data, $type)->get();
    }

    public function onlyTrashedFind($id) {
        return $this->newQuery()->onlyTrashed()->find($id);
    }

    public function withTrashedFind($id) {
        return $this->newQuery()->withTrashed()->find($id);
    }

    public function destroy($id) {
        return $this->getModel()->destroy($id);
    }

    public function find($id) {
        return $this->newQuery()->find($id);
    }

    public function detach(){
        return $this->detach();
    }

    public function attach($id, $array){
        return $this->attach($id, $array);
    }


    public function lists($keyList){
        return $this->newQuery()->where('school_id', userSchool()->id)->lists($keyList);
    }
    public function listsOrder($keyList,$orderB){
        return $this->newQuery()->where('school_id', userSchool()->id)->orderBy($orderB,'DESC')->lists($keyList);
    }

    public function listsWhere($column, $filter, $keyList){
        return $this->newQuery()->where('school_id', userSchool()->id)->where($column, $filter)->lists($keyList);
    }

    public function listsRange($array, $filter, $keyList){
        return $this->newQuery()->where('school_id', userSchool()->id)
            ->whereBetween($filter, array($array[0], $array[1]))
            ->lists($keyList);
    }

    public function whereList($column1, $filter, $keyList){
        return $this->newQuery()->where($column1, $filter)->lists($keyList);
    }

    public function whereDuoList($column1, $filter1, $column2, $filter2, $keyList){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->lists($keyList);
    }

    public function whereInList($column1, $array, $keyList){
        return $this->newQuery()->whereIn($column1, $array)->lists($keyList);
    }
    /**/
    public function whereDuoInList($column, $array, $column1, $filter1,$keyList){
        return $this->newQuery()->whereIn($column, $array)->where($column1, $filter1)->lists($keyList);
    }
    public function whereDuoInListDistinct($colum1, $filter1, $column2, $filter2, $column3, $array, $keyList){
        return $this->newQuery()->where($colum1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->distinct()->lists($keyList);
    }

    public function first(){
        return $this->newQuery()->first();
    }

    public function last(){
        return $this->newQuery()->orderBy('id', 'desc')->first();
    }
    /* Obtenemos el ultimo registro de la institución*/
    public function lastFilterSchool(){
        return $this->newQuery()->where('school_id', userSchool()->id)->orderBy('id', 'desc')->first();
    }

    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-28
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: En esta seccion pondremos todas las consulta que traen
    |como resultado una suma.
    |
    |@Type:
    | 1. Consulta a un Where y un WhereIn
    | 2. Consulta WhereIn filtro por institución. Una consulta.
    |
    |
    |
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function sumInOne($campo1, $array){
        return $this->newQuery()->whereIn($campo1, $array)->sum('amount');
    }
    public function sumInDuo($campo1, $array,$campo, $data){
        return $this->newQuery()->where($campo, $data)->whereIn($campo1, $array)->sum('amount');
    }
    public function sumInTree($campo1, $array,$campo, $data,$campo2, $data1){
        return $this->newQuery()->where($campo2, $data1)->where($campo, $data)->whereIn($campo1, $array)->sum('amount');
    }
    public function sumInSchool($column1, $filter1){
        return $this->newQuery()->where('school_id', userSchool()->id)->whereIn($column1, $filter1)->sum('amount');
    }
    public function sumAllData() {
        return $this->newQuery()->sum('amount');
    }
    public function sumSchoolData($campo, $data) {
        return $this->newQuery()->where('school_id', userSchool()->id)->where($campo, $data)->sum('amount');
    }

    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-10-21
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Consultas que retornan todos los datos de la tabla a
    |   consultar
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
   
    public function where($column, $filter){
        return $this->newQuery()->where($column, $filter)->get();
    }

    public function whereTwo($column1, $filter1, $column2, $filter2){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->get();
    }


    public function whereNot($column1, $filter1){
        return $this->newQuery()->where($column1, '<>', $filter1)->get();
    }

    public function whereNotIn($column1, $filter1){
        return $this->newQuery()->whereNotIn($column1, '<>', $filter1)->get();
    }

    public function whereIn($column1, $filter, $column2, $array){
        return $this->newQuery()->where($column1, $filter)->whereIn($column2, $array)->get();
    }
    public function whereInOrder($column1, $filter, $column2, $array){
        return $this->newQuery()->where($column1, $filter)->whereIn($column2, $array)->orderBy('id','DESC')->limit(600)->get();
    }

    public function whereInSingle($column2, $array){
        return $this->newQuery()->whereIn($column2, $array)->get();
    }

    public function whereDuoIn($column1, $filter1, $column2, $filter2, $column3, $array){
        return $this->newQuery()->where($column1, $filter1)->where($column2, $filter2)->whereIn($column3, $array)->get();
    }

    public function orderBy($data, $type){
        return $this->newQuery()->orderBy($data, $type)->get();
    }

    public function orderByFilterSchool($data, $type){
        return $this->newQuery()->where('school_id',userSchool()->id)->orderBy($data, $type)->get();
    }

    public function whereInSchool($column2, $array){
        return $this->newQuery()->where('school_id',userSchool()->id)->whereIn($column2, $array)->get();
    }

    public function whereDuoInSchool( $column2, $filter2, $column3, $array){
        return $this->newQuery()->where('school_id',userSchool()->id)->where($column2, $filter2)->whereIn($column3, $array)->get();
    }

    public function whereAllType($column, $filter){
        return $this->newQuery()->where('school_id', userSchool()->id)->whereIn($column, $filter);
    }
    public function all() {
        return $this->newQuery()->get();
    }

    public function allFilterScholl(){
        return $this->newQuery()->where('school_id', userSchool()->id)->get();
    }

    public function allInstitution($dataOne, $id) {
        return $this->newQuery()->where('school_id',userSchool()->id)->where($dataOne, $id)->get();
    }
    
    public function oneWhere($data, $id,$order){
        return $this->newQuery()->where($data, $id)->orderBy($order,'DESC')->get();
    }

    public function oneWhereAsc($data, $id, $order){
        return $this->newQuery()->where($data, $id)->orderBy($order,'ASC')->get();
    }

    public function whereDuo($dataOne,$id,$dataTwo, $idTwo,$data,$style){
        try {
            return $this->newQuery()->where($dataOne, $id)->where($dataTwo, $idTwo)->orderBy($data, $style)->get();
        }catch(\Illuminate\Database\QueryException $e){
            return ['errors'=>true,'message'=>$e];
        }
    }

    public function whereDuoFilterSchool($dataOne,$id,$dataTwo, $idTwo,$data,$style){
        try {
            return $this->newQuery()->where('school_id',userSchool()->id)->where($dataOne, $id)->where($dataTwo, $idTwo)->orderBy($data, $style)->get();
        }catch(\Illuminate\Database\QueryException $e){
            return ['errors'=>true,'message'=>$e];
        }
    }
}