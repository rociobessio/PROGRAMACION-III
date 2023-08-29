<?php
/*
APLICACIÓN 05 - NÚMEROS EN LETRAS:
Realizar un programa que en base al valor numérico de una variable $num, pueda mostrarse
por pantalla, el nombre del número que tenga dentro escrito con palabras, para los números
entre el 20 y el 60.
Por ejemplo, si $num = 43 debe mostrarse por pantalla “cuarenta y tres”.

Bessio Rocio Soledad
*/

    $num = 25 ; 
    echo "<h1 align=" ,"center", ">NÚMEROS EN LETRAS<h1/>";

    if ($num >= 20 && $num <= 60) {
        $unidades = array(
            "", "uno", "dos", "tres", "cuatro", "cinco",
            "seis", "siete", "ocho", "nueve", "diez", "once",
            "doce", "trece", "catorce", "quince", "dieciséis",
            "diecisiete", "dieciocho", "diecinueve"
        );
    
        $decenas = array(
            2 => "veinte", 3 => "treinta", 4 => "cuarenta",
            5 => "cincuenta", 6 => "sesenta"
        );
    
        $decena = floor($num / 10);/**redondeo el numero, divido el numero por 10 obteniendo la decena */
        $unidad = $num % 10; /*obtengo la unidad */
    
        $numPalabras = "";

        /* si quiero concatenar strings utilizo .= */
    
        if ($decena > 2) {
            $numPalabras .= $decenas[$decena];
            if ($unidad > 0) {
                $numPalabras .= " y " . $unidades[$unidad];
            }
        } else {
            $numPalabras .= $unidades[$decena * 10 + $unidad];
        }
    
        echo "El número $num en palabras es: $numPalabras";
    }  

?>