<?php

    require_once "../clases/Producto.php";
    require_once "../clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['numeroPedido']) && isset($_POST['causaDevolucion']) && isset($_FILES['imagenCliente'])){
            $numeroPedido = intval($_POST['numeroPedido']);
            $causaDevolucion = $_POST['causaDevolucion'];
            $imagenCliente = $_FILES['imagenCliente'];

            $jsonfile = '../archivos/ventas.json';
            $jsonfileCupones = '../archivos/cupones.json';
            $jsonfileDevoluciones = '../archivos/devoluciones.json';

            $ventas = Venta::ObtenerArray($jsonfile);//-->Traigo el array
            $directorioImagenes = '../ImagenesDeDevolucion/2023/';
            $ventaEncontrada = Venta::BuscarVenta($ventas,$numeroPedido);

            if(count($ventas) > 0){

                if( $ventaEncontrada!== null){
                    $nombreImg = $numeroPedido . '_' . $causaDevolucion . '_' . uniqid() . '.jpg' ;  
                    $rutaImg = $directorioImagenes . $nombreImg; 

                    $devoluciones = Venta::ObtenerArray('../archivos/devoluciones.json');

                    // if($devoluciones !== null){
                        $nuevaDevolucion = [
                            'id' => uniqid(),
                            'numero_pedido' => $numeroPedido,
                            'causa_devolucion' => $causaDevolucion,
                            'imagen_devolucion' => $rutaImg
                        ];
                        
                        //-->Genero el CUPON
                        $cupon = [
                            'id' => uniqid(),
                            'devolucion_id' => $nuevaDevolucion['id'],
                            'porcentajeDescuento' => 10,
                            'estado' => 'no usado',//-->Lo guardo como que no esta usado el cupon.
                            'vencimiento' => date('Y-m-d', strtotime('+3 days')),//-->Vence en 3 días
                        ];

                        if(move_uploaded_file($imagenCliente['tmp_name'],$rutaImg)){
                            $nuevaDevolucion['imagen_devolucion'] = $rutaImg;//-->Asigno la imagen 
                        }

                        $devoluciones[] = $nuevaDevolucion;
                        file_put_contents($jsonfileDevoluciones, json_encode($devoluciones, JSON_PRETTY_PRINT));
                        
                        $cuponesJSON = file_get_contents($jsonfileCupones);
                        $cupones = json_decode($cuponesJSON, true);
                        $cupones[] = $cupon;//-->Guardo ese cupon en el array
                        file_put_contents($jsonfileCupones, json_encode($cupones, JSON_PRETTY_PRINT));

                        echo "[La devolucion fue generada correctamente. Se ha generado un cupón de descuento!]";

                    // }
                }
                else
                    echo "[No se encontro la venta solicitada con el número de pedido: " . $numeroPedido;
            }
            else
                echo "[No hay ventas dentro del archivo!]";
        }
        else
            echo "[Se necesitan todos los datos para poder realizar la devolucion!]";
    }