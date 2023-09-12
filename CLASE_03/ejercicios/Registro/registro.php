<?php
/**
 * 
 * APLICACIÓN -  20 BIS (Registro CSV) 
 * método:POST
 * Recibe los datos del usuario(nombre, clave,mail )por POST ,
 * crear un objeto y utilizar sus métodos para poder hacer el alta,
 * guardando los datos en usuarios.csv.
 * retorna si se pudo agregar o no.
 * Cada usuario se agrega en un renglón diferente al anterior.
 * Hacer los métodos necesarios en la clase usuario
 * 
 * Bessio Rocio Soledad
 */

    class Usuario{
        private $_nombre;
        private $_clave;
        private $_mail;

        public function __construct($nombre,$clave,$mail)
        {
            $this->_nombre = $nombre;
            $this->_clave = $clave;
            $this->_mail = $mail;
        }

        /**
         * Me permitirá formatear la información del usuario
         * como csv en una cadena de texto para luego retornarla.
         */
        private function FormatearCSV(){ 
            return $this->_nombre . ',' . $this->_mail . ',' . $this->_clave . ',';
        }

        /**
         * Me permitirá guardar un usuario en un archivo csv
         * con su respectivo formato.
         * 
         * Cada usuario se agrega en un renglón diferente al anterior.
         * 
         * #1: Compruebo que sea una instancia de Usuario.
         * #2: Formateo al usuario y recibo la cadena de texto.
         * #3: Abro el archivo y sino existe lo creo.
         * #4: Escribo en el archivo.
         * #5: Cierro el archivo.
         * #6: Todo salio bien devuelvo true.
         * #7: En caso de error con el archivo devuelvo false.
         * #8: Si no es una instancia de Usuario retorno false.
         */
        public static function GuardarUsuarioCSV($usuario){
            if($usuario instanceof Usuario){//#1
                $informacion = $usuario->FormatearCSV() . PHP_EOL;//#2

                $archivo = fopen('usuarios.csv','a');

                if($archivo !== false){

                    fwrite($archivo,$informacion);//#4

                    fclose($archivo);//#5

                    return true;//#6
                }
                else
                    return false;//#7
            }
            return false;//#8
        }
    }


?>