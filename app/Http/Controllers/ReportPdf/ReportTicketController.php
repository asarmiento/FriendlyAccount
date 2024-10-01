<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 06/04/16
 * Time: 09:46 AM
 */

namespace AccountHon\Http\Controllers\Restaurant\Report;


use AccountHon\Http\Controllers\Controller;
use AccountHon\Traits\Convert;

class ReportTicketController extends Controller
{
    use Convert;
    public function ticket() {

        if (($handle = @fopen("COM1", "w")) === FALSE) {
            die('No se puedo Imprimir, Verifique su conexion con el Terminal');
        }
        fwrite($handle, chr(27) . chr(64)); //reinicio
        fwrite($handle, chr(27). chr(112). chr(48));//ABRIR EL CAJON

        fwrite($handle, Chr(27) . Chr(112) . Chr(0) . Chr(25) . Chr(250));
        fwrite($handle, chr(27) . chr(33) . chr(8)); //negrita
        fwrite($handle, chr(27) . chr(97) . chr(1)); //centrado
        fwrite($handle, chr(27) . chr(32) . chr(3)); //ESTACIO ENTRE LETRAS
        fwrite($handle, "MINI SUPER DANNA");
        fwrite($handle, chr(27) . chr(32) . chr(0)); //ESTACIO ENTRE LETRAS
        fwrite($handle, chr(27) . chr(100) . chr(0)); //salto de linea VACIO
        fwrite($handle, chr(27) . chr(33) . chr(8)); //negrita
        fwrite($handle, chr(27) . chr(100) . chr(0)); //salto de linea VACIO
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "Anwar Sarmiento Ramos");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "Ced.: 134-000-267520");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "Tel.: 2777-4435 Cel.: 8304-5030");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "Barrio San Martin, primera entrada 50m este, Quepos");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        //foreach ($this->Contador_model->Contador('fact') AS $contador)
            fwrite($handle, "Factura: " );
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea



        fwrite($handle, "Cliente: " );
        fwrite($handle, chr(27) . chr(97) . chr(1)); //centrado
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "Fecha:" . date("d-m-Y") . "  Hora: " . date("H:i:s"));
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "=================================");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "Producto   Cant     Monto");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "=================================");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, chr(27) . chr(97) . chr(0)); //centrado


        fwrite($handle, chr(27) . chr(97) . chr(1)); //centrado
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea

        fwrite($handle, "=================================");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "     SUBTOTAL: " . number_format(0, 2));
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "       I.V.A.: " . number_format(0, 2));
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "        TOTAL: " . number_format(0, 2));
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "     PAGA CON: " . number_format(0, 2));
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "    SU VUELTO: " . number_format(0, 2));
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, "=================================");
        fwrite($handle, chr(27) . chr(100) . chr(1)); //salto de linea
        fwrite($handle, chr(27) . chr(33) . chr(8)); //negrita
        /**
         * CON ESTE BLOQUE IMPRIMIMOS EL NUMERO DE BOLETAS PARA LA
         * RIFA HAY QUE DAR
         *
         */



        fclose($handle); // cierra el fichero PRN
      return  $salida = shell_exec('lpr COM1 <ESC> m'); //lpr->puerto impresora, imprimir archivo PRN
    }
}