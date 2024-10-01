<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/01/16
 * Time: 11:45 AM
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\CustomerRepository;
use AccountHon\Traits\Convert;

class CustomerController extends Controller
{
    use Convert;
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerController constructor.
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        CustomerRepository $customerRepository
    )
    {

        $this->customerRepository = $customerRepository;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-18
    |@Date Update: 2016-00-00
    |---------------------------------------------------------------------
    |@Description: Mostramos la lista de los clientes registra
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
        $customers = $this->customerRepository->allFilterScholl();
        return view('general.customers.index',compact('customers'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-18
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
        return view('general.customers.create');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-18
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
    public function store()
    {
        $data = $this->convertionObjeto();

        $customer = $this->CreacionArray($data, 'Customer');
        if(!periodSchool()):
            return $this->errores('No ha creado un periodo contable');
        endif;
        $customer['date'] = dateShort();
        $customers = $this->customerRepository->getModel();

        if ($customers->isValid($customer)):
            $customers->fill($customer);
            $customers->save();

            return $this->exito('Se guardo con exito el cliente: '.$customers->name);
        endif;

        return $this->errores($customers->errors);
    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-11-30 ${TIME}   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($token)
    {


        $customer = $this->customerRepository->token($token);

        return view('general.customers.edit',compact('customer'));

    }

    /**************************************************
     * @Author: Anwar Sarmiento Ramos
     * @Email: asarmiento@sistemasamigables.com
     * @Create: 2016-11-30 ${TIME}   @Update 0000-00-00
     ***************************************************
     * @Description:
     *
     *
     *
     * @Pasos:
     *
     *
     **************************************************
     * @param $token
     * @return
     */
    public function update()
    {
        $data = $this->convertionObjeto();

        $customer = $this->CreacionArray($data, 'Customer');

        $customers = $this->customerRepository->token($data->tokenCustomer);

        if ($customers->isValid($customer)):
            $customers->fill($customer);
            $customers->save();

            return $this->exito('Se Modifico con exito el cliente: '.$customers->name);
        endif;

        return $this->errores($customers->errors);

    }
}