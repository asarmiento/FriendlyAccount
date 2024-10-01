<?php
namespace AccountHon\Traits;
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 21/11/2016
 * Time: 08:44 AM
 */
trait AuthTrait
{
    /**
     *
     * @author Julio Guerra
     * @values
     * - true: all is good;
     * - false: the password incorrect
     * @param Request $request
     * @return bool
     */
    protected function validateUser($data){
        if($data)
        {
            $credentials = array(
                'email' => \Auth::user()->username,
                'password' => $data
            );

            if(\Auth::validate($credentials)){
                return true;
            }
            else
            {
                return false;
            }
        }
        return false;
    }
}