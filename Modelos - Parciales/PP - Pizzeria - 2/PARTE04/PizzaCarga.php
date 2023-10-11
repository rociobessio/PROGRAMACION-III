<?php

    require_once "Pizza.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(isset($_POST['precio']) && isset($_POST['tipo']) && isset($_POST['sabor']) &&
    isset($_POST['cantidad']) && isset($_FILES['imagen'])){
        //-->Obtengo los valores 
        $precio = floatval($_POST['precio']);
        $tipo = $_POST['tipo'];
        $sabor = $_POST['sabor'];
        $cantidad = intval($_POST['cantidad']); 
        $imagen = $_FILES['imagen'];

        if(!Pizza::ValidarTipo($tipo)){
            echo "[Se debe de ingresar un tipo valido!]";
            exit;
        }

        $jsonFile = './archivos/Pizza.json';
        $directorioImagen = './ImagenesDePizzas/';
        
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
    