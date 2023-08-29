<?php 
/*
APLICACION 02 (Mostrar fecha y estación):
Obtenga la fecha actual del servidor (función date) y luego imprímala dentro de la página con
distintos formatos (seleccione los formatos que más le guste). Además indicar que estación del
año es. Utilizar una estructura selectiva múltiple.

Bessio Rocio Soledad
*/

    $fechaActualNormal = date("Y-m-d");/*formato string año-mes-dia
                                        Y => muestra los 4 digitos del año,
                                        y => muestra solo los 2 digitos.
                                        m/d => mes/dia con ceros iniciales */

    $fechaActualConHora = date("d/m/Y h-i-A");/*formato string año-mes-dia con hora
                                                A => muestra si es AM o PM
                                                a => am o pm en minuscula*/

    $fechaActualDiaMes = date("D/M/y  h-i-A");/*formato string año-mes-dia con hora
                                                D => muestra el nombre del dia abreviado
                                                M => muestra el nombre del mes abreviado*/

    echo "Fecha actual (formato #1): ", $fechaActualNormal;
    echo "<br/>Fecha actual (formato #2): ", $fechaActualConHora;
    echo "<br/>Fecha actual (formato #3): ", $fechaActualDiaMes;

    $mesActual =date("n");/* n => me permite obtener el mes en digito sin 0s*/
    $estacionActual = "";

    if($mesActual >= 1 && $mesActual <= 3 || $mesActual == 12)/*Diciembre (12), enero a marzo => Verano */
        $estacionActual = "Verano";
    else if($mesActual >= 4 && $mesActual <=6)
        $estacionActual = "Otoño";
    else if ($mesActual >= 7 && $mesActual <=9)
        $estacionActual = "Invierno";
    else if ($mesActual >= 10 && $mesActual <=11)
        $estacionActual = "Primavera";

    echo "<hr><br/>La estación actual es: ", $estacionActual;

?>
