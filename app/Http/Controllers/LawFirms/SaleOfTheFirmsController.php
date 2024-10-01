<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 11/12/2016
 * Time: 07:54 PM
 */

namespace AccountHon\Http\Controllers\LawFirms;


use AccountHon\Entities\General\InvoiceByReceipt;
use AccountHon\Entities\LawFirm\SaleOfTheFirm;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\CustomerRepository;
use AccountHon\Repositories\General\ReceiptRepository;
use AccountHon\Repositories\LawFirms\SaleOfTheFirmRepository;
use AccountHon\Repositories\Restaurant\InvoiceRepository;
use Carbon\Carbon;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaleOfTheFirmsController extends Controller
{
    /**
     * @var SaleOfTheFirmRepository
     */
    private $saleOfTheFirmRepository;
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;
    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;

    /**
     * SaleOfTheFirmsController constructor.
     * @param SaleOfTheFirmRepository $saleOfTheFirmRepository
     * @param CustomerRepository $customerRepository
     * @param InvoiceRepository $invoiceRepository
     * @param ReceiptRepository $receiptRepository
     */
    public function __construct(
        SaleOfTheFirmRepository $saleOfTheFirmRepository,
        CustomerRepository $customerRepository,
        InvoiceRepository $invoiceRepository,
        ReceiptRepository $receiptRepository
    )
    {

        $this->saleOfTheFirmRepository = $saleOfTheFirmRepository;
        $this->customerRepository = $customerRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->receiptRepository = $receiptRepository;
    }

    public function index(){
        $saleOfTheFirms = $this->saleOfTheFirmRepository->invoicesAll();
        return view('lawFirms.invoices.index',compact('saleOfTheFirms'));
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 2016-12-12 1:34pm   @Update 0000-00-00
    ***************************************************
    * @Description: Mostramos la vista para facturar
    *   en la parte legal
    *
    *
    * @Pasos:
    *
    *
    *
    ***************************************************/
    public function create()
    {
        $customers = $this->customerRepository->allFilterScholl();
        $invoice = $this->invoiceRepository->numberLawFirms();
        $number = 1;
        if($invoice):
            $number =  $invoice->numeration + 1;
        endif;
        $sales = $this->saleOfTheFirmRepository->getModel()
            ->where('school_id',userSchool()->id)
            ->where('user_id',currentUser()->id)
            ->where('status',0)->get();
        return view('lawFirms.invoices.create',compact('customers','sales','number'));
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: ${DATE} ${TIME}
     * @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @param Request $request
     ***************************************************/
    public function storeLine(Request $request)
    {
        $dataSale = $request->all();
        $dataSale['user_id']= currentUser()->id;
        $dataSale['school_id']= userSchool()->id;
        $dataSale['token']= Crypt::encrypt($dataSale['description']);
        $sale = $this->saleOfTheFirmRepository->getModel();
        if($sale->isValid($dataSale)):
            $sale->fill($dataSale);
            $sale->save();
        return $this->exito('Se guardo con exito');
        endif;
        \Log::info($dataSale);
    }

    public function deleteLine($token){
        \Log::info($token);
       $data = $this->saleOfTheFirmRepository->getModel()->where('token',$token)->delete();
        if($data):
            return $this->exito('se Elimino con exito');
            endif;
    }


    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: '2017-01-05'
     * @Update 2017-02-14
     ***************************************************
     * @Description: Con este metodo comprobamos primero
     * si existe datos con estatus cero del usuario, luego
     * comprobamos
     *
     * @Pasos:
     *
     *
     * @return mixed
     ***************************************************/
    public function store()
    {
        $dataInvoices = $this->convertionObjeto();
        DB::beginTransaction();
            $invoices = $this->CreacionArray($dataInvoices, 'SaleOfTheFirm');
            $saleOfTheFirmLine = $this->saleOfTheFirmRepository->getModel()->where('status', 0)
                ->where('user_id', $invoices['user_id'])->count();
            if ($saleOfTheFirmLine > 0):
                $invoices['total'] = $this->saleOfTheFirmRepository->totalInvoicesTheSale($invoices['user_id']);
                $invoices['subtotal'] = $this->saleOfTheFirmRepository->totalInvoicesTheSale($invoices['user_id']);
                $invoices['subtotal_exempt'] = $this->saleOfTheFirmRepository->totalInvoicesTheSale($invoices['user_id']);

                if(!empty($invoices['customer'])):
                    $customer = $this->CreacionArray($invoices, 'Option');
                    $customers = $this->customerRepository->getModel();
                    if($customers->isValid($customer)):
                        $customers->fill($customer);
                        $customers->save();
                        $invoices['customer'] =$customers->token;
                    endif;
                else:
                  return $this->errores("Debe Seleccionar o agregar un nuevo Cliente");
                endif;


                $invoices['customer_id'] = $this->customerRepository->token($invoices['customer'])->id;
                $invoices['payment_method_id'] = 1;
                $invoices['invoices_type_id'] = 2;
                if ($invoices['status'] == false):
                    $invoices['payment_method_id'] = 2;
                    $receipt = $this->receiptRepository->getModel();
                    if(!$invoices['descriptionReceipt']=='' && $invoices['payment']==''):
                        return $this->errores('Debe llenar los dos campos para recibo');
                    elseif($invoices['descriptionReceipt']=='' && !$invoices['payment']==''):
                        return $this->errores('Debe llenar los dos campos para recibo');
                    endif;
                    if(currentUser()->cashDesk ==  null):
                        return $this->errores('El usuario no tiene asignada una caja');
                    endif;
                    $receiptArray = $this->receipt($invoices);
                    $receipt->fill($receiptArray);
                    $receipt->save();
                    $invoices['receiptId']= $receipt->id;
                endif;
                //
                $saleOfTheFirm = $this->invoiceRepository->getModel();
                if ($saleOfTheFirm->isValid($invoices)):
                    $saleOfTheFirm->fill($invoices);
                    DB::commit();
                    $saleOfTheFirm->save();
                    if($invoices['receiptId']):
                    InvoiceByReceipt::create(['invoice_id'=>$saleOfTheFirm->id,
                        'receipt_id'=>$invoices['receiptId'],
                        'amount'=>$invoices['payment']]);
                    endif;
                    $this->saleOfTheFirmRepository->updateSaleInvoice($saleOfTheFirm,$invoices);
                        return $this->exito('factura-bufete/pdf/'.$saleOfTheFirm->token);
                endif;
                DB:rollback();
                return $this->errores($saleOfTheFirm->errors);
            else:
                return $this->errores('Debe Agregar minimo una linea de Detalle');
            endif;

    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2017-02-14
     * @Update 0000-00-00
     ***************************************************
     * @Description: Este metodo, creamos el array para
     * insetarlos en la tabla recibos
     *
     *
     * @Pasos:
     *
     *
     * @param $data
     * @return array
     ***************************************************/
    public function receipt($data)
    {
        $referen = $this->receiptRepository->getModel()->where('school_id',userSchool()->id)->orderBy('id','DESC')->first();
         if ($referen == null):
             $referen = 1;
         else:
             $referen = $referen->reference+1;
         endif;

        return array(
            'date'=>$data['date'],
            'reference'=>$referen,
            'balance'=>$data['payment'],
            'cash_desk_id'=>currentUser()->cashDesk[0]->id,
            'customer_id'=>$data['customer_id'],
            'user_id'=>currentUser()->id,
            'school_id'=>userSchool()->id,
            'token'=>Crypt::encrypt($data['customer_id'].$data['payment'])
        );
    }

    public function pdf($token)
    {
        $invoices = $this->invoiceRepository->token($token);
        $this->header();
        $pdf = Fpdf::SetFont('Arial', 'B', 14);
        $pdf .= Fpdf::Ln(2);
        $pdf .= Fpdf::Ln(2);
        // informacion de la factura
        $pdf .= Fpdf::SetLineWidth(1.5);
        $pdf .= Fpdf::Line(10, 15, 150 , 15);
        $pdf .= Fpdf::Line(15, 10, 15 , 130);
        $pdf .= Fpdf::Image('images/Vargas_Arroyo-&-Asociados.png', 20, 20, 50 , 15,'png','images/Vargas_Arroyo-&-Asociados.png');


        $pdf .= Fpdf::Cell(0,7,'Factura',0,1,'C');
        $pdf .= Fpdf::SetX(20);
        $pdf .= Fpdf::Cell(60,7,utf8_decode($invoices->date),0,0,'L');
        $pdf .= Fpdf::Cell(80,7,utf8_decode('N° '.$invoices->numeration),0,1,'R');
        $y = Fpdf::getY();
        $pdf .= Fpdf::SetY($y+10);
        $pdf .= Fpdf::Ln(2);
        $pdf .= Fpdf::Ln(2);
            $pdf .= Fpdf::SetX(20);
        $pdf .= Fpdf::Cell(0,7,utf8_decode('Cliente: '.$invoices->customer[0]->charter.' - '.$invoices->customer[0]->nameComplete()),0,1,'L');
        $pdf .= Fpdf::Ln(2);
        $pdf .= Fpdf::Ln(2);
        $pdf .= Fpdf::SetX(20);
        $pdf .= Fpdf::SetFillColor(0,0,0);
        $pdf .= Fpdf::SetTextColor(255,255,255);
        $pdf .= Fpdf::Cell(120,7,utf8_decode('Descripcion'),0,0,'C',true);
        $pdf .= Fpdf::Cell(40,7,utf8_decode('Monto'),0,1,'C',true);
        foreach ($invoices->customer AS $sale):
            $pdf .= Fpdf::SetX(20);
            $pdf .= Fpdf::SetTextColor(0,0,0);
            $pdf .= Fpdf::SetFillColor(255,255,255);
            $pdf .= Fpdf::SetLineWidth(0.4);
            $pdf .= Fpdf::Cell(120,7,utf8_decode($sale->pivot->description),1,0,'L');
            $pdf .= Fpdf::Cell(40,7,number_format($sale->pivot->amount,0),1,1,'C');
        endforeach;
        $pdf .= Fpdf::SetX(120);
        $pdf .= Fpdf::Cell(20,7,utf8_decode('Total:'),1,0,'C');
        $pdf .= Fpdf::Cell(40,7,number_format($invoices->total,2),1,1,'C');
        $y = Fpdf::getY();
        $pdf .= Fpdf::SetY($y+20);

        $pdf .= Fpdf::Cell(0,7,utf8_decode('Abogado:_________________'),0,1,'C');
        if(userSchool()->code):
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Codigo: '.userSchool()->code),0,1,'C');
        else:
            $pdf .= Fpdf::Cell(0,7,utf8_decode('Codigo:______________ '),0,1,'C');
        endif;
        Fpdf::Output();
        exit;
    }

    public function header()
    {
        $pdf  = Fpdf::AddPage();
        $pdf .= Fpdf::SetXY(40,20);
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,7,utf8_decode( userSchool()->name),0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::SetXY(40,27);
        $pdf .= Fpdf::Cell(0,7,'Cedula: '.userSchool()->charter,0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::SetXY(40,34);
        $pdf .= Fpdf::Cell(0,7,userSchool()->address,0,1,'C');
        $pdf .= Fpdf::SetXY(40,41);
        $pdf .= Fpdf::Cell(0,7,'Celular: '. userSchool()->phoneOne,0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',14);
        $pdf .= Fpdf::SetXY(40,48);
        $pdf .= Fpdf::Cell(0,7,userSchool()->email,0,1,'C');
        $pdf .= Fpdf::Ln();
        return $pdf;
    }
}