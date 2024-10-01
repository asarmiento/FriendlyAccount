<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 05/07/2015
 * Time: 10:43 PM
 */

namespace AccountHon\Repositories;


use AccountHon\Entities\BalancePeriod;
use AccountHon\Repositories\SeatingPartRepository;

/**
 * @property  seatingRepository
 */
class BalancePeriodRepository extends BaseRepository{
    protected $seatingRepository;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;

    private $seatingPartRepository;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;

    /**
     * BalancePeriodRepository constructor.
     * @param SeatingRepository $seatingRepository
     * @param TypeFormRepository $typeFormRepository
     * @param \AccountHon\Repositories\SeatingPartRepository $seatingPartRepository
     * @param CatalogRepository $catalogRepository
     * @param AccountingPeriodRepository $accountingPeriodRepository
     */
    public function __construct(
            SeatingRepository $seatingRepository,
            TypeFormRepository $typeFormRepository,
            SeatingPartRepository $seatingPartRepository,
            CatalogRepository $catalogRepository,
            AccountingPeriodRepository $accountingPeriodRepository
            ){
        $this->seatingPartRepository = $seatingPartRepository;
        $this->seatingRepository = $seatingRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->catalogRepository = $catalogRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
    }
    /**
     * @return mixed
     */
    public function getModel()
    {
        return new BalancePeriod();
    }

    public function balanceIntial($period,$catalog){

        return $this->newQuery()
            ->where('catalog_id',$catalog)
            ->where('accounting_period_id',$period)->sum('amount');

    }
    public function saldoIncial($filter,$data,$catalog){

        return $this->newQuery()
            ->where('school_id',userSchool()->id)
            ->where('catalog_id',$catalog)
            ->where($filter,$data)->sum('amount');

    }

    public function saldoTotalPeriod($catalog,$period){

        $debito =   $this->balanceForPeriodCatalogId($catalog->id,$period,'debito');
        # se multiplica por -1 para convertirlo a negativo por que credito
        # es una forma de restarle del saldo que tiene
        $credito =   $this->balanceForPeriodCatalogId($catalog->id,$period,'credito') ;

        $saldo = $debito - $credito;
      /*  if($catalog->type == 4):

            $saldo = ($credito-$debito) * -1;

        elseif($catalog->type == 6):

            $saldo = $debito - $credito;
        elseif($catalog->type == 1):

            $saldo = $debito - $credito;
        elseif($catalog->type == 2):

            $saldo = ($credito-$debito) * -1;
        endif;*/

       return $saldo;
    }

    public function saldoPeriod($catalog,$period,$type){

        $saldo = $this->saldoPeriodCatalog($catalog,$period,$type);
        $saldoPart =$this->saldoPeriodCatalogPart($catalog,$period,$type);
        $total = $saldo+$saldoPart;
        return $total;
    }
    private function saldoPeriodCatalog($catalog,$periods,$type){
        $type = $this->typeFormRepository->nameType($type);
        $saldo = 0;
        foreach($periods AS $key => $period):
            $saldo += $this->seatingRepository->catalogPeriod($catalog,$period,$type);
        endforeach;
        return $saldo;
    }

    private function saldoPeriodCatalogPart($catalog,$periods,$type){
        $type = $this->typeFormRepository->nameType($type);
        $saldo = 0;
        foreach($periods AS $key => $period):
            $saldo += $this->seatingPartRepository->catalogPeriod($catalog,$period,$type);
        endforeach;
        return $saldo;
    }



    public function saldoTotalLevel($data,$value){

        $debito =   $this->saldoLevel($data,$value,'debito');
        # se multiplica por -1 para convertirlo a negativo por que credito
        # es una forma de restarle del saldo que tiene
        $credito =   $this->saldoLevel($data,$value,'credito') * -1;

        $saldo = $debito+$credito;


        return $saldo;
    }

    public function saldoLevel($data,$value,$type){

        $saldo = $this->saldoLevelSeat($data,$value,$type);
       $saldoPart =$this->saldoLevelPart($data,$value,$type);

       $total = $saldo+$saldoPart;
        return $total;
    }

    private  function saldoLevelPart($data,$value,$type)
    {
        $catalogs = $this->catalogRepository->listsWhere($data,$value,'id');
        $periods = $this->accountingPeriodRepository->lists('id');
        $type = $this->typeFormRepository->nameType($type);
        $saldo = 0;
        foreach($catalogs AS $catalog):
            $saldo += $this->seatingPartRepository->catalogInPeriod($catalog,$periods,$type);
        endforeach;
        return $saldo;
    }

    private  function saldoLevelSeat($data,$value,$type)
    {
        $catalogs = $this->catalogRepository->listsWhere($data,$value,'id');
        $periods = $this->accountingPeriodRepository->lists('id');
        $type = $this->typeFormRepository->nameType($type);
        $saldo = 0;
        foreach($catalogs AS $catalog):
            $saldo += $this->seatingRepository->catalogInPeriod($catalog,$periods,$type);
        endforeach;
        return $saldo;
    }
    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 01/08/16 07:57 AM   @Update 0000-00-00
     ***************************************************
     * @Description: Con esta accion podemos tomar el
     *   saldo total segun el id de la cuenta, el id
     *   del periodo y el id del tipo de (debito o
     *   credito)
     * @Pasos:
     *  Esta consulta se utiliza en
     *  -Cambio de periodo
     * @param $catalog
     * @param $period
     * @param $typeName
     * @return amount
     **************************************************/
   public function balanceForPeriodCatalogId($catalog,$period,$typeName){
       $type = $this->typeFormRepository->nameType($typeName);
        $saldo = $this->seatingRepository->catalogPeriod($catalog,$period,$type);
        $saldoPart =$this->seatingPartRepository->catalogPeriod($catalog,$period,$type);
        $total = $saldo+$saldoPart;
        return $total;
    }

}
