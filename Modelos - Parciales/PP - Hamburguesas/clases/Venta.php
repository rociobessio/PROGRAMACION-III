<?php


    class Venta{

        /**
        * Busco en el archivo cupones.json
        * si existe el cupon de descuento
        * mediante su ID.
        * 
        * SI existe lo retorna, sino devuelve null.
        */
        public static function BuscarCuponDescuento($cuponID){
            $json_file = '../archivos/cupones.json';
            $cupones = array();

            if(file_exists($json_file)){
                $contenido = file_get_contents($json_file);
                $cupones = json_decode($contenido,true);
            }

            foreach ($cupones as &$cupon) {
                if ($cupon["id"] === $cuponID) {
                    return $cupon;
                }
            }
            return null;
        }

        /**
         * Metodo estatic que me permitira calcular
         * y retornar el total del descuento
         */
        public static function CalcularDescuento($precioHelado,$cuponDescuento){
            if($precioHelado >= 0 && $cuponDescuento >=1){
                $descuento = ($precioHelado * $cuponDescuento['porcentajeDescuento']) / 100 ;
                return $descuento;
            }
            else
                return 0;
        }

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

        /**
         * Esta funcion permite verificar si ha 
         * expirado o no el cupon.
         */
        public static function ValidarFechaVencimiento($cupon){
            $fechaActual = new DateTime('now');
            //var_dump($fechaActual);
            $fechaVencimientoCupon = new DateTime($cupon['vencimiento']);
            if ($fechaActual->format('Y-m-d') > $fechaVencimientoCupon) {
                return true;
            } 
            else 
                return false;
        }
        
        /**
        * Funcion generica para traerme un
        * array de productos de un archivo
        * JSON.
        */
        public static function ObtenerArray($jsonFile){
            $arrayObjetos = array();

            if(file_exists($jsonFile)){
                $contenido = file_get_contents($jsonFile);
                $arrayObjetos = json_decode($contenido,true);
            }
            return $arrayObjetos;
        }

        public static function GenerarVenta($email,$nombreUsuario,$hamburguesaExistente,$cantidad,$total,$img=null,$importeFinal=null){
            $json_file = '../archivos/ventas.json';
            $ventas = array();

            if(file_exists($json_file)){
                $contenido = file_get_contents($json_file);
                $ventas = json_decode($contenido,true);
            }

            $nuevaVenta = [
                'id' => count($ventas) > 0 ? count($ventas) + 1 : 1,
                'fecha' => (new DateTime('now'))->format('Y-m-d'),
                'numero_pedido' => mt_rand(1,190000),
                'cantidad' => intval($cantidad),
                'nombre_hamburguesa' => $hamburguesaExistente['nombre'],
                'tipo' => $hamburguesaExistente['tipo'],
                'aderezo' => $hamburguesaExistente['aderezo'],
                'total' => $total,
                //-->Si importeFinal es null quiere decir que el importe es el orignal, por ende asigo el original
                'importe_Final' => isset($importeFinal) ? $importeFinal : $total,
                'email_usuario' => $email,
                'nombre_usuario' => $nombreUsuario,
                'imagen' => '',
            ];

            $ventas[] = $nuevaVenta;//-->Agrego la nueva al array

            //-->Guardo las ventas
            $ventasJSON = json_encode($ventas,JSON_PRETTY_PRINT);

            if(file_put_contents($json_file,$ventasJSON)){

                //-->Ahora guardo la imagen de la venta
                $posicionArroba = strpos($email, "@");
                $stringFinal = substr($email, 0, $posicionArroba);
                $nombreImagen = $hamburguesaExistente['nombre'] . '_' . $hamburguesaExistente['tipo'] . '_' . $hamburguesaExistente['aderezo'] . '_' .  $stringFinal . '_' . (new DateTime('now'))->format('Y-m-d') . '.jpg';
                $directorioImagenesVenta = '../ImagenesDeVentas/2023/';
                $rutaImagenVenta = $directorioImagenesVenta . $nombreImagen;
                
                if($img !== null){
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