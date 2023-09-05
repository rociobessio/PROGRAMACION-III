<?php
/*  
    En testAuto.php:
    ● Crear dos objetos “Auto” de la misma marca y distinto color.
    ● Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
    ● Crear un objeto “Auto” utilizando la sobrecarga restante.
    ● Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500
    al atributo precio.
    ● Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el
    resultado obtenido.
    ● Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o
    no.
    ● Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos impares (1, 3,
    5)
 */
    //Incluyo el archivo
    include "../Auto/app_17.php";

    echo "<h1 align=" . "center" .">EJERCICIO 17 (AUTO - POO)</h1>"; 

    //● Crear dos objetos “Auto” de la misma marca y distinto color. 
    $auto_Uno  = new Auto("Toyota","Rojo");
    $auto_Dos = new Auto("Toyota","Azul");
    
    //● Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
    $auto_Tres = new Auto("Hyundai","Naranja");
    $auto_Cuatro = new Auto("Hyundai","Naranja");

    //Crear un objeto “Auto” utilizando la sobrecarga restante.
    $auto_Cinco = new Auto("Audi","Negro", 123000.0, new DateTime('2000-10-14'));

    echo "<br/><hr/>Autos:<br/>";
    //para mostrar los atributos del auto
    Auto::MostrarAuto($auto_Tres);
    Auto::MostrarAuto($auto_Cuatro);
    Auto::MostrarAuto($auto_Cinco);

    //Utilizar el método “AgregarImpuestos” en los últimos tres objetos, agregando $ 1500
    $auto_Tres->AgregarImpuestos(1500);
    $auto_Cuatro->AgregarImpuestos(1500);
    $auto_Cinco->AgregarImpuestos(1500);

    echo "<br/><hr/>Autos agregando impuestos:<br/>";
    //para mostrar los atributos del auto
    Auto::MostrarAuto($auto_Tres);
    Auto::MostrarAuto($auto_Cuatro);
    Auto::MostrarAuto($auto_Cinco);

    //Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el
    //resultado obtenido.
    echo "<br/><hr/>Importe sumado del primer Auto mas el segundo:<br/>";
    $importacionSuma = Auto::Add($auto_Uno,$auto_Dos);
    echo "<br/>Importe total: $" . $importacionSuma ."<br/>";

    echo "<br/><hr/>Comparo los autos:<br/>";
    //Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o no.
    $comparacion_Uno = $auto_Uno->Equals($auto_Uno,$auto_Dos);//Son iguales.
    $comparacion_Dos = $auto_Uno->Equals($auto_Uno,$auto_Cinco);//Son distintos
    
     if($comparacion_Uno)
        echo "Los autos son iguales.";
     else
         echo "Los autos NO son iguales.";

      if($comparacion_Dos)
         echo "Los autos son iguales.<br/>";
      else
          echo "Los autos NO son iguales.<br/>";

    //Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos impares (1, 3,5)
    echo "<br/><hr/>Muestro los autos IMPARES:<br/>";
    echo "<br/>Mostrar #1:";
    Auto::MostrarAuto($auto_Uno);
    echo "<br/>Mostrar #3:";
    Auto::MostrarAuto($auto_Tres);
    echo "<br/>Mostrar #5:";
    Auto::MostrarAuto($auto_Cinco);
?>