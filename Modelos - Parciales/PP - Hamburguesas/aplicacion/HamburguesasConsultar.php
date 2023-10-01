<?php

    require_once "../clases/Hamburguesa.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['nombre']) && isset($_POST['tipo'])){

            $nombre = $_POST['nombre'] ;
            $tipo = $_POST['tipo'];

            //-->Me traigo el array de hamburguesas
           $json_file = '../archivos/hamburguesas.json';
           $hamburguesas = array();

           if(file_exists($json_file)){
            $contenido = file_get_contents($json_file);
            $hamburguesas = json_decode($contenido,true);
           }

           $existe = Hamburguesa::BuscarHamburguesa($hamburguesas,$nombre,$tipo);

           if($existe){
                echo "[Si hay!]";
           }
           else
                echo "[No se encuentra la  hamburguesa solicitada!]";
        }
        else
            echo "[Se deben de completar todos los datos!]";
    }