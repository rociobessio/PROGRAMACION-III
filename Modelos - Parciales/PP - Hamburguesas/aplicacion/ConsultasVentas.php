<?php
    require_once "../clases/Hamburguesa.php";
    require_once "../clases/Venta.php";


    if($_SERVER['REQUEST_METHOD'] === 'GET'){
         if(isset($_GET['Consultar'])){
            switch ($_GET['Consultar']){
                case 'Cantidad_Hamburguesa_Fecha':
                    if(isset($_GET['fecha'])){
                        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d', strtotime('-1 day'));
            
                        $json_file = '../archivos/ventas.json';
                        if(file_exists($json_file)){
                            $contenido = file_get_contents($json_file);
                            $ventas = json_decode($contenido,true);
            
                            //-->El contador iniciara en 0
                            $hamburguesasVendidas = 0;
                            foreach ($ventas as $venta) {
                                if($venta['fecha'] === $fecha){
                                    $hamburguesasVendidas++;
                                }
                            }
                            echo "[El dia: " . $fecha . " se vendieron " . $hamburguesasVendidas . " hamburguesas!]";
                        }
                        else
                            echo "[El archivo no existe!]";
                    }
                break;

                case 'Ventas_Usuario':
                    $json_file = '../archivos/ventas.json';
                    if(isset($_GET['email_usuario'])){
                        if(file_exists($json_file)){
                            $contanidoVentas = file_get_contents($json_file);
                            $ventas = json_decode($contanidoVentas,true);
    
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

                case 'Tipo_Hamburguesa':
                    $json_file = '../archivos/ventas.json';
                    if(isset($_GET['tipo'])){
                        if(file_exists($json_file)){
                            $contanidoVentas = file_get_contents($json_file);
                            $ventas = json_decode($contanidoVentas,true);
                            $tipo = $_GET['tipo'];

                            $ventasTipo = array();

                            foreach ($ventas as $venta) {
                                if($venta['tipo'] === $tipo){
                                    $ventasTipo[] = $venta;
                                }
                            }
                            echo "Las ventas realizadas con el tipo de hamburguesa: " . $tipo . " son: " . "</br>";
                            echo json_encode($ventasTipo,JSON_PRETTY_PRINT);
                        }
                        else
                        echo "[Ocurrio un error al intentar abrir el archivo de ventas!]";
                    }
                    else
                        echo "[Se necesita el tipo de hamburguesa para seguir!]";
                break;
                case 'Aderezo_Ketchup':
                    $json_file = '../archivos/ventas.json';
                    if(file_exists($json_file)){
                        $contanidoVentas = file_get_contents($json_file);
                        $ventas = json_decode($contanidoVentas,true); 

                        $ventasAderezo = array();

                        foreach ($ventas as $venta) {
                            if($venta['aderezo'] === "Ketchup" || $venta['aderezo'] === "ketchup" ){
                                $ventasAderezo[] = $venta;
                            }
                        }
                        echo "Las ventas realizadas con el tipo de aderezo: ketchup son: " . "</br>";
                        echo json_encode($ventasAderezo,JSON_PRETTY_PRINT);
                    }
                    else
                    echo "[Ocurrio un error al intentar abrir el archivo de ventas!]";
                break;
                //-->b- El listado de ventas entre dos fechas ordenado por nombre.

                default:
                    echo "[Consulta no disponible!]";
                break;
            }

         }
         else
            echo "[Se deben de completar los datos!]";
    }