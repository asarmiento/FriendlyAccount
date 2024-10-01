<?php

namespace AccountHon\Http\Controllers\ReportExcel;
use AccountHon\Entities\AccountingPeriod;
use AccountHon\Http\Controllers\ReportExcelController;
use AccountHon\Traits\Convert;
use Codedge\Fpdf\Facades\Fpdf;
use Maatwebsite\Excel\Facades\Excel;
use Session;

/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 04/07/2015
 * Time: 10:49 PM
 */

class EstadoDeCuenta extends ReportExcelController {

    use Convert;
    /**
     * @param $token
     */
    public function estadoCuenta($token){


        $school = $this->schoolsRepository->oneWhere('id',userSchool()->id,'id');
        /** @var TYPE_NAME $catalog */
        $catalog = $this->catalogRepository->token($token);
        $seatings= $this->seatingRepository->whereDuo('catalog_id',$catalog->id,'status','aplicado','id','ASC');
        $headers = array(
            array($school[0]->name),
            array('ESTADO DE CUENTA'),
            array($catalog->code.' '.$catalog->name),
            array(''),
            array('Fecha','Descripción','Referencia','debito','credito')
        );


        $content = $headers;

        $countHeader = count($headers);
        foreach($seatings AS $seating):

            if($seating->types->name == 'debito'):
                $content[] = array($seating->date,$seating->detail,$seating->code,$seating->amount,'');
            elseif($seating->types->name == 'credito'):
                $content[] = array($seating->date,$seating->detail,$seating->code,'',$seating->amount);
            endif;
        endforeach;
        $seatings= $this->seatingRepository->listsWhereDuo('catalog_id',$catalog->id,'status','aplicado','id');
      
        $seatingParts = $this->seatingPartRepository->getModel()->where('catalog_id',$catalog->id)->where('status','Aplicado')->get();

        foreach($seatingParts AS $seatingPart):
    
                if($seatingPart->types->name =='credito'):  
                    $content[] = array($seatingPart->date,$seatingPart->detail,$seatingPart->code,'',$seatingPart->amount);
               
                elseif($seatingPart->types->name =='debito'):
                    $content[] = array($seatingPart->date,$seatingPart->detail,$seatingPart->code,$seatingPart->amount,'');
                endif;
            
           /* */
            endforeach;
     
        $countContent = count($content);
     
        $content[] = array('','Fecha de Impresión: '.date('d-m-Y'),'Saldo ',$this->saldoEstadoCuenta($catalog));
        $countFooter = count($content);
       //
        Excel::create(date('d-m-Y').'-'.$catalog->name, function($excel) use ($catalog,$content,$countHeader,$countContent,$countFooter) {
            $excel->sheet($catalog->code, function($sheet)  use ($content,$countHeader,$countContent,$countFooter) {
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');
                $sheet->mergeCells('D'.$countFooter.':E'.$countFooter);
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
                $sheet->cells('A5:E5', function($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A'.$countFooter.':E'.$countFooter, function($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->fromArray($content, null, 'A1', true,false);
            });
        })->export('xlsx');

    }

    /**
     * @param $catalog
     * @return mixed
     */
    private function saldoEstadoCuenta($catalog){

        $debito = $this->nameType('debito');
        $credito = $this->nameType('credito');
      
        $seatingsdebito= $this->seatingRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$debito->id)
            ->where('status','aplicado')
            ->sum('amount');
        $seatingsPartdebito= $this->seatingPartRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$debito->id)
            ->where('status','aplicado')
            ->sum('amount');   
      
       $seatingsPartcredito= $this->seatingPartRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$credito->id)
            ->where('status','aplicado')
            ->sum('amount');

