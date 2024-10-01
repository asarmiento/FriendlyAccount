<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 12/01/16
 * Time: 07:40 PM
 */

namespace AccountHon\Http\Controllers\Generales;


use AccountHon\Traits\Convert;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class ErrorsController extends  BaseController
{
    use Convert;

    public function saveLogs($data,$class,$funtion,$line,$dir)
    {
        Log::error($data.', class: '.$class.', function: '.$funtion.', Line: '.$line.', Directorio: '.$dir);
    }
    public function warningLogs($data,$class,$funtion,$line,$dir)
    {
        Log::warning($data.', class: '.$class.', function: '.$funtion.', Line: '.$line.', Directorio: '.$dir);
    }
    public function returnAbort($num,$message)
    {
        abort($num,'Se genero un error '.$message.' favor verifique,
            o informe al soporte técnico  soporte@sistemasamigables.com ');
    }

    public function returnJson($message)
    {
        $this->errores('Se genero un error '.$message.' favor verifique,
            o informe al soporte técnico  soporte@sistemasamigables.com ');
    }
}