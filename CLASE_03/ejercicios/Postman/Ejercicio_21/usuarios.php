<?php
/**
 * 
 * APLICACIÓN -  21 BIS (Listado y arrays) 
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

        public function __construct($clave,$mail,$nombre = "")
        {
            $this->_nombre = $nombre;
            $this->_clave = $clave;
            $this->_mail = $mail;
        }

        /**
         * Método getter para obtener el nombre
         * de la instancia.
         */
        public function getNombre()
        { return $this->_nombre; }

        /**
         * Método getter para obtener la clave
         * de la instancia.
         */
        public function getClave()
        { return $this->_clave; }

        /**
         * Método getter para obtener el mail
         * de la instancia.
         */
        public function getMail()
        { return $this->_mail; }

        /**
         * Me permitirá formatear la información del usuario
         * como csv en una cadena de texto para luego retornarla.
         */
        private function FormatearCSV(){ 
            return   $this->_clave . ',' . $this->_mail . ',' . $this->_nombre . PHP_EOL;
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
                $informacion = $usuario->FormatearCSV();//#2

                $archivo = fopen('usuarios.csv','a');

                if($archivo !== false){

                    fwrite($archivo, $informacion) . PHP_EOL;//#4

                    fclose($archivo);//#5

                    return true;//#6
                }
                else
                    return false;//#7
            }
            return false;//#8
        }

        /***
        * En el caso de usuarios carga los datos del archivo usuarios.csv.
        * se deben cargar los datos en un array de usuarios.
        * Retorna los datos que contiene ese array en una lista
        * 
        * #1: Creo el array.
        * #2: Leo el contenido del archivo y utilizo flags para ignorar caracteres
        *     de linea y lineas vacias.
        * #3: rompo string 
        * #4: Cargo el usuario al array. 
        * #6: Retorno el array.
        */
       public static function CargarUsuariosCSV($nombreArchivo)
       {
           $usuariosArray = array();//#1
       
           if (file_exists($nombreArchivo) && (is_readable($nombreArchivo))) {
               $archivo = file($nombreArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);//#2
               foreach ($archivo  as $line) {
                   $data = explode(',', $line);//#3
                   if (count($data) === 3) {
                       $clave = $data[0];
                       $mail = $data[1];
                       $nombre = $data[2];
                       $usuariosArray[] = new Usuario($clave, $mail, $nombre);//#4
                   }
               }
           }
           return $usuariosArray;//#5
       }
    
    
        /**
         * Verificar si es un usuario registrado, Retorna
         * un :
         * “Verificado” si el usuario existe y coincide la clave también.
         * “Error en los datos” si esta mal la clave.
         * “Usuario no registrado si no coincide el mail“
         * 
         * #1: Obtengo la lista.
         * #2: Verifico los parametros que recibo
         * #3: Me fijo si coinciden los mails. Si lo hacen verifico que no
         *     haya error con la clave.
         * #4: Si no esta verificado
         * 
         */
        public function Verificar($mail,$clave){ 
            $usuarios = Usuario::CargarUsuariosCSV('usuarios.csv');//#1

            if(!empty($mail) && !empty($clave)){//#2
                if(count($usuarios) > 0){
                    foreach($usuarios as $usuario){
                        if($usuario instanceof Usuario){
                            if ($usuario->getMail() === $mail) {
                                if ($usuario->getClave() === $clave) {//#3
                                    return "[Usuario verificado!]";
                                } else {
                                    return "[Error en los datos ingresados!]";
                                }
                            }
                        }
                    }
                    return "[Usuario NO registrado!]";//#4
                }
                else
                    "[No ningun usuario registrado!]";
            }
            else
                return "[Se deben de completar los datos!]";
        }
    }
