<?php 
/* 
APLICACIÓN 08 - CARGA ALEATORIA:

Imprima los valores del vector asociativo siguiente usando la estructura de control foreach:
$v[1]=90; $v[30]=7; $v['e']=99; $v['hola']= 'mundo';

Bessio Rocio Soledad
*/

    //los arrays ASOCIATIVOS tienen indices nombrados.
    $arrayAsociativo = array(1 => 90, 30 => 7, 'e' => 99, 'hola' => 'mundo');

    foreach ($arrayAsociativo as $clave => $valor) {
            echo "Clave: $clave, Valor: $valor<br/>";
    }
?>