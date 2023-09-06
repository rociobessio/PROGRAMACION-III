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
    $salida = InvertirPalabras($mensajeArray);

    //funcion
    function InvertirPalabras($cadena){
        $longitud = count($cadena);
        $palabraInvertida = array();
    
        //El for recorre la longitud de la cadena -1 e ira descontando
        //se alamacenara en un nuevo array los caracteres
        for ($i = $longitud - 1; $i >= 0; $i--) {
            $palabraInvertida[] = $cadena[$i];
        }

        return $palabraInvertida;
    }
 
    echo "<h1 align=" . "center" .">EJERCICIO 12 (INVERTIR PALABRAS)</h1>"; 
    //el metodo implode me permite concatenar elementos de un array
    //en una cadena y retornarla. argumentos (separador[''],array)
    echo "<hr/>Mensaje original: " . implode('',$mensajeArray);
    echo "<hr/>Salida: " . implode('',$salida);
?>