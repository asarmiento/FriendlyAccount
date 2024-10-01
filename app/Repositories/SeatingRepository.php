<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 02/07/2015
 * Time: 11:44 AM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\Seating;

class SeatingRepository extends BaseRepository {


    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var SeatingPartRepository
     */
    private $seatingPartRepository;

    public function __construct(
        TypeFormRepository $typeFormRepository,
        SeatingPartRepository $seatingPartRepository
    )
    {


        $this->typeFormRepository = $typeFormRepository;
        $this->seatingPartRepository = $seatingPartRepository;
    }
    /**
     * @return mixed
     */
    public function getModel()
    {
        return new Seating();
    }
    public function updateWhere($token, $status,$data){
        $cambio= $this->newQuery()->where('token', $token)->update([$data=>$status]);
        return $cambio;
    }
    public function whereSelect($data,$id,$order,$dataTwo, $idTwo){
        return $this->newQuery()->where($dataTwo, $idTwo)->where($data, $id)->orderBy($order,'DESC')->get();
    }

    public function whereInSelect($data,$id, $dataOne, $idOne,$dataTwo, $idTwo,$order,$type){
        return $this->newQuery()->where($dataOne, $idOne)->where($dataTwo, $idTwo)
            ->whereIn($data, $id)->orderBy($order,$type)->get();
    }

    public function catalogPeriod($catalog,$period,$type){
        
        return $this->newQuery()->where('type_id',$type)
            ->where('accounting_period_id',$period)
            ->where('status','Aplicado')
            ->where('catalog_id',$catalog)->sum('amount');
    }

    public function listsWhereDuo($column, $data,$column1, $data1, $keyList){
        return $this->newQuery()->where($column, $data)->where($column1, $data1)->lists($keyList);
    }

    public function catalogPartPeriod($catalog,$period,$type){

        return $this->newQuery()->where('type_id','<>' ,$type)
            ->where('accounting_period_id',$period)
            ->where('status','Aplicado')
            ->where('catalogPart_id',$catalog)->sum('amount');
    }

    public function amountSeating($code, $typeSeat){
        return $this->newQuery()->where('code',$code)->where('type_seat_id',$typeSeat)->sum('amount');

    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com  
    * @Create: 05/08/16 01:36 AM   @Update 0000-00-00
    ***************************************************
    * @Description:
    *
    *
    *
    * @Pasos:
    *
    *
    * @return amount
    ***************************************************/
    public function balanceCatalogoType($catalog,$period,$type)
    {

        $balanceDebit= $this->catalogInPeriod($catalog,$period,$type);
        $balancePartDebit = $this->seatingPartRepository->catalogInPeriod($catalog,$period,$type);
         return (($balanceDebit+$balancePartDebit));

    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 05/08/16 01:43 AM   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @param $catalog
     * @param $period
     * @return
     **************************************************/
    public function balanceCatalogoInPeriod($catalog,$period)
    {
        $debit = $this->typeFormRepository->nameType('debito');
        $credit = $this->typeFormRepository->nameType('credito');

        $balanceDebit= $this->catalogInPeriod($catalog,$period,$debit);
        $balancePartDebit = $this->seatingPartRepository->catalogInPeriod($catalog,$period,$debit);
        $balanceCredit= $this->catalogInPeriod($catalog,$period,$credit);
        $balancePartCredit = $this->seatingPartRepository->catalogInPeriod($catalog,$period,$credit);
        return (($balanceDebit+$balancePartDebit)-($balancePartCredit+$balanceCredit));

    }
    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-25
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: Con este metodo traemos el saldo de la cuenta en ambas
    | tablas asientos y asientos part, y devolvemos el saldo segun el
    | periodo consultado.
    |@Pasos:
    |----------------------------------------------------------------------
    | @return amount
    |----------------------------------------------------------------------
    */
    public function balanceCatalogoPeriod($catalog,$period)
    {
        $debit = $this->typeFormRepository->nameType('debito');
        $credit = $this->typeFormRepository->nameType('credito');

        $balanceDebit= $this->catalogPeriod($catalog,$period,$debit);
        $balancePartDebit = $this->seatingPartRepository->catalogPeriod($catalog,$period,$debit);
        $balanceCredit= $this->catalogPeriod($catalog,$period,$credit);
        $balancePartCredit = $this->seatingPartRepository->catalogPeriod($catalog,$period,$credit);
        return (($balanceDebit+$balancePartDebit)-($balancePartCredit+$balanceCredit));

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
}