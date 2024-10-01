<?php

namespace AccountHon\Http\Controllers\Restaurant;

use AccountHon\Repositories\ReceiptRepository;
use AccountHon\Repositories\Restaurant\CommissionAgentRepository;
use AccountHon\Traits\Convert;
use Carbon\Carbon;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;

class CommissionAgentsController extends Controller
{
    use Convert;
    /**
     * @var CommissionAgentRepository
     */
    private $commissionAgentRepository;

    /**
     * CommissionAgentsController constructor.
     * @param CommissionAgentRepository $commissionAgentRepository
     */
    public function __construct(CommissionAgentRepository $commissionAgentRepository)
    {

        $this->commissionAgentRepository = $commissionAgentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissionAgents = $this->commissionAgentRepository->allFilterScholl();
        return view('restaurant.commissionAgent.index',compact('commissionAgents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.commissionAgent.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commissionAgentAjax = $this->convertionObjeto();

        $commissionAgent = $this->CreacionArray($commissionAgentAjax,'CommissionAgent');
        $commissionAgent['commission']= $commissionAgent['commission']/100;
        $commissionAgent['date_of_inscription']= Carbon::now()->format('Y-m-d');
        $commissionAgentNew = $this->commissionAgentRepository->getModel();
        if($commissionAgentNew->isValid($commissionAgent)):
            $commissionAgentNew->fill($commissionAgent);
            $commissionAgentNew->save();
            return $this->exito('Se guardo con Exito el Comisionista');
        endif;
        return $this->errores($commissionAgentNew->errors);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $commissionAgent = $this->commissionAgentRepository->token($token);
        return view('restaurant.commissionAgent.edit',compact('commissionAgent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $commissionAgentAjax = $this->convertionObjeto();

        $commissionAgent = $this->CreacionArray($commissionAgentAjax,'CommissionAgent');
        $commissionAgent['commission']= $commissionAgent['commission']/100;
        $commissionAgent['date_of_inscription']= Carbon::now()->format('Y-m-d');
        $commissionAgentNew = $this->commissionAgentRepository->token($commissionAgent['token']);
        if($commissionAgentNew->isValid($commissionAgent)):
            $commissionAgentNew->fill($commissionAgent);
            $commissionAgentNew->update();
            return $this->exito('Se guardo con Exito el Comisionista');
        endif;
        return $this->errores($commissionAgentNew->errors);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
