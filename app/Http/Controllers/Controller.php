<?php

namespace AccountHon\Http\Controllers;

use AccountHon\Entities\School;
use AccountHon\Http\Controllers\Generales\ErrorsController;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Foundation\Validation\ValidatesRequests;
use \Input;
use \Crypt;
use Illuminate\Support\Facades\Response;


abstract class Controller extends ErrorsController
{
    use DispatchesJobs, ValidatesRequests;


   /* Con estos methodos enviamos los mensajes de exito en cualquier controlador */

    public function exito($data)
    {
        return Response::json([
            'success' => true,
            'message' => $data,
        ]);
    }

    /* Con estos methodos enviamos los mensajes de error en cualquier controlador */

    public function errores($data)
    {
        return Response::json([
            'success' => false,
            'errors' => $data,
        ]);
    }

    /*
    |---------------------------------------------------------------------
    |@Author: Anwar Sarmiento <asarmiento@sistemasamigables.com
    |@Date Create: 2016-00-00
    |@Date Update: 2016-02-04
    |---------------------------------------------------------------------
    |@Description: Con esta accion actualizamos el archivo json de los
    | school antes de ser utilizados.
    |----------------------------------------------------------------------
    | @return mixed
    |----------------------------------------------------------------------
    */
    public function fileJsonUpdate() {
        /* Buscamos todos los datos de school y traemos solo el id y el name */
        $school = School::select('id', 'name')->get();
        $dataJson = [];
        foreach ($school as $schools):
            $dataJson[] = array('value' => $schools->id, 'text' => $schools->name);
        endforeach;

        $fh = fopen('json/schools.json', 'w')
        or die('Error al abrir fichero de salida');
        fwrite($fh, json_encode($dataJson, JSON_UNESCAPED_UNICODE));
        fclose($fh);
    }
}
