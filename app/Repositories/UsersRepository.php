<?php
namespace AccountHon\Repositories;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use AccountHon\Entities\User;
use Auth;
use Crypt;
use Illuminate\Support\Facades\Hash;

/**
 * Description of UsersRepository
 *
 * @author Anwar Sarmiento
 */
class UsersRepository extends BaseRepository {
    public function getModel() {
        return new User();
    }
   /* Generar el nombre completo del usuario */

    public function nameComplete() {
        return $this->name . ' ' . $this->last;
    }

    public function validateUser($password) {
    	$users = $this->whereInSingle('type_user_id',[1,2,4,5,6]);
    	$validateUser = null;

        foreach ($users as $key => $user) {
            if(Hash::check($password,$user->password)){
                $validateUser = $user->id;
                break;
            }
        }
        if($validateUser){
            if($validateUser != \Auth::user()->id){
                //Session::put('validateUser', $validateUser);
                //return $this->exito("Usuario válido");
                return $validateUser;
            }else{
                //Session::forget('validateUser');
                return "Debe ingresar el password de otro usuario";
            }
        }else{
            //Session::forget('validateUser');
            return "Usuario no válido";
        }
    }

    public function validateOtherUser($password, $user_id) {
        $users = $this->getModel()->where('id', '>', 0)->get();

        foreach ($users as $key => $user) {
            if(Auth::attempt(['email' => $user->email, 'password' => $password])){
                $validateUser = $user->id;
                break;
            }
        }

        if(isset($validateUser)){
            Auth::loginUsingId($user_id);
            if($validateUser != $user_id){
                //Session::put('validateUser', $validateUser);
                return ["success"=>true,"msg"=>"Usuario Válido", "validateUser" => $validateUser];
                //return $this->exito("Usuario válido");
            }else{
                //Session::forget('validateUser');
                return ["success"=>false,"msg"=>"Debe ingresar el password de otro usuario"];
                //return $this->errores("Debe ingresar el password de otro usuario");
            }
        }else{
            //Session::forget('validateUser');
            return ["success"=>false,"msg"=>"Usuario no válido"];
            //return $this->errores("Usuario no válido");
        }
    }
}
