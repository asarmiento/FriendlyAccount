<?php

namespace AccountHon\Http\Controllers\ReportExcel;

use AccountHon\Http\Controllers\ReportExcelController;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\ReportExcel;
use Maatwebsite\Excel\Facades\Excel;

class Catalog extends ReportExcelController
{
    use Convert;

    public function catalog(){

         /** @var TYPE_NAME $catalog */
        $catalogs = $this->catalogRepository->orderByFilterSchool('code','ASC');
        $content = array(
            array(userSchool()->name),
            array('CATALOGO DE CUENTAS'),
            array(''),
            array(''),
            array('Codigo','Nombre','Estilo','Tipo')
        );

        $type = array(1=>'Activo',2=>'Pasivos',3=>'Patrimonio',4=>'Ingresos',5=>'Compras',6=>'Gastos');

        foreach($catalogs AS $catalog):

            $content[]=array($catalog->code,$catalog->name,$catalog->style,$type[$catalog->type]);

        endforeach;

         Excel::create(date('d-m-Y').'- Catalogo de Cuentas', function($excel) use ($content) {
            $excel->sheet('Lista de Cuentas', function($sheet)  use ($content) {
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                $sheet->mergeCells('A4:D4');
                
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
                $sheet->cell('A4', function($cell) {
                    $cell->setFontSize(16);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->cells('A5:D5', function($cells) {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                
                $sheet->fromArray($content, null, 'A1', true,false);
            });
        })->export('xlsx');
    }
}
