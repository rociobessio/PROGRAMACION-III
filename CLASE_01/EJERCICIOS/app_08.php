<?php 
/* 
APLICACIÓN 08 - CARGA ALEATORIA:

Imprima los valores del vector asociativo siguiente usando la estructura de control foreach:
$v[1]=90; $v[30]=7; $v['e']=99; $v['hola']= 'mundo';

Bessio Rocio Soledad
*/

    $arrayAsociativo = array();
    $arrayAsociativo[1] = 90;
    $arrayAsociativo[30] = 7;
    $arrayAsociativo['e'] = 99;
    $arrayAsociativo['hola'] = 'mundo';

    foreach ($v as $clave => $valor) {
            echo "Clave: $clave, Valor: $valor<br/>";
    }
?>