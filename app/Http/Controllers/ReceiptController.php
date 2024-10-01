<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\AccountingReceipt;
use AccountHon\Entities\Cash;
use AccountHon\Http\Controllers\Controller;
use AccountHon\Http\Requests;
use AccountHon\Repositories\CashRepository;
use AccountHon\Repositories\CatalogRepository;
use AccountHon\Repositories\DepositRepository;
use AccountHon\Repositories\ReceiptRepository;
use AccountHon\Repositories\TypeFormRepository;
use AccountHon\Repositories\TypeSeatRepository;
use AccountHon\Traits\Convert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Date\Date;

class ReceiptController extends Controller
{
    use Convert;
    /**
     * @var CatalogRepository
     */
    private $catalogRepository;
    /**
     * @var TypeSeatRepository
     */
    private $typeSeatRepository;
    /**
     * @var DepositRepository
     */
    private $depositRepository;
    /**
     * @var CashRepository
     */
    private $cashRepository;
    /**
     * @var TypeFormRepository
     */
    private $typeFormRepository;
    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;

    /**
     * @param ReceiptRepository $receiptRepository
     * @param CatalogRepository $catalogRepository
     * @param TypeSeatRepository $typeSeatRepository
     * @param DepositRepository $depositRepository
     * @param CashRepository $cashRepository
     * @param TypeFormRepository $typeFormRepository
     */
    public function __construct(
        ReceiptRepository $receiptRepository,
        CatalogRepository $catalogRepository,
        TypeSeatRepository $typeSeatRepository,
        DepositRepository $depositRepository,
        CashRepository $cashRepository,
        TypeFormRepository $typeFormRepository
    ){

        $this->catalogRepository = $catalogRepository;
        $this->typeSeatRepository = $typeSeatRepository;
        $this->depositRepository = $depositRepository;
        $this->cashRepository = $cashRepository;
        $this->typeFormRepository = $typeFormRepository;
        $this->receiptRepository = $receiptRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $catalogs = $this->catalogRepository->listsWhere('style', 'detalle', 'id');
        $receipts = $this->receiptRepository->whereInOrder('status', 'aplicado', 'catalog_id' ,$catalogs);

        return View('receipts.index', compact('receipts'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2015-00-00
    |@Date Update: 2016-01-12
    |---------------------------------------------------------------------
    |@Description:
    |
    |
    |@Pasos:
    |
    |
    |
    |
    |
    |
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function create()
    {
        $types    = $this->typeFormRepository->getModel()->all();
        if(!userSchool()):
            $this->saveLogs('no existe periodo contable',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            abort(503);
        endif;
        $typeSeat = $this->typeSeatRepository->whereDuoData('RC');
        if($typeSeat->isEmpty()):
            $this->saveLogs('No existe el tipo de asiento',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            abort(503, 'Debe crear el tipo de asiento RC si no tiene autorización contactese con soporte');
        endif;
        $catalogs = $this->catalogRepository->whereDuo('school_id',userSchool()->id,'style','Detalle','code','ASC');
        if($catalogs->isEmpty()):
            $this->saveLogs('no existe las cuentas',__CLASS__,__FUNCTION__,__LINE__,__DIR__);
            abort(503,'Debe Crear las cuentas del catalogo');
        endif;
        $receipts = $this->receiptRepository->whereDuo('status','no aplicado','type_seat_id',$typeSeat[0]->id,'id','ASC');
        $banks = $this->catalogRepository->accountNameSchool('BANCOS');

        /**pendiente solo debe enviar los estudiantes de la institucion*/
        $total = 0;

        if(!$receipts->isEmpty()):
            $total = $this->receiptRepository->getModel()->where('receipt_number',$receipts[0]->receipt_number)->where('type_seat_id',$typeSeat[0]->id)->sum('amount');
        endif;



        return View('receipts.create', compact('types','typeSeat','catalogs','receipts','total','banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try{
            $receipt      = $this->convertionObjeto();
            $typeSeat     = $this->typeSeatRepository->whereDuoData('RC');
            $verification = $this->receiptRepository->getModel()->where('receipt_number', $receipt->receiptNumberReceipt)
                ->where('type_seat_id',$typeSeat[0]->id)->get();

            if(!$verification->isEmpty()):
                if (count($verification) == 5):
                    return $this->errores(['message'=>'Solo se permiten 5 movimientos por recibos']);
                endif;
            endif;

            $Validation = $this->CreacionArray($receipt, 'Receipt');
            $Validation['line']= 1;
            if($verification->count()>0):
                $Validation['token'] = $verification[0]->token;
                $Validation['date']  = $verification[0]->date;
                $Validation['line']  = $verification[0]->line+1;
            endif;

            //Se usa o no?
            $type = $this->typeFormRepository->oneWhere('name','Credito','id');
            $Validation['type_id']= $type[0]->id;

            /* Creamos un array para cambiar nombres de parametros */
            $Validation['user_created']         = Auth::user()->id;
            $catalog                            = $this->catalogRepository->token($Validation['catalog']);
            $Validation['catalog_id']           = $catalog->id;
            $Validation['status']               = 'no aplicado';
            $Validation['receipt_number']       = $Validation['receiptNumber'];
            $Validation['received_from']        = $Validation['receivedFrom'];
            $Validation['accounting_period_id'] = periodSchool()->id;
            $Validation['type_seat_id']         = $typeSeat[0]->id;

            /* Declaramos las clases a utilizar */
            $receipts = $this->receiptRepository->getModel();
            /* Validamos los datos para guardar tabla menu */
            if ($receipts->isValid($Validation)):
                $receipts->fill($Validation);
                $receipts->save();

                $total = $this->receiptRepository->getModel()->where('receipt_number',$receipts->receipt_number)->sum('amount');
                return $this->exito(['token'=>$Validation['token'],'id'=>$receipts->id,'total'=>$total]);
            endif;
            /* Enviamos el mensaje de error */
            return $this->errores($receipts->errors);
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('receipt Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }



    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     * @internal param int $id
     */
    public function status()
    {
        try{
            $deposits = $this->convertionObjeto();
            $receipt = $this->receiptRepository->token($deposits->token);
            //Aquí
            $typeSeat = $this->typeSeatRepository->whereDuoData('RC');

            //array depositos validos
            $deposits_valids = array();

            $sumDeposit=0;

            //Validando el total
            $total = $this->receiptRepository->getModel()->where('receipt_number',$receipt->receipt_number)->where('type_seat_id',$typeSeat[0]->id)->sum('amount');

            if( $deposits->numberDepositAuxiliaryReceipt[0] != null || $deposits->numberDepositAuxiliaryReceipt[0] != '' ){
                //Validación de datos
                for($i=0;$i<count($deposits->numberDepositAuxiliaryReceipt);$i++):
                    $validation = array('number'=>$deposits->numberDepositAuxiliaryReceipt[$i],
                        'date'=>$deposits->dateDepositAuxiliaryReceipt[$i],
                        'catalog_id'=>$this->catalogRepository->token($deposits->accountDepositAuxiliaryReceipt[$i])->id,
                        'amount'=>$deposits->amountDepositAuxiliaryReceipt[$i],
                        'school_id'=>userSchool()->id,
                        'token'=>Crypt::encrypt($deposits->numberDepositAuxiliaryReceipt[$i]),
                        'codeReceipt'=>$receipt->receipt_number
                    );
                    $deposit = $this->depositRepository->getModel();

                    if($deposit->isValid($validation)):
                        array_push($deposits_valids, $validation);
                    else:
                        return $this->errores($deposit->errors);
                    endif;
                endfor;

                //validate date and number
                if( !$this->validateDeposits($deposits) ){
                    return $this->errores('No se pueden registrar los datos, existen depósitos duplicados.');
                }
                foreach($deposits->amountDepositAuxiliaryReceipt AS $suma):
                    $sumDeposit += $suma;
                endforeach;
              
                if($total < $sumDeposit):
                    return $this->errores(array('receipt Save' => 'Los depositos no pueden ser de mayor cantidad que el recibo'));
                endif;

                DB::beginTransaction();
                foreach ($deposits_valids as $key => $value) {
                    $deposit = $this->depositRepository->getModel();
                    $deposit->fill($value);
                    $deposit->save();
                }
                DB::commit();
            }

            if($total > $sumDeposit):
                $diferent =  $total - $sumDeposit;
                $cash = $this->cashRepository->getModel();
                $cashs = ['amount'=>$diferent,'receipt'=>$receipt->receipt_number,'school_id'=>userSchool()->id];
                if($cash->isValid($cashs)):
                    $cash->fill($cashs);
                    $cash->save();
                endif;
            endif;

            $receipt = $this->receiptRepository->updateWhere($deposits->token,'aplicado','status');
            if($receipt>0):
                $this->typeSeatRepository->updateWhere('RC',userSchool()->id);

                return $this->exito(['msg' => "Se ha aplicado con exito!!!", 'data' => $this->report($deposits->token)]);
            endif;
            return $this->errores('No se puedo Aplicar el asiento, si persiste contacte soporte');
        }catch (Exception $e) {
            \Log::error($e);
            return $this->errores(array('receipt Save' => 'Verificar la información del asiento, sino contactarse con soporte de la applicación'));
        }
    }

    /**
     * [view description]
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function view($token){
        $receipts = $this->receiptRepository->oneWhere('token', $token, 'id');
        $deposits = $this->depositRepository->oneWhere('codeReceipt', $receipts[0]->receipt_number, 'id');
        $deposits_numbers = '';
        if(!$deposits->isEmpty()){
            foreach ($deposits as $key => $deposit) {
                $deposits_numbers .= $deposit->number.', ';
            }
            $deposits_numbers = substr($deposits_numbers, 0, -2).'.';
        }

        $total = $this->receiptRepository->getModel()->where('receipt_number',$receipts[0]->receipt_number)->where('type_seat_id', $receipts[0]->type_seat_id)->sum('amount');
        return View('receipts.view', compact('receipts','total', 'deposits_numbers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteDetail($id)
    {
        $receipt= $this->receiptRepository->destroy($id);
        if($receipt==1):
            $typeSeat = $this->typeSeatRepository->whereDuoData('RC');
            $receipts = $this->receiptRepository->whereDuo('status','no aplicado','type_seat_id',$typeSeat[0]->id,'status','no aplicado');
            $total    = 0;
            $aux = AccountingReceipt::find($id);
            $cash = Cash::where('receipt', $aux->receipt_number)->where('school_id', userSchool()->id);
            if ($cash != null) {
                $cash->delete();
            }
            $dep = Deposit::where('receipt', $aux->receipt_number)->where('school_id', userSchool()->id)->delete();
            if ($dep != null) {
                $dep->delete();
            }
            if(!$receipts->isEmpty()):
                $total = $this->receiptRepository->getModel()->where('receipt_number',$receipts[0]->receipt_number)->sum('amount');
            endif;
            return $this->exito(['total'=>$total, 'message' => 'Se ha eliminado con éxito']);
        endif;
        return $this->errores(['No se puedo eliminar la fila, si persiste contacte soporte']);
    }

    private function validateDeposits($deposits){
        $date = $deposits->dateDepositAuxiliaryReceipt;
        $ref  = $deposits->numberDepositAuxiliaryReceipt;

        $duplicatesDate = $this->get_keys_for_duplicate_values($date);

        foreach ($duplicatesDate as $key => $position) {
            $auxRef = null;
            foreach ($position as $keyPos => $valuePos) {
                if($ref[$valuePos] == $auxRef){
                    return false;
                }
                $auxRef = $ref[$valuePos];
            }
        }

        return true;
    }

    private function get_keys_for_duplicate_values($my_arr) {
        $duplicates = array_count_values($my_arr);

        $new_array = array();
        foreach ($duplicates as $key => $value) {
            if($value > 1){
                array_push($new_array, $key);
            }
        }

        $dups = array();
        foreach ($my_arr as $keyMy => $valueMy) {
            foreach ($new_array as $key => $value) {
                if($value == $valueMy){
                    if (isset($dups[$valueMy])) {
                        $dups[$valueMy][] = $keyMy;
                    } else {
                        $dups[$valueMy] = array($keyMy);
                    }
                }
            }
        }
        return $dups;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function report($token)
    {
        $dataReceipt = $this->receiptRepository->allToken($token);
        $totalReceipt = $this->receiptRepository->sumToken($token);
        $deposits = $this->depositRepository->whereDuo('codeReceipt', $dataReceipt[0]->receipt_number, 'school_id', userSchool()->id,'id','ASC');
        $deposits_numbers = '';
        if(!$deposits->isEmpty()){
            foreach ($deposits as $key => $deposit) {
                $deposits_numbers .= $deposit->number.', ';
            }
            $deposits_numbers = substr($deposits_numbers, 0, -2).'.';
        }
        Date::setLocale('es');
        $date = Date::now()->format('j F Y');
        $totalReceipt = $totalReceipt;
        
        return view('receipts.print',compact('dataReceipt', 'date', 'totalReceipt'))->render();

        //$pdf = \PDF::loadView('receipts.report', compact('dataReceipt','totalReceipt', 'deposits_numbers'))->setOrientation('portrait');

        //return $pdf->stream("Impresion - $dataReceipt[0]->receipt_number.pdf");
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-09-14   @Update 0000-00-00
     ***************************************************
     * @Description: Estamos anulando el recibo que se
     *   desea
     *
     *
     * @Pasos:
     *
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     **************************************************/
    public function anular($token)
    {

        $recibo = $this->receiptRepository->token($token);
        $this->depositRepository->getModel()->where('school_id',userSchool()->id)->where('codeReceipt',$recibo->receipt_number)
            ->update(['amount'=>0]);
        $this->cashRepository->getModel()->where('school_id',userSchool()->id)->where('receipt',$recibo->receipt_number)
            ->update(['amount'=>0]);
        $this->receiptRepository->getModel()->where('token',$token)
            ->update(['amount'=>0,'detail'=>'anulado']);
        return back();
    }
}
