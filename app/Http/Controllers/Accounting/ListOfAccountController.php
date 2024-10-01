<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 18:46
 */

namespace AccountHon\Http\Controllers\Accounting;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Accounting\ListOfAccountRepository;
use AccountHon\Repositories\General\TypeOfCompaniesRepository;

class ListOfAccountController extends Controller
{
    /**
     * @var ListOfAccountRepository
     */
    private $listOfAccountRepository;
    /**
     * @var TypeOfCompaniesRepository
     */
    private $typeOfCompaniesRepository;

    /**
     * @param ListOfAccountRepository $listOfAccountRepository
     * @param TypeOfCompaniesRepository $typeOfCompaniesRepository
     */
    public function __construct(ListOfAccountRepository $listOfAccountRepository,
 TypeOfCompaniesRepository $typeOfCompaniesRepository)
    {

        $this->listOfAccountRepository = $listOfAccountRepository;
        $this->typeOfCompaniesRepository = $typeOfCompaniesRepository;
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 31/1/17 19:13   @Update 0000-00-00
    ***************************************************
    * @Description: Mostramos la lista de los tipos
    * de empresa que existen
    *
    *
    * @Pasos:
    *
    *
    * @return
    ***************************************************/
    public function index()
    {
        $listOfAccounts = $this->listOfAccountRepository->all();
        return view('Accounting.listOfAccount.index',compact('listOfAccounts'));
    }

        public function create()
        {
            $listOfAccounts = $this->listOfAccountRepository->getModel()->where('type','grupo')->get();
            $typeOfCompanies = $this->typeOfCompaniesRepository->all();
            return view('Accounting.listOfAccount.create',compact('listOfAccounts','typeOfCompanies'));
        }

        public function store()
        {
            $data = $this->convertionObjeto();

            $typeOfCompany = $this->CreacionArray($data,'TypeOfCompany');


            $typeOfCompanies = $this->listOfAccountRepository->getModel();

            if($typeOfCompanies->isValid($typeOfCompany)):
                $typeOfCompanies->fill($typeOfCompany);
                $typeOfCompanies->save();
                return $this->exito('Se guardo con exito');
            endif;

            return $this->errores($typeOfCompanies->errors);
        }


        public function edit($token)
        {
            $typeOfCompany = $this->listOfAccountRepository->token($token);
            return view('general.typeCompanies.edit',compact('typeOfCompany'));
        }

        public function update()
        {
            $data = $this->convertionObjeto();

            $typeOfCompany = $this->CreacionArray($data,'TypeOfCompany');


            $typeOfCompanies = $this->listOfAccountRepository->token($typeOfCompany['token']);

            if($typeOfCompanies->isValid($typeOfCompany)):
                $typeOfCompanies->fill($typeOfCompany);
                $typeOfCompanies->save();
                return $this->exito('Se guardo con exito');
            endif;

            return $this->errores($typeOfCompanies->errors);
        }
}