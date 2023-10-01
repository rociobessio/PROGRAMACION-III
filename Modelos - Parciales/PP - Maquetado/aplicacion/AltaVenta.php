<?php

    require_once "../clases/Venta.php";
    require_once "../clases/Producto.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['email_usuario']) && isset($_POST['nombre_usuario']) && 
        isset($_POST['tipo']) && isset($_POST['aderezo']) && isset($_POST['cantidad']) &&
        isset($_POST['nombre']) && isset($_FILES['imagen_producto'])){
            $email_usuario = $_POST['email_usuario'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $tipo = $_POST['tipo'];
            $aderezo = $_POST['aderezo'];
            $cantidad = $_POST['cantidad'];
            $nombre = $_POST['nombre'];
            $imagen = $_FILES['imagen_producto'];

            if (RealizarVenta($email_usuario, $nombre_usuario, $tipo, $aderezo, $cantidad, $nombre, $imagen)) {
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
    function RealizarVenta($email_usuario, $nombre_usuario, $tipo, $aderezo, $cantidad, $nombre, $imagen){
        $jsonFileProductos = '../archivos/productos.json';
        $jsonFileCupones = '../archivos/cupones.json';

        $productos = Venta::ObtenerArray($jsonFileProductos);
        $productoExistente = Producto::BuscarProducto($productos, $nombre, $tipo);

        if ($productoExistente !== null) {
            if (Producto::verificarStock($productoExistente, $cantidad)) {
                $productoExistente["cantidad"] -= $cantidad;
                $montoOriginal = $productoExistente['precio'] * $cantidad;

                //-->Se agrega en otra parte, si es con cupon es con descuento.
                if (isset($_POST['cupon_descuento'])) {
                    $cuponDescuento = $_POST['cupon_descuento'];
                    $cuponEncontrado = Venta::BuscarCuponDescuento($cuponDescuento);
                    
                    //-->Valido que no este usado y que no haya vencido
                    if ($cuponEncontrado['estado'] !== "usado" && Venta::ValidarFechaVencimiento($cuponEncontrado)) {
                        $descuento = Venta::CalcularDescuento($productoExistente['precio'], $cuponEncontrado);
                        $importeFinal = $montoOriginal - $descuento;

                        if (Venta::GenerarVenta($email_usuario, $nombre_usuario, $productoExistente, $cantidad, $montoOriginal, $imagen, $importeFinal)) {
                            
                            $cupones = Venta::ObtenerArray($jsonFileCupones);
                            
                            //-->Actualizo el estado, dependiendo no es necesario.
                            foreach ($cupones as &$cupon) {
                                if ($cupon["id"] == $cuponEncontrado["id"]) {
                                    $cupon['estado'] = "usado"; // Cambio el cupón a usado.
                                    break;
                                }
                            }

                            $cuponesJSON = json_encode($cupones, JSON_PRETTY_PRINT);
                            file_put_contents($jsonFileCupones, $cuponesJSON);

                            Producto::ActualizarProducto($productos, $productoExistente, $jsonFileProductos);
                            return true;
                        }
                        else{
                            return false;
                        }
                    }
                    else {
                        echo "[Sucedio algo con el cupon, no existe, ha expirado o ya fue utilizado!]";
                        return false;
                    }
                }
                else{//-->Si no hay cupon es venta sin descuento.
                    if (Venta::GenerarVenta($email_usuario, $nombre_usuario, $productoExistente, $cantidad, $montoOriginal, $imagen)) {
                        Producto::ActualizarProducto($productos, $productoExistente, $jsonFileProductos);
                        return true;
                    }
                    else{
                        return false;
                    }
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