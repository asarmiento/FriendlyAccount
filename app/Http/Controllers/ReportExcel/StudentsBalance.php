<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 06/07/2015
 * Time: 12:10 AM
 */

namespace AccountHon\Http\Controllers\ReportExcel;


use AccountHon\Entities\Degree;
use AccountHon\Entities\School;
use AccountHon\Entities\Student;
use AccountHon\Http\Controllers\ReportExcelController;
use AccountHon\Traits\Convert;
use Carbon\Carbon;
use Codedge\Fpdf\Facades\Fpdf;
use Maatwebsite\Excel\Facades\Excel;

class StudentsBalance extends ReportExcelController
{

    use Convert;

    public function index(){
        /**  Le quitamos el limite de tiempo de ejecusion*/
        set_time_limit(0);
        /** @var  $student Traemos todos los estudiantes de la institución */
    
        $students = $this->studentRepository->oneWhereAsc('school_id',userSchool()->id,'fname');

        /**  Creamos un Encabezado para el archivo de excel */
        $content=array(
            array(userSchool()->name),
            array('LISTA DE SALDOS DE ESTUDIANTES'),
            array('AÑO ACTUAL'.periodSchool()->year),
            array('')
        );
        $countTotal =array();
        $countGrado=array();
        $global=0;
        /** @var $school consultamos nuevamente la tabla de institucion para poder obtener las relaciones */
        $school = $this->schoolsRepository->getModel()->find(userSchool()->id);
        $periods = $this->accountingPeriodRepository->getModel()->groupBy('year')->where('school_id', userSchool()->id)->get();
        $content[]=  ['CARNET','NOMBRE ALUMNO'];
        foreach ($periods AS $period):
            array_push($content[4],'Saldo '.$period->year);
        endforeach;
        array_push($content[4],'BALANCE');
        $countHeader = count($content[4]);
            $balance = 0;
            $Totalbalance = 0;
            foreach($students AS $student):
                $listEstuden = [
                    $student->book,
                    $student->nameComplete()
                ];
                $periods = $this->accountingPeriodRepository->getModel()->groupBy('year')->where('school_id', userSchool()->id)->get();
               
                foreach ($periods AS $period):
                    $saldo = $this->financialRecordsRepository->getModel()->where('student_id',$student->id)->where('year',$period->year)->sum('balance');
                    $balance +=$saldo;
                    array_push($listEstuden,$saldo);
                endforeach;
                $countGrado[] = count($content);
                array_push($listEstuden,$balance);
                array_push($content,$listEstuden);

                $Totalbalance += $balance;
                $balance = 0;
            endforeach;
        $periods = $this->accountingPeriodRepository->getModel()->groupBy('year')->where('school_id', userSchool()->id)->get();
        $total = ['','TOTAL:'];
        $balance= 0;
        foreach ($periods AS $period):
                $balance +=$this->financialRecordsRepository->getModel()->where('year',$period->year)->sum('balance');
                array_push($total,$this->financialRecordsRepository->getModel()->where('year',$period->year)->sum('balance'));

        endforeach;
        array_push($total,$Totalbalance);
        array_push($content,$total);
        $countGrado = count($content);
        $content[]=array('','','','','','');
        Excel::create(date('d-m-Y').'- Todos los Grados', function($excel) use ($content,$countHeader,$countGrado) {
            $excel->sheet('Saldos de todos los Alumnos', function($sheet)  use ($content,$countHeader,$countGrado) {
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->setBorder('A5:F'.$countGrado, 'thin');
                $sheet->cells('A1:F5', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A'.$countGrado.':F'.$countGrado, function($cells) {
                    $cells->setFontWeight('bold');
                });
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
                $sheet->cell('A5', function($cell) {
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->fromArray($content, null, 'A1', true,false);
            });
        })->export('xlsx');
    }


    public function index3()
    {

        /**  Le quitamos el limite de tiempo de ejecusion*/
        set_time_limit(0);
        /** @var  $student Traemos todos los estudiantes de la institución */
        $students = $this->studentRepository->oneWhereAsc('school_id', userSchool()->id, 'fname');

        /**  Creamos un Encabezado para el archivo de excel */
        $content = array(
            array(userSchool()->name),
            array('LISTA DE SALDOS DE ESTUDIANTES'),
            array('AÑO ACTUAL' . periodSchool()->year),
            array('')
        );
        $countTotal = array();
        $countGrado = array();
        $global = 0;

        /** @var $school consultamos nuevamente la tabla de institucion para poder obtener las relaciones */
        $school = $this->schoolsRepository->getModel()->find(userSchool()->id);
        $periods = $this->accountingPeriodRepository->getModel()->groupBy('year')->where('school_id', userSchool()->id)->get();
        $content[] = ['CARNET', 'NOMBRE ALUMNO'];

        $countHeader = count($content[4]);
        $balance = 0;
        $Totalbalance = 0;


        $sysconf = userSchool()->id;
        
        
         $student = $this->studentRepository->oneWhere('school_id',userSchool()->id,'fname');
        /**  Creamos un Encabezado para el archivo de excel */
        $content=array(
            array(userSchool()->name),
            array('LISTA DE SALDOS DE ESTUDIANTES'),
            array('AÑO '.periodSchool()->year),
            array('')
        );
        $countTotal =array();
        $countGrado=array();
        $global=0;
        $countHeader = count($content);
        /** @var $school consultamos nuevamente la tabla de institucion para poder obtener las relaciones */
        $school = $this->schoolsRepository->getModel()->find(userSchool()->id);
        $periods = $this->accountingPeriodRepository->listsWhere('year',(periodSchool()->year-1),'id');
            foreach($school->degrees AS $degree):
        $content[]=  array('GRADO: '.$degree->name);
                $countGrado = count($content);
                $content[]=  array('CARNET','NOMBRE ALUMNO','Saldo Inical','TOTAL Debitos','TOTAL Creditos','BALANCE');
                $finantials = $this->financialRecordsRepository->oneWhere('year',periodSchool()->year,'updated_at');
                $totalI=0;
                $totalD=0;
                $totalC=0;
                $total=0;
                foreach($finantials AS $finantial):
                    if(Student::where('id',$finantial->student_id)->where('school_id',1)->count()>0){
                    if($degree->id == $finantial->degreeDatos()->id ):
                        $idFinancial = $this->financialRecordsRepository->getModel()->where('year',periodSchool()->year-1)
                            ->where('student_id',$finantial->student_id)->get();
                        if($idFinancial->isEmpty()):
                            $inicial = 0;
                        else:
                            $inicial = $this->auxiliarySeatRepository->saldoInicial($idFinancial[0]->id,$periods,6,7);
                        endif;
                        $debitos = $this->auxiliarySeatRepository->amountStudent($finantial->id,6);
                        $creditos = $this->auxiliarySeatRepository->amountStudent($finantial->id,7);
                        $balance = $inicial+$debitos-$creditos;
                        $content[]=  array($finantial->students->book,
                            $finantial->students->nameComplete(),
                            $inicial,
                               $debitos,
                            $creditos,
                            $balance);
                            $totalI +=$inicial;
                            $totalD +=$debitos;
                            $totalC +=$creditos;
                            $total +=$balance;
                    endif;
                    }
                endforeach;
                   $content[]=array('','TOTAL',$totalI,$totalD,$totalC,$total);
                $countTotal[]=count($content);
                $content[]=array('','','');
            $global +=$total;
   
            endforeach;
        $content[]=  array('ALUMNOS CON SALDOS PENDIENTES DEL AÑO ANTERIOR');
        $content[]=  array('CARNET','NOMBRE ALUMNO','TOTAL debitoS','TOTAL creditoS','BALANCE');
        $finantials = $this->financialRecordsRepository->oneWhere('year',(periodSchool()->year-1),'updated_at');
        $total=0;
        foreach($finantials AS $finantial):
             if(Student::where('id',$finantial->student_id)->where('school_id',1)->count()>0){
            if($finantial->balance > 0):
                $content[]=  array($finantial->students->book,
                    $finantial->students->nameComplete(),
                    $this->auxiliarySeatRepository->amountStudent($finantial->id,6),
                    $this->auxiliarySeatRepository->amountStudent($finantial->id,7),
                    $finantial->balance);
                $total +=$finantial->balance;
            endif;
             }
        endforeach;
           $content[]=array('','TOTAL','','',$total);
        $period = $this->accountingPeriodRepository->lists('id');
        $gDebito = $this->auxiliarySeatRepository->amountAllStudent(6,$period);
        $gCredito = $this->auxiliarySeatRepository->amountAllStudent(7,$period);
        $content[]=array('','TOTAL',$gDebito,$gCredito,
            $gDebito-$gCredito);
        $content[]=array('','','','','','');

      /*  $this->headerElectronicInvoices($sysconf);

        /// **
        // * LINE 7
        // * /
        $pdf = Fpdf::SetFont('Times', 'B', 12);
        $pdf .= Fpdf::SetTextColor(235, 237, 240);
        $pdf .= Fpdf::SetFillColor(44, 43, 124);

        $pdf .= Fpdf::SetX(5);
        $pdf .= Fpdf::Cell(25, 7, utf8_decode("Código"), 1, 0, 'C', true);
        $pdf .= Fpdf::Cell(75, 7, "Nombre Estudiante", 1, 0, 'C', true);
        foreach ($periods AS $period):
            if( $period->year > 2018 ){
            $pdf .= Fpdf::Cell(25, 7, 'Saldo ' . $period->year, 1, 0, 'C', true);}
        endforeach;
        $pdf .= Fpdf::Cell(25, 7, 'BALANCE', 1, 1, 'C', true);
        $pdf .= Fpdf::SetTextColor(0, 0, 0);
        $pdf .= Fpdf::SetFont('Times', 'I', 9);
        foreach ($students AS $student):
            $saldo = $this->financialRecordsRepository->getModel()->where('student_id', $student->id)->sum('balance');
            if ($saldo != 0) {
                $pdf .= Fpdf::SetX(5);
                $pdf .= Fpdf::Cell(25, 5, utf8_decode($student->book), 1, 0, 'C');
                $pdf .= Fpdf::Cell(75, 5, utf8_decode($student->nameComplete()), 1, 0, 'L');

                $periods = $this->accountingPeriodRepository->getModel()->groupBy('year')->where('school_id', userSchool()->id)->get();

                foreach ($periods AS $period):
                    $saldo = $this->financialRecordsRepository->getModel()->where('student_id', $student->id)->where('year', $period->year)->sum('balance');
                    $balance += $saldo;

                    if( $period->year > 2018 ) {
                         $pdf .= Fpdf::Cell(25, 5, $saldo, 1, 0, 'C');
                    }
                endforeach;
                $countGrado[] = count($content);
                $pdf .= Fpdf::Cell(25, 5, $balance, 1, 1, 'C');

                $Totalbalance += $balance;
                $balance = 0;
            }
        endforeach;
        $periods = $this->accountingPeriodRepository->getModel()->groupBy('year')->where('school_id', userSchool()->id)->get();
        $total = ['', 'TOTAL:'];

        $balance = 0;
        foreach ($periods AS $period):
            $balance += $this->financialRecordsRepository->getModel()->where('year', $period->year)->sum('balance');

         endforeach;
        $pdf .= Fpdf::SetXY(150,Fpdf::GetY()+10);
        $pdf .= Fpdf::Cell(25, 5, 'TOTAL:', 1, 0, 'R');
        $pdf .= Fpdf::Cell(25, 5, $balance, 1, 1, 'C');



        $pdf .= Fpdf::SetTextColor(0, 0, 0);
        $pdf .= Fpdf::SetFont('Times', 'I', 9);
        Fpdf::Output('I', 'Balance de Alumnos.pdf', true);
        exit;*/
       // $content[]= array('','','','','','');
        // dd($content);
        Excel::create(date('d-m-Y') . '- Todos los Grados', function ($excel) use ($content, $countHeader, $countGrado) {
            $excel->sheet('Saldos de todos los Alumnos', function ($sheet) use ($content, $countHeader, $countGrado) {
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->setBorder('A5:F'. $countGrado , 'thin');//
                $sheet->cells('A1:F5', function ($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A' . $countGrado . ':F' . $countGrado, function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cell('A1', function ($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A2', function ($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A3', function ($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cell('A5', function ($cell) {
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });


                $sheet->fromArray($content, null, 'A1', true, false);
            });
        })->export('xlsx');

    }

    public function headerElectronicInvoices($sysconf)
    {

        $pdf = Fpdf::AddPage();

        $cedula = 'Cédula: ';

        $sysconf = School::find($sysconf);
        /**
         * LINE 1
         */
        $pdf .= Fpdf::SetFont('Times', 'B', 16);
        if (strlen($sysconf->business_name) > 0) {
            $pdf .= Fpdf::Cell(0, 5, utf8_decode(substr($sysconf->business_name, 0, 37)), 0, 1, 'C');
            $pdf .= Fpdf::Cell(0, 5, utf8_decode(substr($sysconf->name, 0, 37)), 0, 1, 'C');
        } else {
            $pdf .= Fpdf::Cell(0, 5, utf8_decode(substr($sysconf->name, 0, 37)), 0, 1, 'C');

        }
        $pdf .= Fpdf::SetFont('Times', '', 9);
        $pdf .= Fpdf::Cell(0, 5, "Email: " . utf8_encode($sysconf->email) . " Tel: " . utf8_encode($sysconf->phone), 0, 1, 'C');
        $pdf .= Fpdf::Cell(0, 5, utf8_decode($cedula) . utf8_encode($sysconf->identification), 0, 1, 'C');
        $pdf .= Fpdf::Cell(0, 5, utf8_decode("Dirección " . substr($sysconf->direction, 0, 40)), 0, 1, 'C');


        $pdf .= Fpdf::SetFont('Times', 'I', 9);
        $pdf .= Fpdf::Cell(0, 4, utf8_decode("Fecha de Emisión: ") . utf8_encode(Carbon::now()->toDateString()), 0, 1, 'C');


        return $pdf;
    }
}