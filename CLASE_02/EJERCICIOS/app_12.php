<?php
/*
    APLICACIÓN 12 - INVERTIR PALABRAS

Realizar el desarrollo de una función que reciba un Array de caracteres y que invierta el orden
de las letras del Array.
Ejemplo: Se recibe la palabra “HOLA” y luego queda “ALOH”.

    Bessio Rocio Soledad
*/

    //array de caracteres
    $mensajeArray = array('h','o','l','a');

    //llamado y devolucion de funcion
    $salida = InvertirPalabras($entrada);

    //funcion
    function InvertirPalabras($cadena){
        $longitud = count($cadena);
        $result = array();
        
        for ($i = $longitud - 1; $i >= 0; $i--) {
            $result[] = $cadena[$i];
        }
        
    }

    // foreach ($salida as $caracter) {
    //     echo $caracter;
    // }
?>