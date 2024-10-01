<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 01/02/16
 * Time: 08:28 AM
 */

namespace AccountHon\Http\Controllers\Restaurant\Report;


use AccountHon\Http\Controllers\Controller;

use Codedge\Fpdf\Fpdf\Fpdf;

class BaseReportController extends Controller
{

    public function __construct(

    )
    {

    }

    public function header()
    {
        echo json_encode(userSchool());
        die;
        $pdf = Fpdf::AddPage();
        $pdf .= Fpdf::Cell(0,5,userSchool()->name,0,1,'C');
    }

}