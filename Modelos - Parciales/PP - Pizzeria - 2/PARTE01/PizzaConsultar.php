<?php

    require_once "Pizza.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //-->Puede variar segun el producto.
        if(isset($_POST['sabor']) && isset($_POST['tipo'])){
            $sabor = $_POST['sabor'] ;
            $tipo = $_POST['tipo'];

            $jsonFile = './archivos/Pizza.json';
            $pizzas = Pizza::LeerJSON($jsonFile);
            // var_dump($pizzas);

            $mensaje = Pizza::BuscarPor($pizzas,$sabor,$tipo);
            echo $mensaje;
 
        }
        else
            echo "[Se necesitan todos los datos para seguir!]";
        
    }