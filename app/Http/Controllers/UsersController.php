<?php

namespace AccountHon\Http\Controllers;

//use AccountHon\Entities\User;
//use AccountHon\Entities\Supplier;
//use AccountHon\Entities\TypeUser;
//use AccountHon\Entities\School;
//use AccountHon\Entities\Menu;
use AccountHon\Entities\General\UserTask;
use AccountHon\Entities\School;
use AccountHon\Entities\User;
use AccountHon\Traits\Convert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use \DB;
use Crypt;
use Illuminate\Support\Facades\Hash;
use AccountHon\Repositories\UsersRepository;
use AccountHon\Repositories\SchoolsRepository;
use AccountHon\Repositories\MenuRepository;
use AccountHon\Repositories\TypeUserRepository;

class UsersController extends Controller
{

    use Convert;
    private $usersRepository;
    private $schoolsRepository;
    private $menuRepository;
    private $typeUserRepository;

    /**
     * Create a new controller instance.
     * @param UsersRepository $usersRepository
     * @param SchoolsRepository $schoolsRepository
     * @param MenuRepository $menuRepository
     * @param TypeUserRepository $typeUserRepository
     */
    public function __construct(
        UsersRepository $usersRepository,
        SchoolsRepository $schoolsRepository,
        MenuRepository $menuRepository,
        TypeUserRepository $typeUserRepository
    )
    {
        $this->usersRepository=$usersRepository;
        $this->schoolsRepository=$schoolsRepository;
        $this->menuRepository=$menuRepository;
        $this->typeUserRepository=$typeUserRepository;
        set_time_limit(0);
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users=$this->usersRepository->withTrashedOrderBy('name','ASC');

        return View('users.index',compact('users'));
    }

