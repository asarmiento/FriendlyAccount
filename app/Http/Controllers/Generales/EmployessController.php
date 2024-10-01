<?php

namespace AccountHon\Http\Controllers\Generales;

use AccountHon\Repositories\General\EmployesRepository;
use AccountHon\Repositories\General\EmployesScheduleRepository;
use AccountHon\Repositories\TypeUserRepository;
use AccountHon\Repositories\UsersRepository;
use AccountHon\Traits\Convert;
use Illuminate\Http\Request;

use AccountHon\Http\Requests;
use AccountHon\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployessController extends Controller
{
    use Convert;
    /**
     * @var EmployesRepository
     */
    private $employesRepository;
    /**
     * @var TypeUserRepository
     */
    private $typeUserRepository;
    /**
     * @var UsersRepository
     */
    private $usersRepository;
    /**
     * @var EmployesScheduleRepository
     */
    private $employesScheduleRepository;

    /**
     * EmployessController constructor.
     * @param EmployesRepository $employesRepository
     * @param TypeUserRepository $typeUserRepository
     * @param UsersRepository $usersRepository
     */
    public function __construct(
        EmployesRepository $employesRepository,
        TypeUserRepository $typeUserRepository,
        UsersRepository $usersRepository,
        EmployesScheduleRepository $employesScheduleRepository
    )
    {

        $this->employesRepository = $employesRepository;
        $this->typeUserRepository = $typeUserRepository;
        $this->usersRepository = $usersRepository;
        $this->employesScheduleRepository = $employesScheduleRepository;
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 13/07/16 10:14 PM   @Update 0000-00-00
    ***************************************************
    * @Description: Estamos enviando todos los empleados
    *   de la institucion que se trabaja en el momento
    *
    *
    * @Pasos:
    *
    *
    * @return view
    ***************************************************/
    public function index()
    {
       $employess = $this->employesRepository->allFilterScholl();
        return view('general.employess.index',compact('employess'));
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 13/07/16 10:14 PM   @Update 0000-00-00
    ***************************************************
    * @Description: Mostramos el formulario para crear
    * empleados
    *
    *
    * @Pasos:
    *
    *
    * @return view
    ***************************************************/
    public function create()
    {
        return view('general.employess.create');
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 13/07/16 10:35 PM   @Update 0000-00-00
    ***************************************************
    * @Description: Guardamos los datos del empleado
    *
    *
    *
    * @Pasos:
    *
    *
    * @return json
    ***************************************************/
    public function store()
    {
        $dataEmployess = $this->convertionObjeto();
        DB::beginTransaction();
        try {
            $employessArray = $this->CreacionArray($dataEmployess, 'Employess');

            $user =$this->userSave($employessArray);
            if($user['success']==false):
                DB::rollback();
                return $this->errores($user['message']->errors);
            endif;
            $employessArray['user_id']=$user['message'];

            $employes = $this->employesRepository->getModel();

            if($employes->isValid($employessArray)):
                $employes->fill($employessArray);
                $employes->save();
                DB::commit();
                return $this->exito('Se guardos con exito los el Empleado'.$employes->nameComplete());
            endif;
            DB::rollback();
            return $this->errores($employes->errors);
        }
        catch (Exception $e) {
            \Log::info('Error '.__CLASS__.' método '.__FUNCTION__);
            DB::rollback();
            return $this->errores(array('Error' => 'Errores'));
        }
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 14/07/16 12:00 AM   @Update 0000-00-00
    ***************************************************
    * @Description: En esta accion ingresamos los datos
    *   a la tabla de users para crear el usuario del
    *   empleado
    *
    * @Pasos:
    * @param $employes
    *
    * @return array
    ***************************************************/
    private function userSave($employes){

        $typeUser = $this->typeUserRepository->whereList('name','Empleado','id');

        $employes['type_user_id']=$typeUser[0];
        $employes['name']=$employes['fname'];
        $employes['last']=$employes['flast'];
        $employes['password']=Hash::make('empleado');
        $user = $this->usersRepository->getModel();

            /* Validamos los datos para guardar tabla menu */
            if ($user->isValid((array)$employes)):
                $user->fill($employes);
                $user->save();
                return ['success'=>true,'message'=>$user->id];
                DB::commit();
            endif;
        return ['success'=>false,'message'=>$user];


    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 14/07/16 12:53 AM   @Update 0000-00-00
    ***************************************************
    * @Description: Recibimos un token y buscamos los
    *   datos del empleado, y los enviamos directo
    *   para la vista.
    *
    * @Pasos:
    *
    *
    * @return view
    ***************************************************/
    public function edit($token)
    {
        $employe = $this->employesRepository->token($token);
        return view('general.employess.edit',compact('employe'));
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 14/07/16 12:55 AM   @Update 0000-00-00
    ***************************************************
    * @Description: Con esta accion actualizamos los datos
    *   del empleado 
    *
    *
    * @Pasos:
    *
    *
    * @return json
    ***************************************************/
    public function update($token)
    {
        $dataEmployess = $this->convertionObjeto();
        DB::beginTransaction();
        try {
            $employessArray = $this->CreacionArray($dataEmployess, 'Employess');

            $employes = $this->employesRepository->token($token);
            $employessArray['user_id'] = $employes->user_id;

            if($employes->isValid($employessArray)):
                $employes->fill($employessArray);
                $employes->save();
                DB::commit();
                return $this->exito('Se Actualizo con exito el Empleado: '.$employes->nameComplete());
            endif;
            DB::rollback();
            return $this->errores($employes->errors);
        }
        catch (Exception $e) {
            \Log::info('Error '.__CLASS__.' método '.__FUNCTION__);
            DB::rollback();
            return $this->errores(array('Error' => 'Errores'));
        }
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 21/08/16 10:40 PM   @Update 0000-00-00
    ***************************************************
    * @Description: Mostramos la pantalla para ver el
    * ingresos de horas de trabajo del empleado
    *
    *
    * @Pasos:
    *
    *
    * @return view
    ***************************************************/
    public function times(){
            $employess = $this->employesRepository->allFilterScholl();

        return view('general.employess.times',compact('employess'));
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 21/08/16 11:38 PM   @Update 0000-00-00
    ***************************************************
    * @Description: guardamos la hora registrada ya sea
    *   entrada o en la salida
    *
    *
    * @Pasos:
    *
    *
    * @return Json
    ***************************************************/
    public function timesStore()
    {
        $data = $this->convertionObjeto();

        $dataEmployes = $this->CreacionArray($data, 'Employess');
        $employes = $this->employesRepository->token($dataEmployes['token']);
        if(is_object($employes)):
        $employes->employesSchedule()->create($dataEmployes);
        return $this->exito('Se registro con Exito');
        endif;
        return $this->errores($employes);
    }

}
