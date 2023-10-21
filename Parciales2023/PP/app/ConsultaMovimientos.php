<?php
    require_once "./classes/Cuenta.php";
    require_once "./classes/Deposito.php";
    /**
     * Datos a consultar:
     * a- El total depositado (monto) por tipo de cuenta y moneda en un día en
     * particular (se envía por parámetro), si no se pasa fecha, se muestran las del día
     * anterior.
     * b- El listado de depósitos para un usuario en particular.
     * c- El listado de depósitos entre dos fechas ordenado por nombre.
     * d- El listado de depósitos por tipo de cuenta.
     * e- El listado de depósitos por moneda.
     */
    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        if(isset($_GET['Consulta'])){
            switch ($_GET['Consulta']){
                case 'TotalDepositadoDiaParticular':
                    if(isset($_GET['moneda']) && isset($_GET['tipoCuenta'])){
                        //-->En caso de no recibir una fecha en particular se asignara la de hoy
                        $fecha = $_GET['fechaParticular'] ?? date("Y-m-d");
                        $moneda = $_GET['moneda'];
                        $tipoCuenta = $_GET['tipoCuenta'];
                        $resultado = Deposito::calcularTotalDepositos($moneda,$tipoCuenta,$fecha);
                        if (!$resultado) {
                            echo "[No se ha podido calcular el total de depositos !]";
                        }
                    }
                    else
                        echo json_encode(['ERROR' => 'Se necesita el ingreso de parametros!']);    
                break;
                case 'ListadoUsuarioParticular'://-->El listado de depósitos para un usuario en particular.
                    if(isset($_GET['emailUsuario'])){
                        $correoIngresado = $_GET['emailUsuario'];
                        $jsonFile = './archivos/banco.json';
                        $cuentas = Cuenta::leerJSON($jsonFile);
                        $resultado = Deposito::buscarYListarMovimientosUsuario($cuentas,$correoIngresado);
                        if(!$resultado){
                            echo "[No hay depositos realizados para el usuario " . $correoIngresado . " !]<br>";
                        } 
                    }
                    else
                        echo json_encode(['ERROR' => 'Se necesita el ingreso de parametros!']);    
                break;
                case 'ListadoFechasNombre'://->c- El listado de depósitos entre dos fechas ordenado por nombre.
                    if(isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])){ 
                        $fechaInicio = $_GET['fechaInicio'];
                        $fechaFin = $_GET['fechaFin'];
                        $jsonFileNameDepositos = './archivos/depositos.json';
                        $depositos = Deposito::leerJSON($jsonFileNameDepositos);
                        $jsonFileNameCuentas = './archivos/banco.json';
                        $cuentas = Cuenta::leerJSON($jsonFileNameCuentas);
                        $resultado = Deposito::buscarYListarDepositosEntreFechas($fechaInicio,$fechaFin,$depositos,$cuentas);
                        if(!$resultado){ 
                            echo "[No hay depositos realizados con fecha de inicio " . $fechaInicio ." y fecha fin: " . $fechaFin ." !]<br>";
                        }
                    }
                    else
                        echo json_encode(['ERROR' => 'Se necesita el ingreso de parametros!']);    
                break;
                case 'ListarTipoCuenta'://-->El listado de depósitos por tipo de cuenta.
                    if(isset($_GET['tipoCuenta'])){
                        $tipoCuentaIngresada= $_GET['tipoCuenta']; 
                        $resultado = Deposito::buscarYListarDepositosTipoCuenta($tipoCuentaIngresada);
                        if(!$resultado){ 
                            echo "[No hay depositos con el tipo de cuenta: " . $tipoCuentaIngresada . " !]<br>";
                        }
                    }
                    else
                        echo json_encode(['ERROR' => 'Se necesita el ingreso de parametros!']);   
                break;
                case 'ListarPorMoneda'://-->e- El listado de depósitos por moneda.
                    if(isset($_GET['moneda'])){
                        $monedaIngresada= $_GET['moneda']; 
                        $resultado = Deposito::buscarYListarDepositosTipoMoneda($monedaIngresada);
                        if(!$resultado){ 
                            echo "[No hay depositos con el tipo de moneda: " . $monedaIngresada . " !]<br>";
                        }
                    }
                    else
                        echo json_encode(['ERROR' => 'Se necesita el ingreso de parametros!']);  
                break;
                default:
                    echo json_encode(['ERROR' => 'Accion CONSULTA no permitida']);
                break;
            }
        }else {
            echo json_encode(['ERROR' => 'Falta el parametro Consulta']);
        }
    }