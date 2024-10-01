<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 04/07/2015
 * Time: 11:00 PM
 */

namespace AccountHon\Http\Controllers\ReportExcel;


use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\FinancialRecords;
use AccountHon\Entities\Student;
use AccountHon\Http\Controllers\ReportExcelController;
use AccountHon\Traits\ConsultStudentsTrait;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EstadoCuentaAuxiliaryController extends ReportExcelController
{

    use Convert;
    use ConsultStudentsTrait;

    public function estadoEstuden()
    {
        $years = AccountingPeriod::where('school_id', periodSchool()->school_id)->groupBY('year')->lists('year');
        $students = Student::where('school_id', periodSchool()->school_id)->get();
        return view('students.estadoCuenta', compact('years', 'students'));
    }

    public function estadoCuentaAuxiliary(Request $request)
    {
        try {
            $school = $this->schoolsRepository->oneWhere('id', userSchool()->id, 'id');
            /** @var TYPE_NAME $catalog */
            $studen = $this->studentRepository->token($request->get('student'));

            $student = FinancialRecords::where('student_id', $studen->id)->where('year', $request->get('year'))->first();
            if (!$student) {
                return redirect()->back();
            }
            $seatings = $this->auxiliarySeatRepository->whereDuo('financial_records_id', $student->id, 'status', 'aplicado', 'id', 'ASC');
            $headers = array(
                array($school[0]->name),
                array('ESTADO DE CUENTA'),
                array($studen->book . ' ' . $studen->nameComplete()),
                array(''),
                array('Fecha', 'Descripción', 'Referencia', 'Debito', 'Credito')
            );
            $content = $headers;
            $countHeader = count($headers);
            $saldoInitial = 0;
            /*  $probacionYear = $this->financialRecordsRepository->getModel()->where('student_id',$student->id)
                  ->where('year',(periodSchool()->year))->get();
              if($probacionYear):*/
            $saldoInitial = $this->financialRecordsRepository->getModel()->where('student_id', $studen->id)
                ->where('year', ($request->get('year') - 1))->sum('balance');

            if ($saldoInitial > 0):
                $content[] = array('', 'Saldo Inicial', '', $saldoInitial, '');
            elseif ($saldoInitial < 0):
                $content[] = array('', 'Saldo Inicial', '', '', $saldoInitial);
            else:
                $content[] = array('', 'Saldo Inicial', '', '', '');
            endif;

            foreach ($seatings AS $seating):
                if ($seating->types->name == 'debito'):
                    $content[] = array($seating->date, $seating->detail, $seating->code, $seating->amount, '');
                else:
                    $content[] = array($seating->date, $seating->detail, $seating->code, '', $seating->amount);
                endif;
            endforeach;
            $countContent = count($content);
            $content[] = array('', 'Fecha de Impresión: ' . date('d-m-Y'),
                               'Saldo ',
                               number_format($this->balanceStudent($student, $this->auxiliarySeatRepository, $request->get('year')), 2));
            $countFooter = count($content);

            Excel::create(date('d-m-Y') . '-' . $studen->nameComplete(), function ($excel) use ($studen, $content, $countHeader, $countContent, $countFooter) {
                $excel->sheet($studen->book, function ($sheet) use ($content, $countHeader, $countContent, $countFooter) {
                    $sheet->mergeCells('A1:E1');
                    $sheet->mergeCells('A2:E2');
                    $sheet->mergeCells('A3:E3');
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
                    $sheet->cells('A5:E5', function ($cells) {
                        $cells->setFontSize(12);
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
                    $sheet->cells('A' . $countFooter . ':E' . $countFooter, function ($cells) {
                        $cells->setFontSize(12);
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
                    $sheet->fromArray($content, null, 'A1', true, false);
                });
            })->export('xlsx');
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            die;
        }
    }

    public function estadoCuentaStudent($token)
    {
        try {
            $school = $this->schoolsRepository->oneWhere('id', userSchool()->id, 'id');
            /** @var TYPE_NAME $catalog */
            $studen = $this->studentRepository->token($token);

            $student = FinancialRecords::where('student_id', $studen->id)->where('year', date('Y'))->first();

            if (!$student) {
                return redirect()->back();
            }

            $seatings = $this->auxiliarySeatRepository->whereDuo('financial_records_id', $student->id, 'status', 'aplicado', 'id', 'ASC');
            $headers = array(
                array($school[0]->name),
                array('ESTADO DE CUENTA'),
                array($studen->book . ' ' . $studen->nameComplete()),
                array(''),
                array('Fecha', 'Descripción', 'Referencia', 'Debito', 'Credito')
            );
            $content = $headers;
            $countHeader = count($headers);
            $saldoInitial = 0;
            /*  $probacionYear = $this->financialRecordsRepository->getModel()->where('student_id',$student->id)
                  ->where('year',(periodSchool()->year))->get();
              if($probacionYear):*/
            $saldoInitial = $this->financialRecordsRepository->getModel()->where('student_id', $studen->id)
                ->where('year', (date('Y') - 1))->sum('balance');

            if ($saldoInitial > 0):
                $content[] = array('', 'Saldo Inicial', '', $saldoInitial, '');
            elseif ($saldoInitial < 0):
                $content[] = array('', 'Saldo Inicial', '', '', $saldoInitial);
            else:
                $content[] = array('', 'Saldo Inicial', '', '', '');
            endif;

            foreach ($seatings AS $seating):
                if ($seating->types->name == 'debito'):
                    $content[] = array($seating->date, $seating->detail, $seating->code, $seating->amount, '');
                else:
                    $content[] = array($seating->date, $seating->detail, $seating->code, '', $seating->amount);
                endif;
            endforeach;
            $countContent = count($content);
            $content[] = array('', 'Fecha de Impresión: ' . date('d-m-Y'),
                               'Saldo ',
                               number_format($this->balanceStudent($student, $this->auxiliarySeatRepository, date('Y')), 2));
            $countFooter = count($content);

            Excel::create(date('d-m-Y') . '-' . $studen->nameComplete(), function ($excel) use ($studen, $content, $countHeader, $countContent, $countFooter) {
                $excel->sheet($studen->book, function ($sheet) use ($content, $countHeader, $countContent, $countFooter) {
                    $sheet->mergeCells('A1:E1');
                    $sheet->mergeCells('A2:E2');
                    $sheet->mergeCells('A3:E3');
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
                    $sheet->cells('A5:E5', function ($cells) {
                        $cells->setFontSize(12);
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
                    $sheet->cells('A' . $countFooter . ':E' . $countFooter, function ($cells) {
                        $cells->setFontSize(12);
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
                    $sheet->fromArray($content, null, 'A1', true, false);
                });
            })->export('xlsx');
        } catch (\Exception $e) {
            echo json_encode($e->getMessage().' '.$e->getTraceAsString());
            die;
        }
    }

    /**
     * @param $catalog
     * @return mixed
     */


    public function estadoCuentaSupplier($token)
    {
        $school = $this->schoolsRepository->oneWhere('id', userSchool()->id, 'id');
        /** @var TYPE_NAME $catalog */
        $student = $this->studentRepository->token($token);

        $seatings = $this->auxiliarySeatRepository->whereDuo('financial_records_id', $student->financialRecords->id, 'status', 'aplicado', 'id', 'ASC');

        $headers = array(
            array($school[0]->name),
            array('ESTADO DE CUENTA'),
            array($student->book . ' ' . $student->nameComplete()),
            array(''),
            array('Fecha', 'Descripción', 'Referencia', 'Debito', 'Credito')
        );
        $content = $headers;
        $countHeader = count($headers);
        foreach ($seatings AS $seating):
            if ($seating->types->name == 'debito'):
                $content[] = array($seating->date, $seating->detail, $seating->code, $seating->amount, '');
            else:
                $content[] = array($seating->date, $seating->detail, $seating->code, '', $seating->amount);
            endif;

        endforeach;
        $countContent = count($content);
        $content[] = array('', 'Fecha de Impresión: ' . date('d-m-Y'), 'Saldo ', $this->saldoEstadoCuentaAuxiliary($student));
        $countFooter = count($content);

        Excel::create(date('d-m-Y') . '-' . $student->nameComplete(), function ($excel) use ($student, $content, $countHeader, $countContent, $countFooter) {
            $excel->sheet($student->book, function ($sheet) use ($content, $countHeader, $countContent, $countFooter) {
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');
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
                $sheet->cells('A5:E5', function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A' . $countFooter . ':E' . $countFooter, function ($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->fromArray($content, null, 'A1', true, false);
            });
        })->export('xlsx');

    }
}