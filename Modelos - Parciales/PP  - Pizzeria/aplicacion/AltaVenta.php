<?php

    require_once "./clases/Venta.php";
    require_once "./clases/Producto.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['email_usuario']) && isset($_POST['tipo']) && isset($_POST['sabor']) && 
        isset($_POST['cantidad']) && ($_FILES['imagen_producto'])){
            $email_usuario = $_POST['email_usuario']; 
            $tipo = $_POST['tipo'];
            $sabor = $_POST['sabor'];
            $cantidad = $_POST['cantidad'];  
            $imagen = $_FILES['imagen_producto'];

            if (RealizarVenta($email_usuario, $tipo, $sabor, $cantidad,null,null, $imagen)) {
                echo "[Venta guardada correctamente!]";
                echo "[Stock actualizado!]";
            } else {
                echo "[No se pudo generar la venta solicitada, reintente!]";
            }
        }   
        else
            echo "[Se necesitan todos los datos para seguir!]";
    }

    /**
     * Esta funcion me permitirá realizar una venta comprobando
     * todas las validaciones necesarias para cumplirla.
     * 
     * @return bool true si pudo, false sino.
     */
    function RealizarVenta($email_usuario, $tipo, $sabor, $cantidad,$nombre_usuario=null ,$nombre=null, $imagen = null){
        $jsonFileProductos = './archivos/Pizza.json';
        // $jsonFileCupones = './archivos/cupones.json';

        $productos = Venta::ObtenerArray($jsonFileProductos);
        // var_dump($productos);
        $productoExistente = Producto::BuscarProducto($productos, $sabor, $tipo);

        if ($productoExistente !== null) {
            if (Producto::verificarStock($productoExistente, $cantidad)) {
                $productoExistente["cantidad"] -= $cantidad;
                $montoOriginal = $productoExistente['precio'] * $cantidad;
                    if (Venta::GenerarVenta($email_usuario, $productoExistente, $cantidad, $montoOriginal, $imagen)) {
                        Producto::ActualizarProducto($productos, $productoExistente, $jsonFileProductos);
                        return true;
                    }
                    else{
                        return false;
                    } 
            }
            else
                echo "[No hay stock del producto para realizar la venta!]";
        }
        else
        {
            echo "[No existe le producto solicitado!]";
            return false;
        }
    }