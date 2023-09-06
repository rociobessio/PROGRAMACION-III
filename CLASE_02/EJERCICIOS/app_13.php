<?php
/*
    APLICACIÓN 13 - INVERTIR PALABRAS

    Crear una función que reciba como parámetro un string ($palabra) y un entero ($max). La
    función validará que la cantidad de caracteres que tiene $palabra no supere a $max y además
    deberá determinar si ese valor se encuentra dentro del siguiente listado de palabras válidas:
    “Recuperatorio”, “Parcial” y “Programacion”. Los valores de retorno serán: 1 si la palabra
    pertenece a algún elemento del listado.
    0 en caso contrario.

    Bessio Rocio Soledad
*/

    echo "<h1 align=" . "center" .">EJERCICIO 13 (INVERTIR PALABRAS)</h1>"; 

    $palabra_Uno = "Rocio";
    $palabra_Dos = "Programacion";
    $max = 15;

    $resultado_Uno = InvertirPalabras($palabra_Uno,$max);
    $resultado_Dos = InvertirPalabras($palabra_Dos,$max);
    $resultado_Tres = InvertirPalabras("Otorrinonaringologia",3);

    echo "<br/><hr/>La palabra: '$palabra_Uno' es $resultado_Uno (No pertenece al conjunto de palabras validas)";
    echo "<br/><hr/>La palabra: '$palabra_Dos' es $resultado_Dos";
    echo "<br/><hr/>La palabra: 'Otorrinonaringologia' es $resultado_Tres (Excede el máximo)";

    function InvertirPalabras($palabra, $max){
        $palabrasValidas = array("Recuperatorio","Parcial","Programacion");

        //strlen me permite saber la longitud de mi cadena
        if(strlen($palabra) > $max )
            return 0;
        
        //Verifico que la palabra coincida o no con las validas
        if(in_array($palabra,$palabrasValidas)){//metodo in_array devuelve si un elemento se encuentra o no en el array.
            return 1;
        }
        return 0;
    }


?>