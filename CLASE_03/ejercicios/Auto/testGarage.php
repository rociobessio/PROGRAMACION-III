<?php
/*
    APLICACION 18 - TESTGARAGE:

    En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos
    los métodos.

    Bessio Rocio Soledad
*/

    include_once "../Auto/Garage.php";
    include_once "../Auto/Auto.php";

    echo "<h1 align=" . "center" .">EJERCICIO 18 (testGarage)</h1>"; 

    $garage = new Garage("Romano's",700.0);

    //Creo los autos
    $auto_Uno = new Auto("Audi","Negro", 23000.0);
    $auto_Dos = new Auto("Hyundai","Rojo", 612000.0);
    $auto_Tres = new Auto("Renault","Blanco", 1000000.0);
    $auto_Cuatro = new Auto("Ferrari","Negro", 2000000.0);//No lo agrego y pruebo el equal

    //Los agrego al garage.
    $garage->Add($auto_Uno);
    $garage->Add($auto_Dos);
    $garage->Add($auto_Tres);

    //Imprimo la información del garage:
    echo "<br/><hr/>Información del garage: <br/>";
    $garage->MostrarGarage();

    //Reviso el Equals
    if($garage->Equals($auto_Cuatro)){
        echo "<br/><hr/>El auto SI se encuentra en el garage!";
    }
    else
        echo "<br/><hr/>El auto NO se encuentra en el garage!";

    //Pruebo el metodo Remove
    echo "<br/><hr/>Eliminando auto #1: <br/>";
    $garage->Remove($auto_Uno);
    $garage->MostrarGarage();

    //************  MANEJO DE ARCHIVOS *************/
    echo "<br/><hr/>GUARDAR GARAGE EN ARCHIVO CSV<br/>";
    $resultado = Garage::GuardarGarageCSV($garage);
    if($resultado)
        echo "<br/>Pudo guardar el garage en el archivo CSV.<br/>";
    else
        echo "<br/>NO se pudo guardar el garage en el archivo CSV.<br/>";
    
    echo "<br/><hr/>LECTURA DE ARCHIVO GARAGES.CSV:<br/>";
   $listaGaragesCSV = Garage::LeerGarageCSV();
   foreach($listaGaragesCSV as $key => $garage){
      $garage->MostrarGarage($garage);
   }
?>