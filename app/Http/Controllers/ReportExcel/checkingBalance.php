<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 05/07/2015
 * Time: 06:16 PM
 */

namespace AccountHon\Http\Controllers\ReportExcel;


use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\BalancePeriod;
use AccountHon\Http\Controllers\ReportExcelController;
use AccountHon\Traits\Convert;
use Maatwebsite\Excel\Facades\Excel;

class checkingBalance extends ReportExcelController{

    use Convert;

    public function index(){
        $periods   = $this->accountingPeriodRepository->allFilterScholl();
        $last      = count($periods) - 1;

        $periodInitial = $periods[0]->month.'/'.$periods[0]->year;
        $periodFinal   = $periods[$last]->month.'/'.$periods[$last]->year;
         $this->actualizarSaldoBalance();
        return view('reports.checkingBalance', compact('periodInitial', 'periodFinal'));
    }


    /**
     *
     */
    public function actualizarSaldoBalance()
    {
        $catalogs = \AccountHon\Entities\Catalog::where('school_id',userSchool()->id)->get();
        if(strlen(periodSchool()->month-1)> 1) {
            if(periodSchool()->month == '1') {
                $periodo = AccountingPeriod::where('school_id', userSchool()->id)->where('month', 12)
                    ->where('year', (periodSchool()->year-1))->first();
            }else{
                $periodo = AccountingPeriod::where('school_id', userSchool()->id)->where('month', (periodSchool()->month - 1))
                    ->where('year', (periodSchool()->year))->first();
            }
        }else {
            if(periodSchool()->month == '1') {
                $periodo = AccountingPeriod::where('school_id', userSchool()->id)->where('month', 12)
                    ->where('year', (periodSchool()->year-1))->first();
            }else{
                $periodo = AccountingPeriod::where('school_id', userSchool()->id)->where('month', '0' . (periodSchool()->month - 1))
                    ->where('year', (periodSchool()->year))->first();
            }
        }
        $periods = $this->accountingPeriodRepository->listsRange(array(periodSchool()->year.periodSchool()->month,periodSchool()->year.periodSchool()->month), 'period',  'id');



        foreach ($catalogs AS $catalog){



            $inicial =  BalancePeriod::where('catalog_id',$catalog->id)->where('accounting_period_id',$periodo->id);
            if($inicial){
                $inicial = $inicial->sum('amount');
            }

            $debito =$this->saldoPeriod($catalog->id,$periods,'debito');
            $credito =$this->saldoPeriod($catalog->id,$periods,'credito');
            $balance =($inicial+$debito)-$credito;

            $bal =  BalancePeriod::where('catalog_id',$catalog->id)->where('accounting_period_id',periodSchool()->id);
            if($bal->count() > 0){
                $bal->update(['amount'=>$balance]);
            }else{
                BalancePeriod::create([
                    'amount'=>$balance, 'catalog_id'=>$catalog->id, 'accounting_period_id'=>periodSchool()->id, 'year'=>periodSchool()->year,
                    'school_id'=>userSchool()->id
                ]);
            }
        }  
    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |@Pasos:
    |
    |
    |
    |
    |
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function report($rangePeriods){ 

        set_time_limit(0);
        $range = explode('-', $rangePeriods);

        $periodInitial = $range[0];
        $periodFinal   =  $range[1];

        $month = substr($periodInitial,-2,2);
        $year = substr($periodInitial,0,4);
        $monthBefore = ($periodInitial-1);


        if($month == '01'):
            $year = substr($periodInitial,0,4);
            $monthBefore = ($year-1).'12';
        endif;
        $periods = $this->accountingPeriodRepository->listsRange(array($periodInitial,$periodFinal), 'period',  'id');
        $periodBefore = AccountingPeriod::where('school_id',userSchool()->id)->where('period',$monthBefore)->get();
        $periodBefore = $periodBefore[0];


       // $periodBefore = $this->accountingPeriodRepository->beforePeriod($monthBefore);


        $catalogs = $this->catalogRepository->getModel()->where('style','Detalle')->where('school_id',userSchool()->id)->orderBy('code','ASC')->get();


        $content = array(
            array(userSchool()->name),
            array('BALANCE DE COMPROBACIÓN'),
            array('Periodo: '.$rangePeriods),
            array(''),
            array('CODIGO','NOMBRE DE CUENTA','SALDO INICIAL','debito PERIODO','credito PERIODO','BALANCE FINAL'),
        );
        $countHeader = count($content);
        $inicial=0;  $Tinicial=0;
        $debito =0;  $Tdebito =0;
        $credito=0;  $Tcredito=0;
        $balance=0;  $Tbalance=0;

        foreach($catalogs AS $catalog):
             //$this->saldoInicialCatalog($catalog->id,$monthBefore);

          if($periodBefore):
              $ini = BalancePeriod::where('catalog_id',$catalog->id)->where('accounting_period_id',$periodBefore->id);
             // $ini = $this->balanceInitial($catalog, $periodBefore->id);
                if($ini->count() > 0){
                    $inicial = $ini->first()->amount;
                }else{
                    $inicial = '0';
                }
            else:
                $inicial = '0';
            endif;

            $debito =$this->saldoPeriod($catalog->id,$periods,'debito');
            $credito =$this->saldoPeriod($catalog->id,$periods,'credito');
            $balance =($inicial+$debito)-$credito;
            $Tinicial+= $inicial;
            $Tdebito += $debito ;
            $Tcredito+= $credito;
            $Tbalance += $balance;
            /**/
            $content[]= array($catalog->code,$catalog->name,
                $inicial,
                $debito,
                $credito,$balance);
        endforeach;
        $countContent = count($content);
        $content[]= array('','TOTAL',$Tinicial,$Tdebito,$Tcredito,$Tbalance);
        $countFooter = count($content);

        Excel::create(date('d-m-Y').'- Balance Comprobación', function($excel) use ($content,$countHeader,$countContent,$countFooter) {
            $excel->sheet('Balance Comprobación', function($sheet)  use ($content,$countHeader,$countContent,$countFooter) {
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->cell('A1', function($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A2', function($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A3', function($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cells('A'.$countHeader.':F'.$countHeader, function($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A'.$countFooter.':F'.$countFooter, function($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->fromArray($content, null, 'A1', true,false);
            });
        })->export('xlsx');
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 05/08/16 01:14 AM   @Update 0000-00-00
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
    public function saldoPeriod($catalog,$period,$type)
    {

        $debit = $this->typeFormRepository->nameType($type);
        return $this->seatingRepository->balanceCatalogoType($catalog,$period,$debit);
    }

    public function balanceInitial($catalog,$period)
    {
        return $this->balancePeriodRepository->balanceIntial($period,$catalog->id);
    }
}