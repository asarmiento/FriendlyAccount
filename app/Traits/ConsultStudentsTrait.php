<?php
/**
 * Created by PhpStorm.
 * User: Anwar
 * Date: 27/09/2017
 * Time: 12:02 PM
 */

namespace AccountHon\Traits;


use AccountHon\Entities\FinancialRecords;
use AccountHon\Entities\TypeForm;
use AccountHon\Repositories\AuxiliarySeatRepository;

trait ConsultStudentsTrait
{

    public function saldoEstadoCuentaAuxiliary($student, $auxiliar)
    {

    }

    public function balanceStudent($student, $auxiliar,$year)
    {
        $initial= FinancialRecords::where('student_id', $student->id)
            ->where('year', ($year - 1))->sum('balance');
        $debito = $this->nameType('debito');
        $credito = $this->nameType('credito');
        $seatingsDebito = $auxiliar->getModel()
            ->where('financial_records_id', $student->id)
            ->where('type_id', $debito->id)
            ->where('status', 'aplicado')
            ->sum('amount');

        $seatingsCredito = $auxiliar->getModel()
            ->where('financial_records_id', $student->id)
            ->where('type_id', $credito->id)
            ->where('status', 'aplicado')
            ->sum('amount');

        $total = $seatingsDebito - $seatingsCredito;

        return $initial +$total;
    }

    protected function nameType($name)
    {
        return TypeForm::where('name', $name)->first();
    }


}