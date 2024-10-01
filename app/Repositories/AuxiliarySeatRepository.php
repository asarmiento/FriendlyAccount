<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 24/06/2015
 * Time: 09:14 PM
 */
namespace AccountHon\Repositories;
use AccountHon\Entities\AuxiliarySeat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuxiliarySeatRepository extends BaseRepository {
    /**
     * @return mixed
     */
    public function getModel()
    {
        return new AuxiliarySeat();
    }
    public function updateWhere($token, $status,$data){
        $cambio= $this->newQuery()->where('token', $token)->update([$data=>$status]);
        return $cambio;
    }
    public function updateDataTreeWhere($data,$token,$data1, $token1,$data2, $token2,$data3,$status){
       return  $this->newQuery()->where($data, $token)->where($data1, $token1)->where($data2, $token2)->update([$data3=>$status]);
         
    }
    public function updateDataWhere($upData, $token,$data, $status){
        return $this->newQuery()->where($upData, $token)->update([$data=>$status]);

    }
    public function whereSelect($data,$id,$order,$dataTwo, $idTwo){
        return $this->newQuery()->where($dataTwo, $idTwo)->where($data, $id)->orderBy($order,'DESC')->get();
    }
    
    public function saldoStudentPeriod($idFinantial,$period,$type){
        return $this->newQuery()->where('type_id',$type)
            ->where('accounting_period_id',$period)
            ->where('financial_records_id',$idFinantial)->sum('amount');
    }

    /**
     * ---------------------------------------------------------------------
     * @Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
     * @Date Create: 2016-06-20
     * @Date Update: 2016-00-00
     * ---------------------------------------------------------------------
     * @Description: Con esta consulta creamos un saldo de los estudiantes
     * solo reciben los periodos ya sea para un saldo inicial o para un saldo
     * total.
     * @Pasos:
     *
     * ----------------------------------------------------------------------
     * @return decimal
     * ----------------------------------------------------------------------
     */
    public function saldoInicial($idFinantial,$period,$type1,$type2){
        $debito= $this->saldoStudentInPeriod($idFinantial,$period,$type1);
        $credito= $this->saldoStudentInPeriod($idFinantial,$period,$type2);

        $total = $debito - $credito;
        return $total;
    }

    public function saldoStudentInPeriod($idFinantial,$period,$type){
        return $this->newQuery()->where('type_id',$type)
            ->whereIn('accounting_period_id',$period)
            ->where('financial_records_id',$idFinantial)->sum('amount');
    }
    public function amountStudent($student,$type){
     return   $this->newQuery()
            ->where('financial_records_id',$student)
            ->where('type_id',$type)
            ->where('status','aplicado')
            ->sum('amount');
    }
    public function amountAllStudent($type,$period){
        return   $this->newQuery()
             ->where('type_id',$type)
            ->whereIn('accounting_period_id',$period)
            ->where('status','aplicado')
            ->sum('amount');
    }

    public function amountSeating($code, $typeSeat){
        return $this->newQuery()->where('code',$code)->where('type_seat_id',$typeSeat)->sum('amount');
    }

    public function amountTypeSeating($type, $typeSeat,$style){
        return $this->newQuery()->where('typeCollection',$type)
        ->where('type_id',$style)
        ->where('accounting_period_id',periodSchool()->id)
        ->where('type_seat_id',$typeSeat)->sum('amount');

    }


    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
     * @Create     : 2017-06-01
     * @Update     : 0000-00-00
     ********************************************************
     * @Description: traemos todos los asientos generados
     *             atravez de la historia
     *
     *
     * @Pasos      :
     *
     *
     *
     * @param $typeSeat
     *
     * @return mixed
     ********************************************************/
    public function allSeatAuxiliar($typeSeat)
    {
        return $this->newQuery()->with('financialRecords')->with('types')
            ->where('status', 'aplicado')->where('type_seat_id',$typeSeat->id)->orderBy('id','ASC')->get();

    }

    /*******************************************************
     * @Author     : Anwar Sarmiento Ramos
     * @Email      : asarmiento@sistemasamigables.com
     * @Create     : 2017-06-01
     * @Update     : 0000-00-00
     ********************************************************
     * @Description: traemos solo los asientos del ano actual
     *
     *
     *
     * @Pasos      :
     *
     *
     *
     * @param $typeSeat
     *
     * @return mixed
     ********************************************************/
    public function nowYearSeatAuxiliar($typeSeat)
    {
        return $this->newQuery()->getModel()->with('financialRecords')->whereHas('accountingPeriods',function ($q){
            $q->where('year',periodSchool()->year);
        })->with('types')
            ->where('status', 'aplicado')->where('type_seat_id',$typeSeat->id)->orderBy('id','ASC')->get();

    }
  public function AllYearSeatAuxiliar($typeSeat)
    {
        return $this->newQuery()->getModel()->with('financialRecords')->with('types')
            ->where('status', 'aplicado')->where('type_seat_id',$typeSeat->id)->orderBy('id','ASC')->get();

    }

    public function nowYearAllSeatAuxiliar()
    {
        return $this->newQuery()->select('date','code',DB::raw('SUM(amount) AS amount'),'types.name')
            ->whereHas('accountingPeriods',function ($q){
            $q->where('year',Carbon::now()->format('Y'))->where('school_id',userSchool()->id)->orderBy('period','ASC');
        })->join('types','types.id','=','type_id')
        ->where('status', 'aplicado')->groupBy('code')->orderBy('date','ASC')->orderBy('auxiliary_seats.updated_at','ASC')->get();

    }


}