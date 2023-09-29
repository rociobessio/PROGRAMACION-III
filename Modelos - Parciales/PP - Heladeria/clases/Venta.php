<?php


    /**
     * Esta clase contendra funciones
     * relacionadas a la venta de productos.
     */
    class Venta{

        /**
         * Me permite buscar una venta en el
         * array de ventas mediante su numero
         * de pedido.
         * 
         * Retorna la venta si existe, null sino.
         */
        public static function BuscarVenta($ventas,$numeroPedido){
            foreach ($ventas as &$venta) {
                if ($venta["numero_pedido"] === $numeroPedido) {
                    return $venta;
                }
            }
            return null;
        }

        public static function GenerarVenta($email,$heladoExistente,$img,$cantidad){
            $json_file = './archivos/ventas.json';
            $ventas = array();

            if(file_exists($json_file)){
                $contenido = file_get_contents($json_file);
                $ventas = json_decode($contenido,true);
            }

            $nuevaVenta = [
                'id' => count($ventas) + 1,
                'fecha' => (new DateTime('now'))->format('Y-m-d'),
                'numero_pedido' => mt_rand(1,190000),
                'cantidad' => $cantidad,
                'imagen' => '',
            ];

            $ventas[] = $nuevaVenta;//-->Agrego la nueva al array

            //-->Guardo las ventas
            $ventasJSON = json_encode($ventas,JSON_PRETTY_PRINT);

            if(file_put_contents($json_file,$ventasJSON)){

                //-->Ahora guardo la imagen de la venta
                $posicionArroba = strpos($email, "@");
                $nombreUsuario = substr($email, 0, $posicionArroba);
                $nombreImagen = $heladoExistente['sabor'] . '_' . $heladoExistente['tipo'] . '_' . $heladoExistente['vaso'] . '_' .  $nombreUsuario . '_' . (new DateTime('now'))->format('Y-m-d') . '.jpg';
                $directorioImagenesVenta = './ImagenesDeVentas/2023/';
                $rutaImagenVenta = $directorioImagenesVenta . $nombreImagen;
                
                if (move_uploaded_file($img['tmp_name'], $rutaImagenVenta)) {
                    //--->Asigno la imagen
                    $nuevaVenta['imagen'] = $rutaImagenVenta;

                    //Actualizo la imagen en la venta generada.
                    foreach ($ventas as &$venta) {
                        if ($venta["id"] === $nuevaVenta["id"]) {
                            $venta["imagen"] = $nuevaVenta["imagen"];
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
                } else {
                    return false;
                }
                return true;
            }
            else
                return false;
        }
    }