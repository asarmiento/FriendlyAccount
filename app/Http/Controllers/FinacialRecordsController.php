<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Repositories\CostRepository;
use AccountHon\Repositories\FinancialRecordsRepository;
use AccountHon\Repositories\StudentRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;


class FinacialRecordsController extends Controller
{
    use Convert;
    /**
     * @var FinancialRecordsRepository
     */
    private $financialRecordsRepository;
    /**
     * @var CostRepository
     */
    private $costRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * @param FinancialRecordsRepository $financialRecordsRepository
     * @param CostRepository $costRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param StudentRepository $studentRepository
     */
    public function __construct(
        FinancialRecordsRepository $financialRecordsRepository,
        CostRepository $costRepository,
        TypeSeatRepository $typeSeatRepository,
        StudentRepository $studentRepository
    ){

        $this->financialRecordsRepository = $financialRecordsRepository;
        $this->costRepository = $costRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * @param $Validation
     * @param $Student
     * @return \Illuminate\Http\JsonResponse
     */
    public  function update($id,$Validation,$Student){

        // Obtenemos los datos de los costos del grade
        $cost = $this->costRepository->idCostDegree($Validation['degree']);
        $financial['user_created'] = $Validation['user_updated'];
        $financial['student_id'] = $Student->id;
        $financial['monthly_discount'] = $Validation['discount'];
        $financial['tuition_discount'] = $Validation['discountTuition'];
        $financial['cost_id'] = $cost->id;
        $financial['year'] = $cost->year;
        $financial['token'] = \Crypt::encrypt($Student->id);
        $financial['balance'] = ($cost->monthly_payment+$cost->tuition)-($financial['monthly_discount']+ $financial['tuition_discount']);
        $financialRecords = $this->financialRecordsRepository->find($id);
        if ($financialRecords->fill($financial)):

            $financialRecords->save();
            $this->typeSeatRepository->updateWhere('alumn',userSchool()->id);

            /* Enviamos el mensaje de guardado correctamente */
            return $this->exito('Los datos se guardaron con exito!!!');
        endif;

        return $this->errores($financialRecords->errors);
    }

        public  function save($Validation,$Student){

        // Obtenemos los datos de los costos del grade
        $cost = $this->costRepository->idCostDegree($Validation['degree']);

        if(!$cost){
            return false;
        }

        $financial['user_created'] = currentUser()->id;
        $financial['student_id'] = $Student->id;
        $financial['monthly_discount'] = $Validation['discount'];
        $financial['tuition_discount'] = $Validation['discountTuition'];
        $financial['cost_id'] = $cost->id;
        $financial['year'] = $cost->year;
        $financial['token'] = \Crypt::encrypt($Student->id);
        $financial['balance'] = ($cost->monthly_payment+$cost->tuition)-($financial['monthly_discount']+ $financial['tuition_discount']);
        $financialRecords = $this->financialRecordsRepository->getModel();
        if ($financialRecords->isValid($financial)):
            $financialRecords->fill($financial);
            $financialRecords->save();
            $updateTypeSeat = $this->typeSeatRepository->updateWhere('alumn',userSchool()->id);
            if ($updateTypeSeat == 1) {
                return $this->exito('Los datos se guardaron con exito!!!');
            }else{
                return $this->errores($updateTypeSeat->errors);
            }
            /* Enviamos el mensaje de guardado correctamente */
        endif;
        return $this->errores($financialRecords->errors);

    }

    public function desactivarStudent($token)
    {
        $student = $this->studentRepository->token($token);

        $this->financialRecordsRepository->updateData($student->financialRecords->id,'retirement_date',dateShort());

        return redirect::route('ver-estudiantes');
    }
}
