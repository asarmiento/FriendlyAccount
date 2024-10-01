<?php

namespace AccountHon\Http\Controllers\Generales;

use AccountHon\Entities\General\ExchangeRate;
use AccountHon\Repositories\General\ExchangeRateRepository;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;

class ExchangeRateController extends Controller
{
    /**
     * @var ExchangeRateRepository
     */
    private $exchangeRateRepository;

    /**
     * ExchangeRateController constructor.
     * @param ExchangeRateRepository $exchangeRateRepository
     */
    public function __construct(ExchangeRateRepository $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exchangeRates = $this->exchangeRateRepository->getModel()
            ->where('school_id',userSchool()->id)->orderBy('date','DESC')->get();
        return view('general.exchangeRates.create',compact('exchangeRates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Capturamos los datos enviados por ajax */
        $exchangeRate = $this->convertionObjeto();
        /* Creamos un array para cambiar nombres de parametros */
        $Validation = $this->CreacionArray($exchangeRate, 'ExchangeRate');
        /* Declaramos las clases a utilizar */
        $exchangeRates = $this->exchangeRateRepository->getModel();
        /* Validamos los datos para guardar tabla menu */
        if ($exchangeRates->isValid($Validation)):
            $exchangeRates->fill($Validation);
            $exchangeRates->save();

            /* Enviamos el mensaje de guardado correctamente */
            return $this->exito('Los datos se guardaron con exito!!!');
        endif;
        /* Enviamos el mensaje de error */
        return $this->errores($exchangeRates->errors);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($token)
    {
       $exchange= $this->exchangeRateRepository->token($token);
        if($exchange->delete()):
            return $this->exito('Se elimino con Exito');
            endif;
        return $this->errores('No se puedo eliminar ya que ha sido usado');
    }
}
