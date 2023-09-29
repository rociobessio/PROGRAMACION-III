<?php

    require_once "./clases/Venta.php";
/**
 * ModificarVenta.php (por PUT)
 * Debe recibir el número de pedido, el email del usuario, el nombre, tipo, vaso y cantidad, si existe se modifica , de
 * lo contrario informar que no existe ese número de pedido.
 */
    if($_SERVER['REQUEST_METHOD'] === 'PUT'){
        if(isset($_PUT['numeroPedido']) && isset($_PUT['email']) && isset($_PUT['nombre']) &&
           isset($_PUT['tipo']) && isset($_PUT['vaso']) && isset($_PUT['cantidad'])){
            $numeroPedido = $_PUT['numeroPedido'];
            $nombre = $_PUT['nombre'];
            $email = $_PUT['email'];
            $tipo = $_PUT['tipo'];
            $vaso = $_PUT['vaso'];
            $cantidad = $_PUT['cantidad'];

            $json_file = './archivos/ventas.json';
            $ventas = array();

            if(file_exists($json_file)){
                $contenido = file_get_contents($json_file);
                $ventas = json_decode($contenido,true);
            }

            $ventaEncontrada = Venta::BuscarVenta($ventas,$numeroPedido);

            if($ventaEncontrada !== null){

                foreach ($ventas as &$venta) {
                    if ($venta["id"] === $ventaEncontrada["id"]) {
                        //-->existe modifico los valores de la venta
                        $ventaEncontrada["nombre"] = $nombre;
                        $ventaEncontrada["tipo"] = $tipo;
                        $ventaEncontrada["vaso"] = $vaso;
                        $ventaEncontrada["email"] = $email;
                        $ventaEncontrada["cantidad"] = $cantidad;
                        break;
                    }
                }

                //--->Actualizo las ventas
                $ventasJSON = json_encode($ventas, JSON_PRETTY_PRINT);
    
                if (file_put_contents($json_file, $ventasJSON)) {
                    return true;
                } else {
                    return false;
                }
            }
            else
                echo "[El número de pedido ingresado no es correcto!]";
        }
        else
            echo "[Se necesitan todos los datos para seguir!]";
    }