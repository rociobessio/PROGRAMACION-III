<?php
/**
 * AltaVenta.php: (por POST)se recibe el email del usuario y el nombre, tipo, aderezo y cantidad ,si el ítem
 * existe en Hamburguesas.json, y hay stock guardar en el archivo con la fecha, número de pedido y id
 * autoincremental ) y se debe descontar la cantidad vendida del stock .
 */

    require_once "../clases/Hamburguesa.php";
    require_once "../clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(isset($_POST['email_usuario']) && isset($_POST['nombre_usuario']) && 
           isset($_POST['tipo']) && isset($_POST['aderezo']) && isset($_POST['cantidad']) &&
           isset($_POST['nombre']) && isset($_FILES['imagen_hamburguesa'])){
            $email_usuario = $_POST['email_usuario'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $tipo = $_POST['tipo'];
            $aderezo = $_POST['aderezo'];
            $cantidad = $_POST['cantidad'];
            $nombre = $_POST['nombre'];
            $imagen = $_FILES['imagen_hamburguesa'];
        
            if (RealizarVenta($email_usuario, $nombre_usuario, $tipo, $aderezo, $cantidad, $nombre, $imagen)) {
                echo "[Venta guardada correctamente!]";
                echo "[Stock actualizado!]";
            } else {
                echo "[No se pudo generar la venta solicitada, reintente!]";
            }
        } else {
            echo "[Se deben completar todos los datos para seguir!]";
        }
    }

        
    function RealizarVenta($email_usuario, $nombre_usuario, $tipo, $aderezo, $cantidad, $nombre, $imagen)
    {
        $json_file = '../archivos/hamburguesas.json';
        $jsonFileCupones = '../archivos/cupones.json';
        $hamburguesas = array();

        if (file_exists($json_file)) {
            $contenido = file_get_contents($json_file);
            $hamburguesas = json_decode($contenido, true);
        }

        $hamburguesaExistente = Hamburguesa::BuscarHamburguesa($hamburguesas, $nombre, $tipo);

        if ($hamburguesaExistente !== null) {
            if (Hamburguesa::verificarStock($hamburguesaExistente, $cantidad)) {
                $hamburguesaExistente["cantidad"] -= $cantidad;
                $montoOriginal = $hamburguesaExistente['precio'] * $cantidad;

                if (isset($_POST['cupon_descuento'])) {
                    $cuponDescuento = $_POST['cupon_descuento'];
                    $cuponEncontrado = Venta::BuscarCuponDescuento($cuponDescuento);
                    //var_dump($cuponEncontrado);

                    if ($cuponEncontrado['estado'] !== "usado") {
                        $descuento = Venta::CalcularDescuento($hamburguesaExistente['precio'], $cuponEncontrado);
                        $importeFinal = $montoOriginal - $descuento;

                        if (Venta::GenerarVenta($email_usuario, $nombre_usuario, $hamburguesaExistente, $cantidad, $montoOriginal, $imagen, $importeFinal)) {
                            //Venta::ActualizarCupon($cuponEncontrado, $cuponDescuento, $jsonFileCupones);
                            
                            $cupones = array();

                            if (file_exists($jsonFileCupones)) {
                                $contenido = file_get_contents($jsonFileCupones);
                                $cupones = json_decode($contenido, true);
                            }

                            foreach ($cupones as &$cupon) {
                                if ($cupon["id"] == $cuponEncontrado["id"]) {
                                    $cupon['estado'] = "usado"; // Cambio el cupón a usado.
                                    break;
                                }
                            }

                            $cuponesJSON = json_encode($cupones, JSON_PRETTY_PRINT);
                            file_put_contents($jsonFileCupones, $cuponesJSON);
                            
                            Hamburguesa::ActualizarHamburguesa($hamburguesas, $hamburguesaExistente, $json_file);
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        echo "[Sucedio algo con el cupon, no existe, ha expirado o ya fue utilizado!]";
                        return false;
                    }
                } else {
                    if (Venta::GenerarVenta($email_usuario, $nombre_usuario, $hamburguesaExistente, $cantidad, $montoOriginal, $imagen)) {
                        Hamburguesa::ActualizarHamburguesa($hamburguesas, $hamburguesaExistente, $json_file);
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                echo "[No hay stock suficiente de la hamburguesa requerida!]";
                return false;
            }
        } else {
            echo "[No existe la hamburguesa buscada!]";
            return false;
        }
    }