<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\AccountingPeriod;
use AccountHon\Entities\Catalog;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\BalancePeriodRepository;
use AccountHon\Repositories\CatalogRepository;

use AccountHon\Repositories\SeatingRepository;
use AccountHon\Traits\Convert;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;

class FpdfController  extends Controller
{
    use Convert;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var BalancePeriodRepository
     */
    private $balancePeriodRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;
    /**
     * @var SeatingRepository
     */
    private $seatingRepository;

    /**
     * FpdfController constructor.
     * @param \AccountHon\Repositories\CatalogRepository $catalogRepository
     * @param \AccountHon\Repositories\BalancePeriodRepository $balancePeriodRepository
     * @param \AccountHon\Repositories\AccountingPeriodRepository $accountingPeriodRepository
     * @param SeatingRepository $seatingRepository
     */
    public function __construct(
        CatalogRepository $catalogRepository,
        BalancePeriodRepository $balancePeriodRepository,
        AccountingPeriodRepository $accountingPeriodRepository,
        SeatingRepository $seatingRepository
    )
    {

        $this->catalogRepository = $catalogRepository;
        $this->balancePeriodRepository = $balancePeriodRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
        $this->seatingRepository = $seatingRepository;
    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-11-08
    |@Date Update: 2015-00-00
    |---------------------------------------------------------------------
    |@Description: Generamos el encabezado del estado resultado
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function header()
    {
        $pdf  = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,7,utf8_decode( userSchool()->name),0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'Cedula: '.userSchool()->charter,0,1,'C');

        return $pdf;
    }

    public function viewEstadoResultado()
    {
        $years = AccountingPeriod::where('school_id',periodSchool()->school_id)->groupBY('year')->lists('year');
        return view('Accounting.closedYear.estadoResultado',compact('years'));
    }
    public function pdfEstadoSituacion()
    {
       /**
         * ENCABEZADO
         */
       $pdf= $this->header();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,7,utf8_decode('ESTADO DE SITUACIÓN'),0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'Periodo: '.period(),0,1,'C');
        /**
         * ACTIVOS CIRCULANTES
         */
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,7,'ACTIVOS ',0,1,'L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'ACTIVOS CIRCULANTES',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('01-01-00-00-000')->childCatalogs;

