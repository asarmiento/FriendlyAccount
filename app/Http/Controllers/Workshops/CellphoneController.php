<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 24/02/2017
 * Time: 03:33 PM
 */

namespace AccountHon\Http\Controllers\Workshops;


use AccountHon\Entities\Workshops\Cellphone;
use AccountHon\Entities\Workshops\ModelWorkshop;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\BrandRepository;
use AccountHon\Repositories\General\CustomerRepository;
use AccountHon\Repositories\Workshops\CellphoneRepository;
use AccountHon\Repositories\Workshops\ModelWorkshopRepository;
use AccountHon\Repositories\Workshops\TechnicalCellphoneRepository;
use AccountHon\Traits\MailTrait;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CellphoneController extends Controller
{
    use MailTrait;
    /**
     * @var CellphoneRepository
     */
    private $cellphoneRepository;
    /**
     * @var BrandRepository
     */
    private $brandRepository;
    /**
     * @var ModelWorkshopRepository
     */
    private $modelWorkshopRepository;
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var TechnicalCellphoneRepository
     */
    private $technicalCellphoneRepository;


    /**
     * CellphoneController constructor.
     *
     * @param CellphoneRepository          $cellphoneRepository
     * @param BrandRepository              $brandRepository
     * @param ModelWorkshopRepository      $modelWorkshopRepository
     * @param CustomerRepository           $customerRepository
     * @param TechnicalCellphoneRepository $technicalCellphoneRepository
     */
    public function __construct(
        CellphoneRepository $cellphoneRepository,
        BrandRepository $brandRepository,
        ModelWorkshopRepository $modelWorkshopRepository,
        CustomerRepository $customerRepository,
        TechnicalCellphoneRepository $technicalCellphoneRepository
    )
    {
        $this->cellphoneRepository = $cellphoneRepository;
        $this->brandRepository = $brandRepository;
        $this->modelWorkshopRepository = $modelWorkshopRepository;
        $this->customerRepository = $customerRepository;
        $this->technicalCellphoneRepository = $technicalCellphoneRepository;
    }

    public function index(){
        $cellphones = $this->cellphoneRepository->allFilterScholl();
        return view('Workshops.Cellphone.index',compact('cellphones'));
    }

    public function create()
    {
        $brands = $this->brandRepository->allFilterScholl();
        $models = $this->modelWorkshopRepository->allFilterInst();
        $customers = $this->customerRepository->allFilterScholl();
        return view('Workshops.Cellphone.create',compact('brands','models','customers'));
    }

    public function store()
    {
        $datos = $this->convertionObjeto();

        $cellphone = $this->CreacionArray($datos,'Cellphone');
        $customer = $this->customerRepository->token($cellphone['customer_id']);
        $model = $this->modelWorkshopRepository->token($cellphone['modelWorkshop_id']);
        if($customer->email==''):
            return $this->errores('El cliente no tiene un Email, Debe Agregarle uno');
        endif;
        $cellphone['customer_id'] = $customer->id;
        $cellphone['modelWorkshop_id'] = $model->id;
        $cellphone['numeration'] = 1;
        $cellphone['brand_id'] = $this->brandRepository->token($cellphone['brand_id'])->id;
        $modelCellphone = $this->cellphoneRepository->getModel();
        $data = ['view'=>'Workshops.Cellphone.email','customer'=>$customer,
                 'subject'=>'Hemos recibido su '.$cellphone['equipment'].'  para revisión en nuestro taller de servicio bajo la boleta# '.$cellphone['numeration']];
        if($modelCellphone->isValid($cellphone)):
            $modelCellphone->fill($cellphone);
            $modelCellphone->save();
            $this->send($modelCellphone,$data);
            return $this->exito('Se guardo con Exito los Datos');
           //
        endif;
        return $this->errores($modelCellphone->errors);
    }

    public function getModels(Request $request)
    {
        $brand = $this->brandRepository->token($request->brand);
        $models = [];
        if($brand){
            foreach($brand->modelWorkshop as $model){
                array_push($models, ['token' => $model->token, 'name' => $model->name]);
            }
        }
        return $models;
    }


    /**
     * ---------------------------------------------------------------------
     * @Author     : Anwar Sarmiento "asarmiento@sistemasamigables.com"
     * @Date       Create: 2014-04-26
     * @Time       Create: ${TIME}
     * @Date       Update: 0000-00-00
     * ---------------------------------------------------------------------
     * @Description: Mostamos la vista para ragistro del tecnico
     * @Pasos      :
     * @param $token
     * ----------------------------------------------------------------------
     *
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * ----------------------------------------------------------------------
     */
    public function technical($token)
    {
        $cellphones = $this->cellphoneRepository->token($token);
        $technical = $this->technicalCellphoneRepository->getModel()->where('cellphone_id',$cellphones->id);
        return view('Workshops.Cellphone.technical',compact('cellphones','technical'));
    }


    /**
     * ---------------------------------------------------------------------
     * @Author     : Anwar Sarmiento "asarmiento@sistemasamigables.com"
     * @Date       Create: 2017-05-12
     * @Time       Create: ${TIME}
     * @Date       Update: 0000-00-00
     * ---------------------------------------------------------------------
     * @Description: Registramos el trabajo realizado por el tecnico al
     *             Equipo en reparacion
     * @Pasos      :
     * ----------------------------------------------------------------------
     * @return mixed
     * ----------------------------------------------------------------------
     */
    public function postTechnical()
    {
        $datos = $this->convertionObjeto();

        $cellphone = $this->CreacionArray($datos,'Cellphone');
        $customer = $this->customerRepository->token($cellphone['customer']);

        $modelCellphone = $this->cellphoneRepository->token($cellphone['technical']);
        $cellphone['cellphone_id']=$modelCellphone->id;

        $technical = $this->technicalCellphoneRepository->getModel();
        if($technical->isValid($cellphone)):
            $technical->fill($cellphone);
            $technical->save();
            $data = ['view'=>'Workshops.Cellphone.emailTechnical','customer'=>$customer,
                     'subject'=>'Hemos terminado la reparación de su '.$modelCellphone->equipment.'  con boleta# '.$modelCellphone->numeration,'technical'=>$technical];
            $this->send($modelCellphone,$data);
            return $this->exito('Se guardo con Exito los Datos');
            //
        endif;
        return $this->errores($modelCellphone->errors);
    }

    public function pdfFile($token)
    {
        $cellphone = $this->cellphoneRepository->token($token);
        $pdf  = Fpdf::AddPage('P','Letter');
        $pdf .= Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Image('images/'.userSchool()->nameImage,20,10,40,30);
        $pdf .= Fpdf::Cell(0,7,utf8_decode( userSchool()->name),0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::Cell(0,7,'Cedula: '.userSchool()->charter,0,1,'C');
        $pdf = Fpdf::SetFont('Arial','B',16);
        $pdf .= Fpdf::Cell(0,7,utf8_decode('Ficha Técnica # '.$cellphone->numeration),0,1,'C');
        $pdf .= Fpdf::SetFont('Arial','B',12);
        $pdf .= Fpdf::Cell(0,7,'Fecha impresión: '.Carbon::now()->format('d-m-Y'),0,1,'C');
        $pdf .= Fpdf::Ln();
        /** @var  $Y  Primer rectagundo informacion del cliente*/

        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Rect(20,$Y,170,($Y-15));
        $pdf .= Fpdf::SetFont('Arial','B',8);
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(85,7,"Fecha: ",0,0,"L");
        $pdf .= Fpdf::Cell(30,7,"Recibido por: ",0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(85,7,"Nombre: ",0,0,"L");
        $pdf .= Fpdf::Cell(30,7,"Cédula: ",0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(85,7,"Teléfono: ",0,0,"L");
        $pdf .= Fpdf::Cell(30,7,"Email: ",0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(115,7,"Autorizado para Retirar: ",0,0,"L");
        $pdf .= Fpdf::Cell(20,7,"Cédula: ",0,1,"L");

        $pdf .= Fpdf::Ln();
        /** @var  $Y  segundo rectangulo datos del equipo recibido*/
        $Y =0;
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Rect(20,$Y,170,($Y-25));
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetFont('Arial','B',8);
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(40,5,"Equipo: ",0,0,"L");
        $pdf .= Fpdf::Cell(40,5,"Marca: ",0,0,"L");
        $pdf .= Fpdf::Cell(35,5,"Color: ",0,0,"L");
        $pdf .= Fpdf::Cell(35,5,"Modelo: ",0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(60,5,"S/N: ",0,0,"L");
        $pdf .= Fpdf::Cell(40,5,utf8_decode("Otro tipo de Identificación: "),0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Incluye: ( ) "),0,0,"L");
        $pdf .= Fpdf::Cell(60,5,utf8_decode("Cargador S/N: "),0,0,"L");
        $pdf .= Fpdf::Cell(25,5,utf8_decode("( )  Estuche"),0,0,"L");
        $pdf .= Fpdf::Cell(40,5,utf8_decode("Otro: "),0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Señas Físicas: "),0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Line(20,$Y+1,190,($Y+1));
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetX(22);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Contraseña y/o Pin: "),0,1,"L");

        $pdf .= Fpdf::Ln();
        /** @var  $Y  segundo rectangulo datos del equipo recibido*/
        $Y =0;
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Rect(5,$Y,100,($Y-100));
        $pdf .= Fpdf::SetXY(5,$Y+1);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Problema Reportado: "),0,1,"L");
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::SetXY(5,160);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Solicitud Adicional: "),0,1,"L");
        /** @var  $Y  Tercer rectangulo del reporte*/
        $Y =0;
        $Y = Fpdf::GetY();
        $pdf .= Fpdf::Rect(105,137,102,37 );

        $pdf .= Fpdf::SetFont('Arial','B',8);
        $pdf .= Fpdf::Rect(5,174,202,65 );
        $pdf .= Fpdf::Ln();
        $pdf .= Fpdf::SetXY(5,179);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Diagnóstico: "),0,0,"L");
        $pdf .= Fpdf::Line(25,183,95,183);
        $pdf .= Fpdf::Line(5,190,95,190);
        $pdf .= Fpdf::SetXY(95,179);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Respuestos utilizados: "),0,1,"L");
        $pdf .= Fpdf::Line(127,183,173,183);
        $pdf .= Fpdf::Line(127,190,173,190);
        $pdf .= Fpdf::SetXY(172,179);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("S/N: "),0,1,"L");
        $pdf .= Fpdf::Line(180,183,205,183);
        $pdf .= Fpdf::SetXY(172,186);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("S/N: "),0,1,"L");
        $pdf .= Fpdf::Line(180,190,205,190);
        $pdf .= Fpdf::SetXY(5,192);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Trabajo Realizado: "),0,0,"L");
        $pdf .= Fpdf::Line(32,195,205,195);
        $pdf .= Fpdf::Line(5,202,205,202);
        $pdf .= Fpdf::Line(5,209,205,209);
        $pdf .= Fpdf::SetXY(5,215);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Recomendaciones: "),0,0,"L");
        $pdf .= Fpdf::Line(33,219,205,219);
        $pdf .= Fpdf::SetXY(5,225);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Nombre y Firma del Técnico: "),0,0,"L");
        $pdf .= Fpdf::Line(45,228,150,228);
        $pdf .= Fpdf::SetXY(150,225);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Fecha listo para Entregar: "),0,0,"L");
        $pdf .= Fpdf::Line(186,228,205,228);
        $pdf .= Fpdf::SetXY(5,230);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Notificado el cliente via : (X) Email,  ( ) Teléfono,  ( ) SMS, ( ) Whatapp,   Fechas:_____________________________________________"),0,0,"L");

        $pdf .= Fpdf::SetXY(25,243);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Firma cliente recibido conforme: "),0,0,"L");
        $pdf .= Fpdf::Line(70,247,110,247);

        $pdf .= Fpdf::SetXY(125,243);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Fecha recibido: ").Carbon::now()->format('d-m-Y'),0,0,"L");
        $pdf .= Fpdf::Line(147,247,165,247);

        $pdf .= Fpdf::SetXY(25,253);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Entregado por:________________________________ Precio Total:  ₡"),0,0,"L");
        $pdf .= Fpdf::Line(115,257,140,257);
        $pdf .= Fpdf::SetXY(140,253);
        $pdf .= Fpdf::Cell(25,5,utf8_decode("Código:__________     Sello trabajo pagado"),0,0,"L");

        /** Contenido del Documento*/

        $pdf .= Fpdf::SetFont('Arial','I',8);
        $pdf .= Fpdf::SetXY(105,140);
        $pdf .= Fpdf::MultiCell(100,3,utf8_decode("Entiendo que todo trabajo técnico cuenta con un mes de garantía sobre defectos de la misma reparación, y no otro componente no trabajado.
    Luego de ser notificado por teléfono, SMS, WhatsApp o Correo electrónico, cuento con un mes de tiempo para retirar el equipo, luego de este tiempo,
    si quiero retirar el equipo, debo cancelar un monto por ₡20000 por cada mes de retraso, sin excepción, por concepto de bodegaje adicional al costo de la reparación,
    hasta un máximo de 5 meses, luego de este tiempo no podré hacer ningún tipo de reclamo ya que cedo la propiedad del mismo a SITE Technology."),0,"J");


        Fpdf::Output();
        exit;
    }
}