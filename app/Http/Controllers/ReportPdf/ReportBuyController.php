<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 01/12/2016
 * Time: 10:34 AM
 */

namespace AccountHon\Http\Controllers\ReportPdf;


use AccountHon\Entities\Accounting\Supplier;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\InventoriesIncomeRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use AccountHon\Repositories\Restaurant\SupplierRepository;
use Codedge\Fpdf\Facades\Fpdf;


class ReportBuyController extends Controller
{
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var InventoriesIncomeRepository
     */
    private $inventoriesIncomeRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * ReportBuyController constructor.
     * @param SupplierRepository $supplierRepository
     * @param InventoriesIncomeRepository $inventoriesIncomeRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        InventoriesIncomeRepository $inventoriesIncomeRepository,
        InvoiceRepository $invoiceRepository
    )
    {

        $this->supplierRepository = $supplierRepository;
        $this->inventoriesIncomeRepository = $inventoriesIncomeRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function pdfBuy($token)
    {
        $this->header();
        $invoice = $this->inventoriesIncomeRepository->token($token);
        $pdf = Fpdf::SetFont('Arial','B',14);
        // informacion de la factura
        $pdf .= Fpdf::Cell(120,5,'Proveedor: '.$invoice->supplier->name,0,0,'L');
        $pdf .= Fpdf::Cell(50,5,'Fecha Factura: '.$invoice->invoice->date,0,1,'L');
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::Cell(70,5,'Forma de Pago: '.$invoice->invoice->paymentMethod->name,0,0,'L');
        $pdf .= Fpdf::Cell(60,5,utf8_decode('N° Factura: ').$invoice->reference,0,0,'L');
        $pdf .= Fpdf::Cell(50,5,'Referencia: '.$invoice->invoice->numeration,0,1,'L');

        // contenido de la factura
        $pdf = Fpdf::SetFont('Arial','B',14);
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetDrawColor(87,171,234);
        $pdf .= Fpdf::SetTextColor(255,255,255);
        $pdf .= Fpdf::SetX(15);
        $pdf .= Fpdf::Cell(10,7,utf8_decode('N°'),1,0,'C',true);
        $pdf .= Fpdf::Cell(30,7,utf8_decode('Codigo'),1,0,'C',true);
        $pdf .= Fpdf::Cell(70,7,utf8_decode('Descripcion'),1,0,'C',true);
        $pdf .= Fpdf::Cell(20,7,utf8_decode('Cant'),1,0,'C',true);
        $pdf .= Fpdf::Cell(25,7,utf8_decode('Precio'),1,0,'C',true);
        $pdf .= Fpdf::Cell(25,7,utf8_decode('Total'),1,1,'C',true);

        $invoices = $this->invoiceRepository->find($invoice->invoice_id);
        $i=0;
        $pdf .= Fpdf::SetTextColor(0,0,0);

        foreach ($invoices->rawProduct AS $rawProduct):
            $i++;
            $pdf = Fpdf::SetFont('Arial','',12);
            $pdf .= Fpdf::SetX(15);
            $pdf .= Fpdf::Cell(10,5,utf8_decode($i),1,0,'C');
            $pdf .= Fpdf::Cell(30,5,utf8_decode($rawProduct->code),1,0,'C');
            $pdf .= Fpdf::Cell(70,5,utf8_decode($rawProduct->description),1,0,'L');
            $pdf .= Fpdf::Cell(20,5,number_format($rawProduct->pivot->amount,2),1,0,'C');
            $pdf .= Fpdf::Cell(25,5,number_format($rawProduct->pivot->price,2),1,0,'C');
            $pdf .= Fpdf::Cell(25,5,number_format($rawProduct->pivot->price*$rawProduct->pivot->amount,2),1,1,'C');
        endforeach;
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(135);
        $pdf .= Fpdf::Cell(25,7,utf8_decode('Sub Total: '),1,0,'R');
        $pdf .= Fpdf::Cell(35,7,number_format($invoices->subtotal,2),1,1,'C');
        $pdf .= Fpdf::SetX(135);
        $pdf .= Fpdf::Cell(25,7,utf8_decode('Descuento: '),1,0,'R');
        $pdf .= Fpdf::Cell(35,7,number_format($invoices->descount,2),1,1,'C');
        $pdf .= Fpdf::SetX(135);
        $pdf .= Fpdf::Cell(25,7,utf8_decode('Impuesto: '),1,0,'R');
        $pdf .= Fpdf::Cell(35,7,number_format($invoices->tax,2),1,1,'C');
        $pdf .= Fpdf::SetX(135);
        $pdf .= Fpdf::Cell(25,7,utf8_decode('Total: '),1,0,'R');
        $pdf .= Fpdf::Cell(35,7,number_format($invoices->total,2),1,1,'C');


        Fpdf::Output();
        exit;
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-12-01 11:07am
     * @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @return string
     ***************************************************/
    public function header()
    {
        $pdf  = Fpdf::AddPage();
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,7,utf8_decode( userSchool()->name),0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::Cell(0,7,'Cedula: '.userSchool()->charter,0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,7,'FACTURA DIGITAL DE PROVEEDOR',0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::Cell(0,7,'Periodo: '.period(),0,1,'C');
        $pdf .= Fpdf::Ln();

        return $pdf;
    }
}