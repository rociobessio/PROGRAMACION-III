<?php
/**
 * APLICACIÓN 03 - OBTENER EL VALOR DEL MEDIO:
 * Dadas tres variables numéricas de tipo entero $a, $b y $c realizar una aplicación que muestre
 *  el contenido de aquella variable que contenga el valor que se encuentre en el medio de las tres
 *  variables. De no existir dicho valor, mostrar un mensaje que indique lo sucedido. Ejemplo 1: $a
 *  = 6; $b = 9; $c = 8; => se muestra 8.
 *  Ejemplo 2: $a = 5; $b = 1; $c = 5; => se muestra un mensaje “No hay valor del medio”
 * 
 * BESSIO ROCIO SOLEDAD
 */

    echo "<h1 align=" ,"center", "> Obtener el valor del Medio <h1/>";

    $a = 5;
    $b = 5 ;
    $c = 4;

    /*Ordenamiento*/
    if($a > $b){
        $aux = $a;
        $a = $b;
        $b = $aux;
    }

    if($b > $c){
        $aux = $b;
        $b = $c;
        $c = $aux;
    }

    if($c > $b){
        $aux = $a;
        $a = $b;
        $b = $aux;
    }

    /*Compruebo*/
    if($a == $b || $b == $c)
        echo "<h3>[No existe un valor intermedio!]<h3/>";
    else
        echo "<h3>El valor intermedio es: ", $b, "<h3/>"; 
?>