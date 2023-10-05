<?php
    require_once "./clases/Producto.php";
    require_once "./clases/Venta.php";
    require_once "./clases/Archivo.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        if(isset($_GET['Consultar'])){
            switch ($_GET['Consultar']){
                case 'Cantidad_Producto_Fecha'://-->En una fecha especifica
                    if(isset($_GET['fecha'])){
                        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d', strtotime('-1 day'));
            
                        $json_file = './archivos/ventas.json';
                        $ventas = Archivo::ObtenerArray($json_file);

                        if(!empty($ventas) && $ventas !== null){  
            
                            //-->El contador iniciara en 0
                            $productosVendidas = 0;
                            foreach ($ventas as $venta) {
                                if($venta['fecha'] === $fecha){
                                    $productosVendidas++;
                                }
                            }
                            echo "[El dia: " . $fecha . " se vendieron " . $productosVendidas . " productos!]";
                        }
                        else
                            echo "[El archivo no existe!]";
                    }
                break;

                case 'Cantidad_Producto_Entre_Fechas'://-->Entre una fecha de inicio y otra de fin
                    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin']) ){ 
                        $fechaInicio = $_GET['fechaInicio'];
                        $fechaFin = $_GET['fechaFin'];

                        $ventasFiltro = array();
            
                        $json_file = './archivos/ventas.json';
                        $ventas = Archivo::ObtenerArray($json_file);

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

                case 'Ventas_Entre_Fechas_Nombre'://-->El listado de ventas entre dos fechas ordenado por nombre.
                    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin']) ){ 
                        $fechaInicio = $_GET['fechaInicio'];
                        $fechaFin = $_GET['fechaFin'];

                        $ventasFiltro = array();
            
                        $json_file = './archivos/ventas.json';
                        $ventas = Archivo::ObtenerArray($json_file);

                        if(!empty($ventas) && $ventas !== null){ 
                            foreach ($ventas as $venta) {
                                $fechaVenta = $venta['fecha'];
                                if ($fechaVenta >= $fechaInicio && $fechaVenta <= $fechaFin) {
                                    $ventasFiltradas[] = $venta;
                                }
                            }
                            
                            // Ordenar las ventas filtradas por nombre del producto
                            usort($ventasFiltradas, function ($ventaA, $ventaB) {
                                return strcmp($ventaA['nombre_producto'], $ventaB['nombre_producto']);
                            });
                            
                            // Imprimir el listado de ventas ordenado por nombre del producto
                            foreach ($ventasFiltradas as $venta) {
                                echo "Fecha: " . $venta['fecha'] . ", Producto: " . $venta['nombre_producto'] . ", Monto: $" . $venta['monto'] . "\n";
                            } 
                        }
                    }
                break;

                case 'Cantidad_Productos_Totales_Vendidos'://-->Cant. TOTAL de productos vendidos
                    $json_file = './archivos/ventas.json';
                    $ventas = Archivo::ObtenerArray($json_file);
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

                case 'Ventas_Usuario'://-->Cant. Ventas a un usuario en especifico.
                    if(isset($_GET['email_usuario'])){
                        $json_file = './archivos/ventas.json';
                        $ventas = Archivo::ObtenerArray($json_file);

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
                
                case 'Tipo_Producto'://-->Cant. con un tipo de producto especifico.
                    if(isset($_GET['tipo'])){
                        $json_file = './archivos/ventas.json';
                        $ventas = Archivo::ObtenerArray($json_file);
                        if(!empty($ventas) && $ventas !== null){ 
                            $tipo = $_GET['tipo'];

                            $ventasTipo = array();

                            foreach ($ventas as $venta) {
                                if($venta['tipo'] === $tipo){
                                    $ventasTipo[] = $venta;
                                }
                            }
                            echo "Las ventas realizadas con el tipo de producto: " . $tipo . " son: " . "</br>";
                            echo json_encode($ventasTipo,JSON_PRETTY_PRINT);
                        }
                        else
                        echo "[Ocurrio un error al intentar abrir el archivo de ventas!]";
                    }
                    else
                        echo "[Se necesita el tipo de producto para seguir!]";
                break;

                case 'Aderezo_Ketchup'://-->Ventas con un tipo de aderezo ESPECIFICIO
                    $json_file = './archivos/ventas.json';
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

                case 'Sabor_Producto'://-->Ventas con un tipo de sabor ingresado
                    if(isset($_GET['sabor'])){
                        $json_file = './archivos/ventas.json';
                        $ventas = Archivo::ObtenerArray($json_file);

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