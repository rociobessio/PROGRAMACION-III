<?php
/*
    APLICACIÓN 19 - AUTO (POO)

    //#1
    Realizar una clase llamada “Auto” que posea los siguientes atributos
    privados: _color (String)
              _precio (Double)
              _marca (String).
              _fecha (DateTime)
    //#2
    Realizar un constructor capaz de poder instanciar objetos pasándole como
    parámetros: i. La marca y el color.
                ii. La marca, color y el precio.
                iii. La marca, color, precio y fecha.

    #3:
    Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble
    por parámetro y que se sumará al precio del objeto.

    #4:
    Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto”
    por parámetro y que mostrará todos los atributos de dicho objeto.

    #5
    Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
    devolverá TRUE si ambos “Autos” son de la misma marca.

    #6
    Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son
    de la misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con
    la suma de los precios o cero si no se pudo realizar la operación.

    #7
    Ejemplo: $importeDouble = Auto::Add($autoUno, $autoDos);

    #8:
    Crear un método de clase para poder hacer el alta de un Auto, guardando los datos en un archivo
    autos.csv.
    
    #9:
    Hacer los métodos necesarios en la clase Auto para poder leer el listado desde el archivo
    autos.csv
    
    #10:
    Se deben cargar los datos en un array de autos.

    Bessio Rocio Soledad
*/

    class Auto{
        //Atributos
        private $_color;
        private $_precio;
        private $_marca;
        private $_fecha;

        //Constructor ?:
        public function __construct($marca,$color,$precio = null, $fecha = null){
            $this->_marca = $marca;
            $this->_color = $color;
            $this->_precio = $precio;
            $this->_fecha = $fecha;
        }

        //Metodo de Instancia
        public function AgregarImpuestos($impuesto = 0){
            if(($impuesto > 0) && ($this->_precio !== null))
            { 
                $this->_precio += $impuesto;
            }
        }

        //Método de Clase:                  (Auto)?
        public static function MostrarAuto(Auto $auto = null){
            if($auto != null){
                echo "<br/>Marca: " . $auto->_marca . "<br/>";  
                echo "Color: " . $auto->_color . "<br/>";
                //Verifico que no sean null
                if($auto->_precio !== null)
                    echo "Precio: $" . $auto->_precio . "<br/>";
                if($auto->_fecha !== null)
                    echo "Fecha: " . $auto->_fecha->format('Y-m-d') . "<br/>";
            }
        }

        //Metodo de Instancia:
        public function Equals(Auto $auto_1,Auto $auto_2){
            if(!($auto_1 == null) && !($auto_2 == null)){//-->Verifico que no sean nulos
                return ($auto_1->_marca == $auto_2->_marca);//-->Si las marcas son iguales, devuelve true.
            }
        }

        //Metodo de clase
        public static function Add(Auto $auto_1,Auto $auto_2){ 
            if($auto_1->Equals($auto_1,$auto_2) && ($auto_1->_color == $auto_2->_color)){

                if($auto_1->_precio !== null && $auto_2->_precio !== null){
                    return $auto_1->_precio + $auto_2->_precio;//-->Se suman los precios
                }
            }
            else
            {
                echo "Los autos no pueden sumarse. No coinciden en marca y color!";
                return 0;
            }
        }

        // ****************************** MANEJO DE ARCHIVOS ******************************

        /***
         * Crear un método de clase para poder hacer el alta de un Auto, guardando los datos en un archivo 
         * autos.csv.
         * 
         * #1: Compruebo que lo que recibo sea una instancia de Auto.
         * #2: Recibo la cadena de texto del metodo que formatea el auto para csv,
         *     a su vez lo concateno con PHP_EOL que agrega un '\n'.
         * #3: Abro el archivo y con el puntero al final del mismo. Sino existe lo crea.
         * #4: Si pudo abrirlo, escribe pasandole el archivo y la cadena de texto del auto,
         *     luego cierra el archivo y retorna true.
         */
        public static function GuardarAutoCSV($auto){
            if($auto instanceof Auto){//#1

                $informacion = $auto->FormatoCSV() . PHP_EOL;//#2

                //#3
                $archivoCSV = fopen('autos.csv','a');

                //#4
                if($archivoCSV !== false){
                    
                    fwrite($archivoCSV,$informacion);//-->Escribo en el archivo

                    fclose($archivoCSV);//-->Cierro el archivo.

                    return true;
                }
                else
                    return false;//-->Fallo la carga
            }
            return false;
        }

        /***
        * Me permitirá manejar el formato de como
        * guardar el auto.
        * #1: Primero paso la información del auto a
        * un array.
        * #2:Fecha puede ser null, compruebo, le doy un formato, en caso de estar vacia le asigno ''.
        * #3: Por último el metodo implode convierte elementos de un array a cadena 
        *     de texto, y separo esa información con el primer param (',') y la retorna.
        */
        public function FormatoCSV(){
            $informacion = array(//#1
                $this->_marca,
                $this->_color,
                $this->_precio,
                //#2
                ($this->_fecha !== null) ? $this->_fecha->format('d-m-Y') : ''
            );
            return implode(',', $informacion);//#3
        }

        /** 
        * Hacer los métodos necesarios en la clase Auto para poder leer el listado desde el archivo
        * autos.csv
        * #1: Primero me fijo si existe el archivo y si es leible.
        * #2: Lo abro como lectura
        * #3: Leo linea por linea con la función fgetcsv pasandole el archivo y parsea de formato csv.
        * #4: Con la información creo el objeto.
        * #5: En caso de que la fecha del csv sino esta vacia creo una instancia de datetime asignandoselo, si lo esta
        *     le asigno null.
        * #6: Lo agrego al array de autos.
        */
        public static function LeerCSV(){

            $autos_array = array();

            if(file_exists('autos.csv') && (is_readable('autos.csv'))){//#1
                
                $archivo = fopen('autos.csv','r');//#2

                if($archivo !== false){
                    
                    while(($informacion = fgetcsv($archivo)) !== false){//#3
                        //#4
                        $auto = new Auto(
                            $informacion[0],
                            $informacion[1],
                            $informacion[2],
                            !empty($informacion[3]) ? new DateTime($informacion[3]) : null//#5
                        );
                        $autos_array[] = $auto;//#6
                    }
                }
                fclose($archivo);
            }
            else{ 
                return "No se puede importar el archivo.<br/>";
            }
            return $autos_array;
        }
    }
?>