        echo json_encode(recursiva('01-01-00-00-000',array(),0,$activeCirculant));
        die;

        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',1)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,utf8_decode(strtoupper($activeCirculante->name)),0,1,'L');
        endforeach;
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'MOBILIARIO PLANTA Y EQUIPO',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('01-02-00-00-000');
        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',1)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,strtoupper(utf8_decode($activeCirculante->name)),0,1,'L');
        endforeach;
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'DIFERIDOS',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('01-03-00-00-000');
        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',1)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,strtoupper(utf8_decode($activeCirculante->name)),0,1,'L');
        endforeach;
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'OTROS ACTIVOS',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('01-04-00-00-000');
        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',1)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,strtoupper(utf8_decode($activeCirculante->name)),0,1,'L');
        endforeach;
        /**
         * PASIVOS
         */
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,7,'PASIVOS ',0,1,'L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'PASIVOS CIRCULANTES',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('02-01-00-00-000');
        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',2)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,utf8_decode(strtoupper($activeCirculante->name)),0,1,'L');
        endforeach;
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'PASIVOS NO CIRCULANTES',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('02-02-00-00-000');
        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',2)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,strtoupper(utf8_decode($activeCirculante->name)),0,1,'L');
        endforeach;
        /**
         * PASIVOS
         */
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,7,'PATRIMONIO ',0,1,'L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'FONDO DISPONIBLE ',0,1,'L');
        $activeCirculant = $this->catalogRepository->code('03-01-00-00-000');
        $activeCirculantes = $this->catalogRepository->getModel()->where('school_id',userSchool()->id)->where('level',3)
            ->where('type',3)->where('catalog_id',$activeCirculant->id)->get();
        foreach ($activeCirculantes As $activeCirculante):
            $pdf->SetFont('Arial','I',10);
            $pdf->Cell(0,7,utf8_decode(strtoupper($activeCirculante->name)),0,1,'L');
        endforeach;
        Fpdf::Output();
        exit;
    }
    /**
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-11-08
    |@Date Update: 2017-03-30
    |---------------------------------------------------------------------
    |@Description: Con esta acción generamos la información de los ingresos
     * SE HA MODIFICADO POR COMPLETO EL REPORTE DE ESTADO DE RESULTADO A
     * UN NUEVO FORMATO, SE DEBE IR AGREGANDO PERSONALIZACION SEGUN EL TIPO
     * DE INSTITUCION.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function pdfEstadoResultado(Request $request)
    {
        /**
         * ENCABEZADO
         */

        $dateR = AccountingPeriod::where('year',$request->get('year'))->where('school_id',periodSchool()->school_id)->lists('id');
       $pdf = $this->header();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,7,'ESTADO DE RESULTADO',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'Periodo: 12-'.$request->get('year'),0,1,'C');
        /**
         * INGRESOS DE LA INSTITUCIÓN
         *
         */
             $catalogs = Catalog::where('type',4)->where('style','Detalle')->where('school_id',periodSchool()->school_id)->get();
            $ings = 0;
        foreach ($catalogs AS $catalog):
              $this->seatingRepository->balanceCatalogoInPeriod($catalog,$dateR);
        endforeach;
        $pdf->SetFont('Arial','I',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('INGRESOS'),0,0,'L');
        $pdf->Cell(40,5,'',0,1,'R');
        $pdf->SetX(20);
        foreach ($catalogs AS $catalog):
            $amount=  $this->seatingRepository->balanceCatalogoInPeriod($catalog->id,$dateR);
            $ings +=  $amount;
            $pdf->SetX(20);
            $pdf->Cell(120,5,utf8_decode($catalog->name),0,0,'L');
        $pdf->Cell(40,5,number_format($amount,2),0,1,'R');
        endforeach;
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('OTROS INGRESOS'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $Y= $pdf->getY();
        $pdf->Line(160,$Y,190,$Y);

        $pdf ->SetFont('Arial','BI',10);

        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('TOTAL DE INGRESOS..........'),0,0,'L');
        $pdf->Cell(40,5,number_format($ings,2),0,1,'R');
        /**
         *
         * INVENTARIO Y COMPRAS COSTO DE VENTAS
         *
         *
        $pdf->SetFont('Arial','BI',12);
        $pdf->SetX(20);
        $pdf->Cell(100,10,utf8_decode('COSTO DE VENTAS'),0,1,'L');
        $pdf->SetFont('Arial','I',10);
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('INVENTARIO INICIAL'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('COMPRAS'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf->Line(120,$Y,150,$Y);
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('SUBTOTAL'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf ->SetFont('Arial','BI',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('INVENTARIO FINAL'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf->Line(160,$Y,190,$Y);
        $pdf ->SetFont('Arial','BI',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('UTILIDAD BRUTA.......'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
*/
        /**
         * GASTOS DE LA INSTITUCION
         */
        $pdf->Ln();
        $pdf ->SetFont('Arial','BI',12);
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('GASTOS'),0,1,'L');
        $catalogs = Catalog::where('type',6)->where('style','Detalle')->where('school_id',periodSchool()->school_id)->get();
        $gtos = 0;

        $pdf ->SetFont('Arial','I',10);
        foreach ($catalogs AS $catalog):
            $amount =   $this->seatingRepository->balanceCatalogoInPeriod($catalog->id,$dateR);
        $gtos += $amount;
            $pdf->SetX(20);
            $pdf->Cell(100,5,utf8_decode($catalog->name),0,0,'L');
            $pdf->Cell(20,5,number_format($amount,2),0,1,'R');
        endforeach;
        /*$pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('GASTOS DE VENTAS'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('GASTOS FINANCIEROS'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf ->SetFont('Arial','BI',10);
        $Y->getY();
        $pdf->Line(120,$Y,150,$Y);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('TOTAL DE GASTOS'),0,0,'L');
        $pdf->Cell(40,5,number_format($gtos,2),0,1,'R');
        $Y->getY();
        $pdf->Line(160,$Y,190,$Y);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('UTILIDAD ANTES IMP S/RENTA.....'),0,0,'L');
        $pdf->Cell(40,5,number_format(($ings*-1)-$gtos,2),0,1,'R');

        /**
         * Ingresos y gastos no Gravables
         */
       /* $pdf->Ln();
        $pdf ->SetFont('Arial','I',10);
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('INGRESOS NO GRAVABLES'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('GASTOS NO DEDUCIBLE'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');*/
        $Y= $pdf->getY();
        $pdf->Line(160,$Y,190,$Y);
        $pdf ->SetFont('Arial','BI',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('TOTAL GRAVABLE......'),0,0,'L');
        $pdf->Cell(40,5,number_format($gtos,2),0,1,'R');
        $pdf->Ln();
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('IMPUESTO SOBRE RENTA'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');

        /**
         * EXONERACIONES
         */

       /* $pdf->Ln();
        $pdf ->SetFont('Arial','BI',12);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('EXONERACIONES'),0,1,'L');
        $pdf ->SetFont('Arial','I',10);
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('EXONERACION CONTRATOS TURISMO'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('EXONERACION ZONA FRANCA'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('EXONERACION CONT DE EXPORTA'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('EXONERACION OTROS CONCEPTOS'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf->Line(120,$Y,150,$Y);
        $pdf ->SetFont('Arial','BI',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('TOTAL EXONERACIONES...........'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf->Line(160,$Y,190,$Y);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('IMPUESTO SOBRE RENTA'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        /**
         * creditos
         */
/*
        $pdf->Ln();
        $pdf ->SetFont('Arial','BI',12);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('CREDITOS'),0,1,'L');
        $pdf ->SetFont('Arial','I',10);
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('CREDITOS FAMILIARES'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('CREDITO CONTRATO TURISTICO'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('CREDITO CONTRATO PRODUCCION'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('CREDITO CONTRATO FORESTAL'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(100,5,utf8_decode('OTROS CREDITOS'),0,0,'L');
        $pdf->Cell(20,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf->Line(120,$Y,150,$Y);
        $pdf ->SetFont('Arial','BI',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('TOTAL CREDITOS..............'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf->Line(160,$Y,190,$Y);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('IMPUESTO SOBRE RENTA'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
*/
        /**
         *  DEDUCCIONES POR RETENCIONES
         */
/*
        $pdf ->SetFont('Arial','I',10);
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('PAGOS PARCIALES Y ADELANTADOS'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('RETENCIONES 1% 2% 3%'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $pdf->SetX(20);
        $pdf->Cell(120,5,utf8_decode('COMPENSANCION I.V.A.'),0,0,'L');
        $pdf->Cell(40,5,number_format('0'),0,1,'R');
        $Y->getY();
        $pdf ->SetFont('Arial','BI',12);
        $pdf->Line(160,$Y,190,$Y);$pdf->SetX(20);
        $pdf->Cell(120,7,utf8_decode('IMPUESTOS RENTA POR PAGAR'),0,0,'L');
        $pdf->Cell(40,7,number_format('0'),0,1,'R');*/
        $Y=$pdf->getY();
        $pdf->SetFont('Arial','BI',14);
        $pdf->Line(160,$Y,190,$Y);$pdf->SetX(20);
        $pdf->Cell(120,7,utf8_decode('UTILIDAD NETA DESPUES DE IMPUESTOS'),0,0,'L');
        $pdf->Cell(40,7,number_format(($ings*-1)-$gtos,2),0,1,'R');
        $Y= $pdf->getY();
        $pdf->Line(160,$Y,190,$Y);

        $pdf->Output();
        exit;
    }
}
