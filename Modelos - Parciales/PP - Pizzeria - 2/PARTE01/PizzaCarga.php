<?php

    require_once "Pizza.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['precio']) && isset($_GET['tipo']) && isset($_GET['sabor']) &&
    isset($_GET['cantidad']) && isset($_GET['imagen'])){
        //-->Obtengo los valores 
        $precio = floatval($_GET['precio']);
        $tipo = $_GET['tipo'];
        $sabor = $_GET['sabor'];
        $cantidad = intval($_GET['cantidad']); 
        $imagen = $_GET['imagen'];

        if(!Pizza::ValidarTipo($tipo)){
            echo "[Se debe de ingresar un tipo valido!]";
            exit;
        }

        $jsonFile = './archivos/Pizza.json';
        $directorioImagen = './ImagenesDePizzas/2023/';
        
        $pizzas = Pizza::LeerJSON($jsonFile);
        // var_dump($pizzas);
        $pizzaExistente = Pizza::BuscarPizza($pizzas,$sabor,$tipo);//-->Busco el producto.

        if(Pizza::CargarProducto($pizzaExistente,$pizzas,$precio,$cantidad,$jsonFile,$tipo,$sabor,$imagen,$directorioImagen)){
            echo "[Producto guardado correctamente!]";
        }
        else
            echo "[Ocurrio un error al querer guardar el producto!]";    
    }
    else
        echo "[Se necesitan todos los datos para poder seguir!]";
    }
    