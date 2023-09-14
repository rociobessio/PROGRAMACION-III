<?php

    include_once "usuarios.php";

    echo "<h1 align=" . "center" .">TEST DE USUARIOS</h1>"; 

    echo "<br/><hr/><h3>Test guardar usuarios en csv:</h3>";

    $listaUsuarios = array(new Usuario(123,"rocibessio@gmail.com","Rocio"),
                           new Usuario(12233,"cv2320@gmail.com","Claudia"),
                           123554);//-->El último valor no debería ser agregado al archivo ya que NO es un usuario.


    foreach($listaUsuarios as $usuario){
        if(Usuario::GuardarUsuarioCSV($usuario)){
            echo "[Usuario agregado al archivo correctamente!]<br>";
        }
        else
            echo "[Ocurrio un error al intentar agregar el usuario al archivo!]<br>";
    }

    echo "<br/><hr/><h3>Test Método EQUALS:</h3>";
    $usuario_Registrado = new Usuario(123,"rocibessio@gmail.com");//-->Deberia de estar ya registrado
    $resultado_Verificado = $usuario_Registrado->Equals($listaUsuarios);
    echo $resultado_Verificado . "<br>";

    $usuario_Clave_Incorrecta = new Usuario(5342,"rocibessio@gmail.com");//-->Deberia de lanzar error de verificación
    $resultado_Clave_Incorrecta = $usuario_Clave_Incorrecta->Equals($listaUsuarios);
    echo $resultado_Clave_Incorrecta . "<br>";
    
    $usuario_No_Registrado = new Usuario(8888,"noregistrado@gmail.com");//-->Deberia de lanzar error de verificación
    $resultado_No_Registrado = $usuario_No_Registrado->Equals($listaUsuarios);
    echo $resultado_No_Registrado . "<br>";
?>