<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/02/16
 * Time: 09:12 PM
 */

namespace AccountHon\Http\Controllers\Restaurant;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Repositories\Restaurant\GroupMenuRepository;
use AccountHon\Traits\Convert;

class GroupMenuController extends Controller
{
    use Convert;
    /**
     * @var GroupMenuRepository
     */
    private $groupMenuRepository;

    /**
     * GroupMenuController constructor.
     * @param GroupMenuRepository $groupMenuRepository
     */
    public function __construct(
        GroupMenuRepository $groupMenuRepository
    )
    {

        $this->groupMenuRepository = $groupMenuRepository;
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-02-03
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
        $groupMenus = $this->groupMenuRepository->allFilterScholl();

        return view('restaurant.groupMenu.index',compact('groupMenus'));
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-01-03
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
        return view('restaurant.groupMenu.create');
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-02-03
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
        $datos = $this->convertionObjeto();

        $dataGroupMenu = $this->CreacionArray($datos,'GroupMenu');
        $very = $this->groupMenuRepository->allInstitution('name',$dataGroupMenu['name']);
        if(!$very->isEmpty()):
            return $this->errores('No se permiten nombre iguales');
        endif;
        $groupMenu = $this->groupMenuRepository->getModel();

        if($groupMenu->isValid($dataGroupMenu)):
            $groupMenu->fill($dataGroupMenu);
            $groupMenu->save();
            return $this->exito('Se guardo con exito el Grupo');
        endif;

        return $this->errores($groupMenu->errors);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-02-03
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
    public function edit($token)
    {
        $groupMenu = $this->groupMenuRepository->token($token);
        return view('restaurant.groupMenu.edit',compact('groupMenu'));
    }

    public function update()
    {
        $datos = $this->convertionObjeto();
        $dataGroupMenu = $this->CreacionArray($datos,'GroupMenu');
        $groupMenu = $this->groupMenuRepository->token($datos->tokenGroupMenu);

        if($groupMenu->isValid($dataGroupMenu)):
            $groupMenu->fill($dataGroupMenu);
            $groupMenu->update();
            return $this->exito('Se guardo con exito el Grupo');
        endif;

        return $this->errores($groupMenu->errors);
    }
}