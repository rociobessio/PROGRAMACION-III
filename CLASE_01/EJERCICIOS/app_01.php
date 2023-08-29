<?php
/*
Confeccionar un programa que sume todos los números enteros desde 1 mientras la suma no
supere a 1000. Mostrar los números sumados y al finalizar el proceso indicar cuantos números
se sumaron.
*/

$cont = 0;
$mensaje = "";
$sumaNumeros = 0;
$numero = 1; /*desde 1*/

    while($sumaNumeros + $numero <= 1000){
        $sumaNumeros+= $numero;
        $cont++;
        $numero++;

    }/* La suma del número + el numero, no debe superar a 1000. */

    echo "Números: ";
    for ($i = 1; $i <= $cont; $i++) {
        echo "<br/>$i ";
    }

    echo "<br/>Cantidad de números sumados: " , $cont;
?>