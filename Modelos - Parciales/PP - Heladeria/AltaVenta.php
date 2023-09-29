<?php
/**
 * Parte 01 - Punto 03
 * 
 * a- (1 pts.) AltaVenta.php: (por POST) se recibe el email del usuario y el Sabor, Tipo y Stock, si el ítem existe en
 * heladeria.json, y hay stock guardar en la base de datos( con la fecha, número de pedido y id autoincremental ) .
 * Se debe descontar la cantidad vendida del stock
 */

    require_once "validaciones.php";
    require_once "./clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['email']) && isset($_POST['sabor']) && isset($_POST['tipo']) && 
           isset($_POST['stock']) && isset($_FILES['imagen'])){

            $email = $_POST['email'];
            $sabor = $_POST['sabor'];
            $tipo = $_POST['tipo'];
            $stock = intval($_POST['stock']);
            $imagen = $_FILES['imagen'];

             //-->Me traigo el array de helados
            $json_file = './archivos/heladeria.json';
            $helados = array(); 

            if(file_exists($json_file)){
                $contenido = file_get_contents($json_file);
                $helados = json_decode($contenido,true);
            }

            $heladoExistente = Validaciones::BuscarHelado($helados,$sabor,$tipo); 

            if($heladoExistente !== null){//-->Primero me fijo si existe.
                if(Validaciones::verificarStock($heladoExistente,$stock)){
                    $heladoExistente["stock"] -= $stock;//-->Se descuenta el stock.

                    var_dump($heladoExistente);

                    if(Venta::GenerarVenta($email,$heladoExistente,$imagen,$stock)){
                        echo "[Venta guardada correctamente!]";

                        //-->Recien cuando pude generar la venta actualizo el producto en el array
                        foreach ($helados as &$helado) {
                            if ($helado["id"] == $heladoExistente["id"]) {
                                $helado = $heladoExistente;
                                break;
                            }
                        }

                        $heladosJSON = json_encode($helados,JSON_PRETTY_PRINT);

                        if(file_put_contents($json_file,$heladosJSON)){
                            echo "[Stock actualizado!]";
                        }
                        else
                            echo "[Ocurrio un error al querer actualizar el stock!]";
                    }
                    else{
                        echo "[Ocurrio un error al querer generar la venta!]";
                    }
                }
                else
                    echo "[La cantidad solicitada es mayor al stock disponible del producto!]";
            }
            else
                echo "[El helado no existe!]";
        }
        else
            echo "[Se deben de completar todos los datos!]";
    }
    else
        echo "[Error al procesar la request!]";