<?php

use AccountHon\Entities\FinancialRecords;
use AccountHon\Entities\TypeForm;

/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 22/06/2015
 * Time: 12:18 PM
 */


function client(){
    return "Cliente Contado";
}

function fixed($string, $options){
    return substr($string, 0, 15);
}

function currentUser()
{
    return auth()->user();
}

function convertTitle($string){

    $string = strtolower($string);

    return ucwords($string);
}

function schoolSession($school){
    \Session::put('school', $school);
}

function userSchool(){

    return \Session::get('school');
}

function actionList(){
    return 'SchoolsController@listSchools';
}

function changeLetterMonth($month){
    $very = explode(0,$month);
    \Log::info("Camvio ".json_encode($very)." cambio ".$month);
    if(strlen($month)== 2 || $very[0]== '' || $very[0]== 0 ):
        $months= ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'];
    else:
        $months= [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
    endif;
    return $months[$month];
}

function months(){
    return $months= [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
}

/**
 * @return mixed
 */
function periodSchool()
{
    if(userSchool()):
    return \AccountHon\Entities\AccountingPeriod::where('school_id',userSchool()->id)->get()->last();
    endif;
    return false;
}

function period(){
    if(periodSchool()){
        return periodSchool()->month.'-'.periodSchool()->year;
    }else{
        return "No existe periodo contable.";
    }
}

function periodBalance()
{
    if(periodSchool()){
        return periodSchool()->year.periodSchool()->month;
    }else{
        return "No existe periodo contable.";
    }
}
function dateShort()
{
    $mes_actual = date("n");
    if(!periodSchool()):
       return 'Crear el Periodo Contable';
    endif;
    $mes=periodSchool()->month;
    if($mes != $mes_actual):

        $year = periodSchool()->year;
        $dia = date("d",(mktime(0,0,0,$mes+1,1,$year)-1));

        return $salida ="$year/$mes/$dia";

    endif;
    return date("Y/m/d");
}
function type_user($type){
    switch ($type) {
        case 1:
            return 'Super Administrador';
        case 2:
            return 'Administrador';
        case 3:
            return 'Contador';
        case 4:
            return 'Cajero';
        case 5:
            return 'Mesero';
        case 6:
            return 'Cocinero';
    }
}
function taxAdd($amount)
{
    return $amount + ($amount*(userSchool()->tax_rate/100));
}
function taxLess($amount)
{
    return  ($amount / ('1.'.userSchool()->tax_rate));
}

function iva(){
    return 0.13;
}

function isv(){
    return 0.1;
}

function multipleOfFive($amount)
{
    $amounts = explode('.',$amount);
    $totalSeparate = substr($amounts[0],-1);
    \Log::info(__FUNCTION__.' '.__LINE__.' '.$amount);
    \Log::info(__FUNCTION__.' '.__LINE__.' '.$totalSeparate);
    if($totalSeparate > 0 && $totalSeparate < 5):
        $diferencia = 5 - $totalSeparate;
        $amount = $amounts[0] + $diferencia;
    elseif ($totalSeparate > 5 && $totalSeparate < 10):
        $diferencia = 10 - $totalSeparate;
        $amount = $amounts[0] + $diferencia;
    endif;
    return $amount;
}

function recursiva($code,$idAccount,$i,$j){
    if($i == $j->count()-1):
        //echo json_encode($idAccount);
        return $idAccount;
    endif;
    $catalog = \AccountHon\Entities\Catalog::where('code',$code)->first();
    // caso base son las cuentas de Detalle
    if($catalog->style == 'Detalle'):
        $list = \AccountHon\Entities\Catalog::where('catalog_id',$catalog->catalog_id)->lists('id');
        $idAccount = array_add($idAccount,$catalog->catalog_id,$list);
        $level = $catalog->parentCatalog->parentCatalog->childCatalogs;
         if($level):
            for($e=0;$e<count($level);$e++):
                if(!array_key_exists($level[$e]->id,$idAccount)):
                  return recursiva($level[$e]->code,$idAccount,$i,$j);
                endif;
            endfor;
            $i++;
            echo json_encode($idAccount);
            return recursiva($j[$i]->code,$idAccount,$i,$j);
         endif;
    endif;
    \Log::info("relacion level:".json_encode($catalog));
    $catalogId = \AccountHon\Entities\Catalog::where('catalog_id',$catalog->id)->get();
    if($i ==1 ):
    endif;
    return recursiva($catalogId[$i]->code,$idAccount,$i,$j);



}

 function balanceStudent($student)
{

    $initial = FinancialRecords::where('student_id', $student->id)
        ->where('year', (periodSchool()->year - 1))->sum('balance');

    $debito = TypeForm::where('name', 'debito')->first();
    $credito = TypeForm::where('name','credito')->first();
    $seatingsDebito =\AccountHon\Entities\AuxiliarySeat::where('financial_records_id', $student->financialRecords->id)
        ->where('type_id', $debito->id)
        ->where('status', 'aplicado')
        ->sum('amount');

    $seatingsCredito = \AccountHon\Entities\AuxiliarySeat::where('financial_records_id', $student->financialRecords->id)
        ->where('type_id', $credito->id)
        ->where('status', 'aplicado')
        ->sum('amount');

    $total = $seatingsDebito - $seatingsCredito;

    return number_format($total+$initial);

}

function recalcularSaldoStuden(){
    $Student =\AccountHon\Entities\Student::where('school_id', userSchool()->id)->lists('id');
    $finantials = FinancialRecords::whereIn('student_id',$Student)->where('year',periodSchool()->year)->lists('id');

    $period =\AccountHon\Entities\AccountingPeriod::where('school_id', userSchool()->id)->where('year',periodSchool()->year)->lists('id');

    foreach ($finantials as $finantial) {

        $seatDebito = \AccountHon\Entities\AuxiliarySeat::where('type_id',6)
            ->whereIn('accounting_period_id',$period)
            ->where('financial_records_id',$finantial)->sum('amount');
        $seatCredito = \AccountHon\Entities\AuxiliarySeat::where('type_id',7)
            ->whereIn('accounting_period_id',$period)
            ->where('financial_records_id',$finantial)->sum('amount');
        $saldo = $seatDebito-$seatCredito;

       FinancialRecords::where('id',$finantial)->update(['balance'=>$saldo]);
    }

return true;
}
