<?php
/*
APLICACIÓN 06 - ARRAYS - CARGA ALEATORIA:

Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número (utilizar la
función rand). Mediante una estructura condicional, determinar si el promedio de los números
son mayores, menores o iguales que 6. Mostrar un mensaje por pantalla informando el
resultado.

Bessio Rocio Soledad
*/ 
    $numeros = array();

    /*Cargo el array */
    for ($i = 0; $i < 5; $i++) {
        $numeroAleatorio = rand(1, 10); /*rand me permite obtener valores RANDOM */
        $numeros[] = $numeroAleatorio;
    }    

    $totalSuma = array_sum($numeros); /*Esta funcion me permite sumar los valores de mi array*/
    $promedio = $totalSuma / count($numeros); /*Count me permite saber el total de elementos*/

    if ($promedio > 6) {
        echo "El promedio es mayor que 6.";
    } elseif ($promedio < 6) {
        echo "El promedio es menor que 6.";
    } else {
        echo "El promedio es igual a 6.";
    }
?>