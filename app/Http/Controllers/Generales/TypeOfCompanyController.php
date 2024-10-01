<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 18:46
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\General\TypeOfCompaniesRepository;

class TypeOfCompanyController extends Controller
{
    /**
     * @var TypeOfCompaniesRepository
     */
    private $typeOfCompaniesRepository;

    /**
     * TypeOfCompanyController constructor.
     * @param TypeOfCompaniesRepository $typeOfCompaniesRepository
     */
    public function __construct(TypeOfCompaniesRepository $typeOfCompaniesRepository)
    {
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
        $typeOfCompanies = $this->typeOfCompaniesRepository->all();
        return view('general.typeCompanies.index',compact('typeOfCompanies'));
    }

    public function create()
    {
        return view('general.typeCompanies.create');
    }

    public function store()
    {
        $data = $this->convertionObjeto();

        $typeOfCompany = $this->CreacionArray($data,'TypeOfCompany');


        $typeOfCompanies = $this->typeOfCompaniesRepository->getModel();

        if($typeOfCompanies->isValid($typeOfCompany)):
            $typeOfCompanies->fill($typeOfCompany);
            $typeOfCompanies->save();
            return $this->exito('Se guardo con exito');
        endif;

        return $this->errores($typeOfCompanies->errors);
    }


    public function edit($token)
    {
        $typeOfCompany = $this->typeOfCompaniesRepository->token($token);
        return view('general.typeCompanies.edit',compact('typeOfCompany'));
    }

    public function update()
    {
        $data = $this->convertionObjeto();

        $typeOfCompany = $this->CreacionArray($data,'TypeOfCompany');


        $typeOfCompanies = $this->typeOfCompaniesRepository->token($typeOfCompany['token']);

        if($typeOfCompanies->isValid($typeOfCompany)):
            $typeOfCompanies->fill($typeOfCompany);
            $typeOfCompanies->save();
            return $this->exito('Se guardo con exito');
        endif;

        return $this->errores($typeOfCompanies->errors);
    }
}