        $seatingscredito= $this->seatingRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$credito->id)
            ->where('status','aplicado')
            ->sum('amount');    

        $total = ($seatingsdebito+$seatingsPartdebito)- ($seatingscredito+$seatingsPartcredito);

        return $total;
    }

    public function estadoCuentaMonthPost(){
        $datosCatalogs = $this->convertionObjeto();

        $catalog = $this->catalogRepository->token($datosCatalogs->nameCatalogs);
        $in = $this->accountingPeriodRepository->token($datosCatalogs->monthInCatalogs);
        $out = $this->accountingPeriodRepository->token($datosCatalogs->monthOutCatalogs);
        $period = $this->accountingPeriodRepository->listAccountPeriod($in,$out);
        Session::put('catalog', $catalog);
        Session::put('in', $in);
        Session::put('out', $out);
        Session::put('period', $period);
        return $this->exito("Se lograron filtrar los datos para el reporte");
    }    

    public function estadoCuentaMonth(){

        $datosCatalogs = $this->convertionObjeto();
        $school = $this->schoolsRepository->oneWhere('id',userSchool()->id,'id');
        /** @var TYPE_NAME $catalog */
        $catalog = Session::get('catalog');
        $in = Session::get('in');
        $out = Session::get('out');
        $period = Session::get('period');
        $month= $in->month;
        $year = $in->year;
        $old = $this->accountingPeriodRepository->getModel()->where('school_id',userSchool()->id)
            ->where('month','<',$month)->where('year','<=',$year)->lists('id');

        if($month =='01'):
            $month = 12;
        $year = $in->year-1;
            $old = $this->accountingPeriodRepository->getModel()->where('school_id',userSchool()->id)
                ->where('month','<=',$month)->where('year','<=',$year)->lists('id');
        endif;

        $seatings= $this->seatingRepository->whereInSelect('accounting_period_id',$period,'catalog_id',$catalog->id,'status','aplicado','id','ASC');

        $pdf  = Fpdf::AddPage('P','letter');
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,8,utf8_decode(userSchool()->name),0,1,'C');
        $pdf .= Fpdf::Cell(0,8,'ESTADO DE CUENTA',0,1,'C');
        $pdf .= Fpdf::Cell(0,8,utf8_decode($catalog->code.' '.$catalog->name),0,1,'C');
        $pdf .= Fpdf::Cell(0,8,$in->month.'-'.$in->year.'  a  '.$out->month.'-'.$out->year,0,1,'C');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::Line(5,Fpdf::GetY(),200,Fpdf::GetY());
        $pdf .= Fpdf::Cell(25,10,'Fecha',0,0,'C');
        $pdf .= Fpdf::Cell(82,10,utf8_decode('Descripción'),0,0,'C');
        $pdf .= Fpdf::Cell(30,10,'Referencia',0,0,'C');
        $pdf .= Fpdf::Cell(30,10,'debito',0,0,'C');
        $pdf .= Fpdf::Cell(30,10,'credito',0,1,'C');
        $pdf .= Fpdf::Line(5,Fpdf::GetY(),200,Fpdf::GetY());

        $periodBefore = $this->accountingPeriodRepository->beforePeriod($in->period);

        if($periodBefore):
            $beforePeriod = $periodBefore->id;
        else:
            $beforePeriod = -1;
        endif;
         $balanceInitial = $this->balancePeriodRepository->balanceIntial($beforePeriod,$catalog->id);

        $pdf .= Fpdf::Cell(137,7,'Saldo Inicial',0,0,'C');
        if($balanceInitial >= 0):
            $pdf .= Fpdf::Cell(30,7,number_format($balanceInitial,2),0,1,'C');
        else:
            $pdf .= Fpdf::Cell(25,7,'',0,0,'C');
            $pdf .= Fpdf::Cell(30,7,number_format($balanceInitial,2),0,1,'C');
        endif;
        $total = 0;

        foreach($seatings AS $seating):
            $pdf .= Fpdf::SetFont('Arial','I',12);
            if($seating->types->name=='debito'):
                $pdf .= Fpdf::Cell(25,7,$seating->date,0,0,'C');
                $pdf .= Fpdf::Cell(82,7,utf8_decode(substr($seating->detail,0,38)),0,0,'L');
                $pdf .= Fpdf::Cell(30,7,$seating->code,0,0,'C');
                $pdf .= Fpdf::Cell(30,7,$seating->amount,0,0,'C');
                $pdf .= Fpdf::Cell(30,7,'',0,1,'C');
                $total += $seating->amount;
           else:
               $pdf .= Fpdf::Cell(25,7,$seating->date,0,0,'C');
               $pdf .= Fpdf::Cell(82,7,utf8_decode(substr($seating->detail,0,38)),0,0,'L');
               $pdf .= Fpdf::Cell(30,7,$seating->code,0,0,'C');
               $pdf .= Fpdf::Cell(30,7,'',0,0,'C');
               $pdf .= Fpdf::Cell(30,7,$seating->amount,0,1,'C');
               $total -= $seating->amount;
            endif;

        endforeach;

       // $seatings= $this->seatingRepository->listsWhereDuo('catalog_id',$catalog->id,'status','aplicado','id');

        $seatingParts = $this->seatingPartRepository->getModel()->whereIn('accounting_period_id',$period)
            ->where('catalog_id',$catalog->id)->where('status','Aplicado')->get();

        foreach($seatingParts AS $seatingPart):
            $pdf .= Fpdf::SetFont('Arial','I',12);
            if($seatingPart->types->name =='debito'):
                $pdf .= Fpdf::Cell(25,5,$seatingPart->date,0,0,'C');
                $pdf .= Fpdf::Cell(82,5,utf8_decode(substr($seatingPart->detail,0,38)),0,0,'L');
                $pdf .= Fpdf::Cell(30,5,$seatingPart->code,0,0,'C');
                $pdf .= Fpdf::Cell(30,5,$seatingPart->amount,0,0,'C');
                $pdf .= Fpdf::Cell(30,5,'',0,1,'C');
                $total += $seatingPart->amount;
            else:
                $pdf .= Fpdf::Cell(25,5,$seatingPart->date,0,0,'C');
                $pdf .= Fpdf::Cell(82,5,utf8_decode(substr($seatingPart->detail,0,38)),0,0,'L');
                $pdf .= Fpdf::Cell(30,5,$seatingPart->code,0,0,'C');
                $pdf .= Fpdf::Cell(30,5,'',0,0,'C');
                $pdf .= Fpdf::Cell(30,5,$seatingPart->amount,0,1,'C');
                $total -= $seatingPart->amount;
            endif;

            /* */
        endforeach;
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::Line(5,Fpdf::GetY(),200,Fpdf::GetY());
        $pdf .= Fpdf::Cell(120,10,utf8_decode('Fecha de Impresión: '.date('d-m-Y')),0,0,'C');
        $pdf .= Fpdf::Cell(15,10,'Saldo ',0,0,'C');
        $pdf .= Fpdf::Cell(30,10,number_format($total+$balanceInitial,2),0,1,'C');
        $pdf .= Fpdf::Line(5,Fpdf::GetY(),200,Fpdf::GetY());
        $pdf .= Fpdf::Ln();

        Fpdf::Output('Estado de Cuenta '.$catalog->name.'-'.$in->month.'-'.$in->year.'  a  '.$out->month.'-'.$out->year,'I');
        exit;
    }

    /**
     * @param $catalog
     * @return mixed
     */
    private function monthEstadoCuenta($catalog){

        $debito = $this->nameType('debito');
        $credito = $this->nameType('credito');

        $seatingsdebito= $this->seatingRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$debito->id)
            ->where('status','aplicado')
            ->sum('amount');
        $seatingsPartdebito= $this->seatingPartRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$debito->id)
            ->where('status','aplicado')
            ->sum('amount');

        $seatingsPartcredito= $this->seatingPartRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$credito->id)
            ->where('status','aplicado')
            ->sum('amount');

        $seatingscredito= $this->seatingRepository->getModel()
            ->where('catalog_id',$catalog->id)
            ->where('type_id',$credito->id)
            ->where('status','aplicado')
            ->sum('amount');

        $total = ($seatingsdebito+$seatingsPartdebito)- ($seatingscredito+$seatingsPartcredito);

        return $total;
    }

  
}