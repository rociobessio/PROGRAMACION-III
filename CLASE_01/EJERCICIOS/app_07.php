<?php 
/*
APLICACIÓN 07 - MOSTRAR IMPARES:

Generar una aplicación que permita cargar los primeros 10 números impares en un Array.
Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el
salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números
utilizando las estructuras while y foreach.

Bessio Rocio Soledad
*/
    $numerosImpares = array();

    $numero = 1;

    /*Cargo los primeros 10s numeros impares en mi array */
    while(count($numerosImpares) < 10){
        if ($numero %2 != 0 )
            $numerosImpares[] = $numero;
        
        $numero++;
    }


    /*El operador . me permite concatenar dos strings. */
    echo "Imprimiendo con WHILE: <br/>";
    $i = 0;
    while ($i < count($numerosImpares)) {
        echo $numerosImpares[$i] . "<br/>";
        $i++;
    }
    
    echo "<br/>Imprimiendo con FOREACH:<br/>";
    foreach ($numerosImpares as $numero) {
        echo $numero . "<br/>";
    }

?>