<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 12/04/16
 * Time: 11:50 PM
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\CurrencyRepository;
use AccountHon\Traits\Convert;

class CurrenciesController extends Controller
{
    use Convert;
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-13
    |@Date Update: 2016-00-00
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
    public function index()
    {
        $currencies = $this->currencyRepository->allFilterScholl();
        return view('general.currencies.index',compact('currencies'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-04-13
    |@Date Update: 2016-00-00
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
        return view('general.currencies.create');
    }

    public function store()
    {
        $data = $this->convertionObjeto();

        $currency = $this->CreacionArray($data,'Currencies');

        $currencies = $this->currencyRepository->getModel();

        if($currencies->isValid($currency)):
            $currencies->fill($currency);
            $currencies->save();
            return $this->exito('Se ha Guardado con exito la nueva denominación');
        endif;
        return $this->errores($currencies->errors);
    }

    public function edit($id)
    {
        $currency = $this->currencyRepository->find($id);
        return view('general.currencies.edit',compact('currency'));
    }

    public function update()
    {
        $data = $this->convertionObjeto();

        $currency = $this->CreacionArray($data,'Currencies');

        $currencies = $this->currencyRepository->find($currency['id']);

        if($currencies->isValid((array)$currency)):
            $currencies->fill($currency);
            $currencies->save();
            return $this->exito('Se Actualizo con exito la nueva denominación');
        endif;

        return $this->errores($currencies->errors);
    }

}