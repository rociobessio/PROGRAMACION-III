<?php
/*
APLICACIÓN 09 - ARRAYS ASOCIATIVOS:

Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que
contenga como elementos: ‘color’, ‘marca’, ‘trazo’ y ‘precio’. Crear, cargar y mostrar tres
lapiceras.

Bessio Rocio Soledad
*/

    //Creo las lapiceras
    $lapiceraUno = array('color'=> 'rojo' ,
                         'marca' => 'pepe', 
                         'trazo' => 'grueso' , 
                         'precio' => '200');

    $lapiceraDos = array('color'=> 'negro',
                         'marca' => 'grillo', 
                         'trazo' => 'medio', 
                         'precio' => '120');

    $lapiceraTres = array('color'=> 'verde',
                         'marca' => 'pinocho', 
                         'trazo' => 'fino', 
                         'precio' => '190'); 
    
    //Hago un array de las lapiceras para luego mostrarlas
    $arrayLapiceras = array($lapiceraUno,$lapiceraDos,$lapiceraTres);

    //Recorro mi array de lapiceras y las muestro
    foreach($arrayLapiceras as $index => $lapicera){
        echo "Lapicera $index: <br/>";
        echo "Color: " . $lapicera['color']  . "<br/>";
        echo "Marca: " . $lapicera['marca']  . "<br/>";
        echo "Trazo: " . $lapicera['trazo']  . "<br/>";
        echo "Precio: " . $lapicera['precio']  . "<br/><hr/>";
    }
?>