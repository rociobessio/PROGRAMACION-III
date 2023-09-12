<?php
/*
    APLICACIÓN N°18 (AUTO - GARAGE)

    Crear la clase Garage que posea como atributos privados:

    _razonSocial (String)
    _precioPorHora (Double)
    _autos (Autos[], reutilizar la clase Auto del ejercicio anterior)
    Realizar un constructor capaz de poder instanciar objetos pasándole como

    parámetros: i. La razón social.
    ii. La razón social, y el precio por hora.

    Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y
    que mostrará todos los atributos del objeto.
    Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
    objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
    Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage”
    (sólo si el auto no está en el garaje, de lo contrario informarlo).
    Ejemplo: $miGarage->Add($autoUno);
    Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del
    “Garage” (sólo si el auto está en el garaje, de lo contrario informarlo). Ejemplo:
    $miGarage->Remove($autoUno);

    Bessio Rocio Soledad
*/
        include "../Auto/Auto.php";

        class Garage{
            //Atributos
            private $_razonSocial;
            private $_precioPorHora;
            private $_autos = array();   

            //Constructor
            public function __construct($razonSocial,$precioPorHora)
            {
                $this->_razonSocial = $razonSocial;
                $this->_precioPorHora = $precioPorHora;
            }

            //Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y
            //que mostrará todos los atributos del objeto.
            public function MostrarGarage(){
                echo "<br/>Razón Social: "  . $this->_razonSocial . "<br/>"; 
                echo "<br/>Precio Por Hora: $"  . $this->_precioPorHora . "<br/>";  
                echo "<br/>Autos dentro del Garage: <br/>";
                //Con un foreach recorro el array
                foreach($this->_autos as $auto){
                    Auto::MostrarAuto($auto);//-->Invoco al metodo estatico para mostrar el auto del array
                    echo "<br/>";
                } 
            }

            //Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
            //objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
            public function Equals($auto){
                if($auto instanceof Auto){//-->Valido si es un Auto.
                    foreach($this->_autos as $autoAux){
                        if ($auto === $autoAux )
                            return true;
                    }
                }
                return false;
            }

            //Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage”
            //(sólo si el auto no está en el garaje, de lo contrario informarlo).
            public function Add(Auto $auto){

                //Verifico sino esta
                if(!($this->Equals($auto))){
                    $this->_autos[] = $auto;
                }
                else
                    echo "El auto ya se encuentra en el garage! <br/>";
            }
            

            //Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del
            //“Garage” (sólo si el auto está en el garaje, de lo contrario informarlo).
            public function Remove(Auto $auto){
                //Recorro en busca del auto dentro del array
                foreach($this->_autos as $key => $autosGarage){
                    if($auto === $autosGarage){//Si esta lo elimino
                        unset($this->_autos[$key]);
                        return;
                    }
                }
                echo "El auto NO se encuentra dentro del Garage!. <br/>";
            }   
            // ****************************** MANEJO DE ARCHIVOS ******************************
            
            /**
             * Formateo el garage a CSV
             * #1: Creo el array.
             * #2: Recorro los autos y los paso a csv.
             * #3: Por último retorno la cadena dandole el formato csv.
             */
            private function FormatearCSV(){
                $arrayAutos = array();//#1
                foreach($this->_autos as $autoGarage){//#2
                    $arrayAutos[] = $autoGarage->FormatoCSV();
                }
                return $this->_razonSocial . ',' . $this->_precioPorHora . ',' . implode(',',$arrayAutos);//#3
            }

            /**
             * Crear un método de clase para poder hacer el alta de un Garage y, guardando los datos en un archivo
             * garages.csv.
             * 
             * #1: Primero me fijo que lo que recibo es instanceof Garage.
             * #2: Mando a formatear esa instancia del garage.
             * #3: Abro el archivo de garages.csv sino existe lo crea.
             * #4: Si pudo abrirlo escribo en el.
             */
            public static function GuardarGarageCSV($garage){
                if ($garage instanceof Garage) {

                    $informacion = $garage->FormatearCSV() . PHP_EOL;//#2

                    $archivoCSV = fopen('garages.csv','a');//#3

                    if($archivoCSV !== false){//#4
                        fwrite($archivoCSV,$informacion);

                        fclose($archivoCSV);//-->Cierro el archivo.

                        return true;
                    }
                    else
                        return false;
                }
                else
                    return false;
            }

            /**
             * Hacer los métodos necesarios en la clase Garage para poder leer el listado desde el archivo
             * garage.csv
             * Se deben cargar los datos en un array de garage.
             * 
             * #1: Creo un array para almacenar la información.
             * #2: Me fijo si existe el archivo y si es leible.
             * #3: Lo abro.
             * #4: Leo linea por linea con la función fgetcsv pasandole el archivo y parsea de formato csv.
             */
            public static function LeerGarageCSV(){
                $garages = array();//#1

                if(file_exists('garages.csv') && (is_readable('garages.csv'))){//#2

                    $archivo = fopen('garages.csv','r');//#3

                    if($archivo !== false){

                        while(($informacion = fgetcsv($archivo)) !== false){//#4
                            $nuevoGarage = new Garage($informacion[0],
                                                      $informacion[1]);
                            

                            // Cargar los autos del Garage desde el archivo (puedes adaptar esto según la estructura de tu archivo CSV)
                            // Supongamos que los datos de los autos están en las siguientes columnas (después de nombre y precio por hora):
                            $marcaAuto = $informacion[2];
                            $colorAuto = $informacion[3];
                            $precioAuto = $informacion[4];
                            

                            // Crear un objeto Auto y agregarlo al Garage
                            $auto = new Auto($marcaAuto, $colorAuto, $precioAuto);
                            $nuevoGarage->Add($auto);
                        
                            $garages[] = $nuevoGarage;
                        }

                        fclose($archivo);
                    }
                    return $garages;
                }
            }
        }

?>