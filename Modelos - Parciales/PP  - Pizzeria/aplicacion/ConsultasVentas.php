<?php
    require_once "./clases/Producto.php";
    require_once "./clases/Venta.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        if(isset($_GET['Consultar'])){
            switch ($_GET['Consultar']){
                case 'Cantidad_Producto_Entre_Fechas':
                    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin']) ){ 
                        $fechaInicio = $_GET['fechaInicio'];
                        $fechaFin = $_GET['fechaFin'];

                        $ventasFiltro = array();
            
                        $json_file = './archivos/ventas.json';
                        $ventas = Venta::ObtenerArray($json_file);

                        if(!empty($ventas) && $ventas !== null){ 
                            //-->Filtro las ventas
                            foreach ($ventas as $venta) {
                                $fechaVenta = $venta['fecha'];
                                if ($fechaVenta >= $fechaInicio && $fechaVenta <= $fechaFin) {
                                    $ventasFiltradas[] = $venta;
                                }
                            }

                            //-->Ordeno con funcion usort
                            usort($ventasFiltradas, function ($ventaA, $ventaB) {
                                return strcmp($ventaA['sabor'], $ventaB['sabor']);
                            });

                            foreach ($ventasFiltradas as $venta) {
                                echo "Fecha: " . $venta['fecha'] . ", Sabor: " . $venta['sabor'] . ", Monto: $" . $venta['importe_Final'] . "\n";
                            } 
                        }
                        else
                            echo "[Ocurrio un error al buscar las ventas!]";
                    }
                break;

                case 'Cantidad_Productos_Vendidas':
                    $json_file = './archivos/ventas.json';
                    $ventas = Venta::ObtenerArray($json_file);
                    if(!empty($ventas) && $ventas !== null){ 
        
                        //-->El contador iniciara en 0
                        $productosVendidas = 0;
                        foreach ($ventas as $venta) {
                            $productosVendidas += $venta['cantidad'];
                        }
                        echo "[En total se vendieron: " . $productosVendidas . " productos!]";
                    }
                    else
                        echo "[Ocurrio un error al abrir el archivo!]";
                break;

                case 'Ventas_Usuario':
                    if(isset($_GET['email_usuario'])){
                        $json_file = './archivos/ventas.json';
                        $ventas = Venta::ObtenerArray($json_file);

                        if(!empty($ventas) && $ventas !== null){ 
    
                            $correoEjemplo = $_GET['email_usuario'];
    
                            $ventasUsuario = array();
    
                            foreach ($ventas as $venta) {
                                if($venta['email_usuario'] === $correoEjemplo){
                                    $ventasUsuario[] = $venta;
                                }
                            }
    
                            echo "Las ventas realizadas al usuario con email: " . $correoEjemplo . " son: " . "</br>";
                            echo json_encode($ventasUsuario,JSON_PRETTY_PRINT);
                        }
                        else
                            echo "[Ocurrio un error al intentar abrir el archivo de ventas!]";
                    }
                    else
                        echo "[Se necesita ingresar el email del usuario para seguir!]";
                break;
                
                case 'Sabor_Producto':
                    if(isset($_GET['sabor'])){
                        $json_file = './archivos/ventas.json';
                        $ventas = Venta::ObtenerArray($json_file);

                        if(!empty($ventas) && $ventas !== null){  
                            $sabor = $_GET['sabor'];

                            $ventasSabor = array();

                            foreach ($ventas as $venta) {
                                if($venta['sabor'] === $sabor){
                                    $ventasSabor[] = $venta;
                                }
                            }
                            echo "Las ventas realizadas con el sabor de producto: " . $sabor . " son: " . "</br>";
                            echo json_encode($ventasSabor,JSON_PRETTY_PRINT);
                        }
                        else
                        echo "[Ocurrio un error al intentar abrir el archivo de ventas!]";
                    }
                    else
                        echo "[Se necesita el tipo de producto para seguir!]";
                break; 
                //-->b- El listado de ventas entre dos fechas ordenado por nombre.

                default:
                echo "[Consulta no disponible!]";
                break;
            }
        }
        else
            echo "[Se debe de completar la accion!]";
    }