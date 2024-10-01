<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 06/07/2015
 * Time: 10:54 PM
 */

namespace AccountHon\Http\Controllers\ReportExcel;


use AccountHon\Http\Controllers\ReportExcelController;
use AccountHon\Traits\Convert;

class AccountingReceipt extends ReportExcelController {

    use Convert;

    public function report(){
        $content = array(
            array(userSchool()->name),
            array('BALANCE DE COMPROBACIÓN'),
            array(''),
            array('CODIGO','NOMBRE DE CUENTA','SALDO INICIAL','debito PERIODO','credito PERIODO','BALANCE FINAL'),
        );
        $countHeader = count($content);
    }
}