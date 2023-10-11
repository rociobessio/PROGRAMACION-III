<?php
    include_once "Pizza.php";
    include_once "Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['emailUsuario']) && isset($_POST['tipo']) && isset($_POST['sabor']) &&
            isset($_POST['cantidad']) && isset($_FILES['imagen'])){
            $emailUsuario = $_POST['emailUsuario']; 
            $tipo = $_POST['tipo'];
            $sabor = $_POST['sabor'];//-->Puede variar
            $cantidad = intval($_POST['cantidad']); 
            $imagen = $_FILES['imagen'];

            $jsonFilePizzas = './archivos/Pizza.json"';
            $pizzas = Pizza::LeerJSON($jsonFilePizzas);//-->Traigo el array
            $pizzaExistente = Pizza::BuscarPizza($pizzas,$sabor,$tipo);//-->Busco si existe

            if (Venta::GenerarVenta($pizzaExistente,$cantidad,$emailUsuario,$imagen)) {
                echo '[Venta guardada correctamente!]<br>';

                if(Pizza::ActualizarProducto($pizzas,$pizzaExistente,$jsonFilePizzas)){
                    echo '[Stock actualizado en el archivo!]';
                }
                else
                    echo '[No se pudo actualizar el stock del archivo!]<br>';
            } else {
                echo '[No se pudo generar la venta solicitada, reintente!]';
            }
        }   
        else
            echo '[Se necesitan todos los datos para seguir!]';
    }