    public function indexRole()
    {
        $users=$this->usersRepository->orderBy('id','ASC');

        return View('roles.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $typeUsers=$this->typeUserRepository->orderBy('name','ASC');
        $schools=$this->schoolsRepository->orderBy('name','ASC');
        $menus=$this->menuRepository->orderBy('name','ASC');
        /* Con este methodo creamos el archivo Json */
        $this->fileJsonUpdate();
        return View('users.create',compact('typeUsers','schools','menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        /* Capturamos los datos enviados por ajax */
        $users=$this->convertionObjeto();
        /* Creamos un array para cambiar nombres de parametros */
        $Validation=$this->CreacionArray($users,'User');
        $Validation['type_user_id']=$users->typeUserIdUser;
        $Validation['password']=bcrypt($users->passwordUser);

        /* Declaramos las clases a utilizar */
        $user=$this->usersRepository->getModel();

        DB::beginTransaction();
        try {
            /* Validamos los datos para guardar tabla menu */
            if ($user->isValid((array)$Validation)):
                $user->fill($Validation);
                $user->save();
                /* Traemos el id del ultimo registro guardado */
                $schoolsUser=$users->schoolsUser;

                for ($i=0; $i < count($schoolsUser); $i++):
                    if (empty($schoolsUser[$i])):
                        return $this->errores('Debe Seleccionar una Institución como minimo');
                    endif;
                    /* Comprobamos cuales estan habialitadas y esas las guardamos */
                    $Relacion=$this->usersRepository->find($user->id);
                    if ($users->schoolsUser[$i]) {
                        $Relacion->schools()->attach($users->schoolsUser[$i]);
                    }
                endfor;

                /* Comprobamos si viene activado o no para guardarlo de esa manera */
                if ($users->statusUser == true):
                    $this->usersRepository->withTrashedFind($user->id)->restore();
                else:
                    $this->usersRepository->destroy($user->id);
                endif;
                DB::commit();
                /* Enviamos el mensaje de guardado correctamente */
                return $this->exito('Los datos se guardaron con exito!!!');
            endif;
        } catch (Exception $e) {
            \Log::info('Error '.__CLASS__.' método '.__FUNCTION__);
            DB::rollback();
            return $this->errores(array('Error'=>'Errores'));
        }

        /* Enviamos el mensaje de error */
        return $this->errores($user->errors);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user=$this->usersRepository->withTrashedFind($id);
        $typeUsers=$this->typeUserRepository->orderBy('name','ASC');
        $schools=$this->schoolsRepository->orderBy('name','ASC');
        $menus=$this->menuRepository->orderBy('name','ASC');
        /* Con este methodo creamos el archivo Json */
        $this->fileJsonUpdate();
        return view('users.edit',compact('user','typeUsers','schools','menus'));
    }

    public function editRole($id)
    {
        $user=$this->usersRepository->find($id);
        $menus=$this->menuRepository->orderBy('name','ASC');

        return view('roles.edit',compact('user','menus'));
    }

    public function updateRole()
    {


        $roles=$this->convertionObjeto();

        $Menus=$roles->roles;
        /*  $user = $this->usersRepository->withTrashedFind($roles->idUser);
          $user->Tasks()->detach();*/

        foreach ($Menus as $idMenu=>$value):

            if ($value != null) {
                if ($idMenu > 0):
                    $statusTask=$value->statusTasks;
                    for ($e=0; $e < count($statusTask); $e++):
                        /* Comprobamos cuales estan habialitadas y esas las guardamos */
                        $user=$this->usersRepository->find($roles->idUser);
                        Log::info(json_encode(['task_id',$value->idTasks[$e],'menu_id',$idMenu,'user_id',$roles->idUser,
                            'status'=>$value->statusTasks[$e]]));
                        if (UserTask::where('task_id',$value->idTasks[$e])->where('menu_id',$idMenu)->where('user_id',$roles->idUser)->count() > 0) {
                            $ed=UserTask::where('task_id',$value->idTasks[$e])->where('menu_id',$idMenu)->where('user_id',$roles->idUser)->update(['status' =>$value->statusTasks[$e],
                                                                                                                                                   'task_id'=>$value->idTasks[$e]]);
                            //  echo json_encode(['menu_id' => $idMenu, 'status' => $value->statusTasks[$e],'task_id'=>$value->idTasks[$e]]);
                        } else {
                            UserTask::create(['task_id'=>$value->idTasks[$e],'menu_id'=>$idMenu,'user_id'=>$roles->idUser,
                                'status'=>$value->statusTasks[$e]]);

                        }
                    endfor;
                endif;

            }
        endforeach;

        return $this->exito('Los datos se guardaron con exito!!!');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {

        /* Capturamos los datos enviados por ajax */
        $users=$this->convertionObjeto();
        /* obtenemos dos datos del supplier mediante token recuperamos el id */
        $user=User::find($users->idUser);

        /* Creamos un array para cambiar nombres de parametros */
        //  $Validation = $this->CreacionArray($users, 'User');
        $Validation['type_user_id']=$users->idTypeUser;
        $Validation['password']=$user->password;


        /* Validamos los datos para guardar tabla menu */
        if ($user->fill($Validation)):

            $user->update();

            $schoolsUser=$users->schoolsUser;
            $Relacion=$this->usersRepository->find($id);
            if (!$Relacion->schools->isEmpty()):
                $Relacion->schools()->detach();
            endif;

            for ($i=0; $i < count($schoolsUser); $i++):
                if (empty($schoolsUser[$i])):
                    return $this->errores('Debe Seleccionar una Institución como minimo');
                endif;

                /* Comprobamos cuales estan habialitadas y esas las guardamos */
                $Relacion->schools()->attach($schoolsUser[$i]);

            endfor;
            /* Enviamos el mensaje de guardado correctamente */
            return $this->exito('Los datos se guardaron con exito!!!');
        endif;
        /* Enviamos el mensaje de error */
        return $this->errores($user->errors);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy()
    {
        /* Capturamos los datos enviados por ajax */
        $users=$this->convertionObjeto();
        /* les damos eliminacion pasavida */
        $data=$this->usersRepository->find($users->idUser)->delete();
        if ($data):
            /* si todo sale bien enviamos el mensaje de exito */
            return $this->exito('Se desactivo con exito!!!');
        endif;
        /* si hay algun error  los enviamos de regreso */
        return $this->errores($data->errors);
    }

    /**
     * Restore the specified typeuser from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function active()
    {
        /* Capturamos los datos enviados por ajax */
        $users=$this->convertionObjeto();
        /* les quitamos la eliminacion pasavida */
        $data=$this->usersRepository->withTrashedFind($users->idUser)->restore();
        if ($data):
            /* si todo sale bien enviamos el mensaje de exito */
            return $this->exito('Se Activo con exito!!!');
        endif;
        /* si hay algun error  los enviamos de regreso */
        return $this->errores($data->errors);
    }

    public function password()
    {
        return view('general.users.changePassword');
    }

    public function updatePassword()
    {

        $datos=$this->convertionObjeto();
        if ($datos->password == $datos->confirmPassword):
            $password=bcrypt($datos->password);

            $user=$this->usersRepository->find(currentUser()->id);
            $user->password=$password;
            $user->update();
            return $this->exito('Se cambio con exito la contraseña');


        endif;

        return $this->errores('Las contraseñas no son iguales');

    }
}
