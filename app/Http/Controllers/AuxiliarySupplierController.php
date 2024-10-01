<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Repositories\Accounting\SupplierRepository;
use AccountHon\Repositories\AccountingPeriodRepository;
use AccountHon\Repositories\AuxiliarySupplierRepository;

use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use Illuminate\Support\Facades\Auth;


class AuxiliarySupplierController extends Controller
{
    use Convert;
    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var AuxiliarySupplierRepository
     */
    private $auxiliarySupplierRepository;
    /**
     * @var AccountingPeriodRepository
     */
    private $accountingPeriodRepository;

    /**
     * @param \AccountHon\Repositories\Accounting\SupplierRepository $supplierRepository
     * @param TypeFormRepository $typeFormRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param \AccountHon\Repositories\AuxiliarySupplierRepository $auxiliarySupplierRepository
     * @param AccountingPeriodRepository $accountingPeriodRepository
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        TypeFormRepository $typeFormRepository,
        TypeSeatRepository $typeSeatRepository,
        AuxiliarySupplierRepository $auxiliarySupplierRepository,
        AccountingPeriodRepository $accountingPeriodRepository

    )
    {
        set_time_limit(0);
        $this->middleware('auth');
        $this->middleware('sessionOff');
        $this->supplierRepository = $supplierRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->auxiliarySupplierRepository = $auxiliarySupplierRepository;
        $this->accountingPeriodRepository = $accountingPeriodRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $typeSeat = $this->typeSeatRepository->whereDuoData('CPA');

        if($typeSeat->isEmpty()):
            Log::warning('No existe tipo de asiento CPA: en la institucion '.userSchool()->name);
            abort(500,'prueba');
        endif;
        $auxiliarySuppliers = $this->auxiliarySupplierRepository->whereDuo('status', 'aplicado','type_seat_id',$typeSeat[0]->id,'id','ASC');
        // echo json_encode($auxiliarySeats); die;
        return View('auxiliarySupplier.index', compact('auxiliarySuppliers'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store()
    {
        $seatings= $this->convertionObjeto();
        $debito    = $this->typeFormRepository->nameAllDataType('debito');
        $token = bcrypt($debito->id);

        $type = 'contado';
        $dateExpiration=null;
        if($seatings->typeBuy==2):
            $type = 'credito';
            $dateExpiration= $seatings->dateExpirationBuy;
        endif;

        if($seatings->totalExcentoBuy > 0):
            $mountBuy = ['Productos exento  ' => $seatings->totalExcentoBuy];
        endif;

        if($seatings->totalGravadoBuy > 0):
            $mountBuy['Productos  gravados'] =  $seatings->totalGravadoBuy;
        endif;

        if($seatings->otherBuy > 0):
            $mountBuy['Productos otros '] =  $seatings->otherBuy;
        endif;

        if($seatings->ivaBuy > 0):
            $mountBuy['impuesto de Productos gravados '] = $seatings->ivaBuy;
        endif;
        $typeId ='';
        if($seatings->discountBuy > 0):
            $typeId = $debito->id;
            $mountBuy['Descuento en compra '] = $seatings->discountBuy;
        endif;

        if($seatings->subsidizedBuy > 0):
            $typeId = $debito->id;
            $mountBuy['Bonificado en compra '] = $seatings->subsidizedBuy;
        endif;

        foreach($mountBuy AS $key => $value):
            $data = $this->generationSeat($seatings,$value,$key,$type,$token,$typeId,$dateExpiration);
            $seating = $this->auxiliarySupplierRepository->getModel();
            if ($seating->isValid($data)):
                $seating->fill($data);
                $seating->save();
            endif;
        endforeach;

        return $this->errores($seating->errors);
    }

    public function generationSeat($seatings,$amount,$message,$type,$token,$typeId,$dateExpiration){
        $supplier = $this->supplierRepository->token($seatings->supplierBuy);
        $typeSeat=$this->typeSeatRepository->whereDuoData('ACOMP');
        $seat=   [
            'date'=>dateShort(),
            'code'=>$typeSeat[0]->abbreviation(),
            'detail'=>$message .' '.$seatings->dateBuy,
            'dateExpiration'=>$dateExpiration,
            'dateBuy'=>$seatings->dateBuy,
            'bill'=>$seatings->referenceBuy,
            'type'=>$type,
            'amount'=>$amount,
            'supplier_id'=>$supplier->id,
            'type_seat_id'=>$typeSeat[0]->id,
            'accounting_period_id'=>periodSchool()->id,
            'type_id'=>'',
            'token'=>$token,
            'status'=>'aplicado',
            'user_created' => Auth::user()->id
        ];

        if($message =='Descuento en compra '):
            $seat['type_id']= $typeId;
       elseif($message =='Bonificado en compra '):
           $seat['type_id']= $typeId;
       else:
            $credito    = $this->typeFormRepository->nameAllDataType('credito');
           $seat['type_id']= $credito->id;
        endif;

        return $seat;
    }

}
