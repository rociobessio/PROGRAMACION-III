<?php
/*
APLICACIÓN 04 - CALCULADORA:
Escribir un programa que use la variable $operador que pueda almacenar los símbolos
matemáticos: ‘+’, ‘-’, ‘/’ y ‘*’; y definir dos variables enteras $op1 y $op2. De acuerdo al
símbolo que tenga la variable $operador, deberá realizarse la operación indicada y mostrarse el
resultado por pantalla.

Bessio Rocio Soledad
 */

    $operador = "/";
    $op1 = 6;
    $op2 = 0;

    echo "<h1 align=" ,"center", "> CALCULADORA <h1/>";

    if($operador == "+")
        echo "Resultado de la suma: ", $op1 + $op2;
    elseif ($operador == "*")
        echo "Resultado de la multiplicación: ", $op1 * $op2;
    elseif ($operador == "-")
        echo "Resultado de la resta: ", $op1 - $op2;
    elseif ($operador == "/"){
        if($op2 == 0)
            echo "No se puede dividir por 0.";
        else
            echo "Resultado de la división: ", $op1 / $op2;
    } 
?>