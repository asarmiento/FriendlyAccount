<?php
namespace AccountHon\Traits;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 19/11/2016
 * Time: 08:44 PM
 */
trait Convert
{

    public function multipleOfFive($amount)
    {
        $amounts = explode('.',$amount);
        $totalSeparate = substr($amounts[0],-1);
        \Log::info(__FUNCTION__.' '.__LINE__.' '.$amount);
        \Log::info(__FUNCTION__.' '.__LINE__.' '.$totalSeparate);
        if($totalSeparate == 5 OR $totalSeparate == 0):
            $amount = $amounts[0];
        else:
            if($totalSeparate > 0 && $totalSeparate < 5):
                $diferencia = 5 - $totalSeparate;
                $amount = $amounts[0] + $diferencia;
            elseif ($totalSeparate > 5 && $totalSeparate < 10):
                $diferencia = 10 - $totalSeparate;
                $amount = $amounts[0] + $diferencia;
            endif;
        endif;
        return $amount;
    }

    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: 2016-11-22   @Update 0000-00-00
    ***************************************************
    * @Description: Con este metodos recivimos via ajax
    * y la convertimos a un objeto para poder trabajarlo
    *
    *
    * @Pasos:
    *
    *
    * #if (${TYPE_HINT} != "void") * @return ${TYPE_HINT}
    *  #end
    *  ${THROWS_DOC}
    ***************************************************/
    public function convertionObjeto()
    {
        $datos = Input::get('data');
        $DatosController = json_decode($datos);
        /** @var TYPE_NAME $DatosController */
        return $DatosController;
    }
    /**************************************************
    * @Author: Anwar Sarmiento Ramos
    * @Email: asarmiento@sistemasamigables.com
    * @Create: ${DATE} ${TIME}   @Update 0000-00-00
    ***************************************************
    * @Description:
    *
    *
    *
    * @Pasos:
    *
    *
    * #if (${TYPE_HINT} != "void") * @return ${TYPE_HINT}
    *  #end
    *  ${THROWS_DOC}
    ***************************************************/
    public function CreacionArray($data, $delimiter, $md5 = false)
    {
        foreach ($data AS $key => $value):
            $newKey = explode($delimiter, $key);
            $keyNew = ($newKey[0]);
            $newArreglo[$keyNew] = ($value);
        endforeach;
        if(is_object(userSchool())):
            $newArreglo['school_id'] = userSchool()->id;
            $newArreglo['user_id'] = currentUser()->id;
        endif;
        if (empty($newArreglo['token'])):

            if (isset($newArreglo['name'])):
                if($md5)
                {
                    $newArreglo['token'] = md5($newArreglo['name']);
                }else{
                    $newArreglo['token'] = Crypt::encrypt($newArreglo['name']);
                }

                return $newArreglo;

            elseif (isset($newArreglo['amount'])):

                $newArreglo['token'] = Crypt::encrypt($newArreglo['amount']);

                return $newArreglo;

            elseif (isset($newArreglo['code'])):

                $newArreglo['token'] = Crypt::encrypt($newArreglo['code']);

                return $newArreglo;

            elseif (isset($newArreglo['year'])):

                $newArreglo['token'] = Crypt::encrypt($newArreglo['year']);

                return $newArreglo;

            elseif (isset($newArreglo['fname'])):

                $newArreglo['token'] = Crypt::encrypt($newArreglo['fname']);

                return $newArreglo;
            elseif (isset($newArreglo['date'])):

                $newArreglo['token'] = Crypt::encrypt($newArreglo['date']);

                return $newArreglo;

            elseif (isset($newArreglo['coustomer_id'])):

                $newArreglo['token'] = Crypt::encrypt($newArreglo['coustomer_id']);

                return $newArreglo;
            endif;

            $newArreglo['token'] = Crypt::encrypt($newArreglo);

            return $newArreglo;
        endif;

        return $newArreglo;
    }
}