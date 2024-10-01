<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 16/07/2015
 * Time: 11:46 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\SeatingPart;

class SeatingPartRepository extends BaseRepository {



    /**
     * @return mixed
     */
    public function getModel()
    {
        return new SeatingPart();
    }

    public function updateWhere($token, $status,$data){
        $cambio= $this->newQuery()->where('token', $token)->update([$data=>$status]);
        return $cambio;
    }
    
     public function whereSelect($data,$id,$order,$dataTwo, $idTwo){
        return $this->newQuery()->where($dataTwo, $idTwo)->where($data, $id)->orderBy($order,'DESC')->get();
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 01/08/16 07:44 AM   @Update 0000-00-00
    ***************************************************
    * @Description: Sumanos el total de una cuenta
    *   filtrado por el tipo (debito o Credito) y segun
    *   el periodo contable esta consulta se utiliza en
    *   -Cambio de periodo
    * @Pasos:
    *
    *
    * @return amount
    ***************************************************/
    public function catalogPeriod($catalog,$period,$type){
        
        return $this->newQuery()->where('type_id',$type)
            ->where('accounting_period_id',$period)
            ->where('status','Aplicado')
            ->where('catalog_id',$catalog)->sum('amount');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-11-09
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: con esta consulta enviamos a buscar todas las cuentas
    | tipo de cuenta
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function catalogInPeriod($catalog,$period,$type){

        return $this->newQuery()->where('type_id',$type)
            ->whereIn('accounting_period_id',$period)
            ->where('status','Aplicado')
            ->where('catalog_id',$catalog)->sum('amount');
    }


    public function catalogPartPeriod($catalog,$period,$type){

        return $this->newQuery()->where('type_id','<>' ,$type)
            ->where('accounting_period_id',$period)
            ->where('status','Aplicado')
            ->where('catalog_id',$catalog)->sum('amount');
    }


}