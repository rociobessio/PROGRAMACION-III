<?php
/*
APLICACIÓN 10 - ARRAYS DE ARRAYS:

Realizar las líneas de código necesarias para generar un Array asociativo y otro indexado que
contengan como elementos tres Arrays del punto anterior cada uno. Crear, cargar y mostrar los
Arrays de Arrays.

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

    //Creo el array de lapiceras asociativo:
    $lapicerasAsociativas = array(
        'lapiceraUno' => $lapiceraUno,
        'lapiceraDos' => $lapiceraDos,
        'lapiceraTres' => $lapiceraTres
    );

    //Creo el array de lapiceras indexadas
    $lapicerasIndexadas = array($lapiceraUno,$lapiceraDos,$lapiceraTres);

    echo "Lapiceras Asociativas: <br/>";
    //Muestro el array de lapiceras ASOCIATIVAS
    foreach($lapicerasAsociativas as $nombreLapicera => $lapicera){
        echo "$nombreLapicera: <br/>";
        echo "Color: " . $lapicera['color']  . "<br/>";
        echo "Marca: " . $lapicera['marca']  . "<br/>";
        echo "Trazo: " . $lapicera['trazo']  . "<br/>";
        echo "Precio: " . $lapicera['precio']  . "<br/><hr/>";
    } 

    echo "<br/><hr/>Lapiceras Indexadas: <br/>";
    //Muestro el array INDEXADO
    foreach($lapicerasIndexadas as $indexLapicera => $lapicera){
        echo "Lapicera $indexLapicera: <br/>";
        echo "Color: " . $lapicera['color']  . "<br/>";
        echo "Marca: " . $lapicera['marca']  . "<br/>";
        echo "Trazo: " . $lapicera['trazo']  . "<br/>";
        echo "Precio: " . $lapicera['precio']  . "<br/><hr/>";
    }
?>