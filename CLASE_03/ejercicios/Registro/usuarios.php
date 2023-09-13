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

        public function __construct($nombre = "",$clave,$mail)
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
            return $this->_nombre . ',' . $this->_mail . ',' . $this->_clave;
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

        /***
         * En el caso de usuarios carga los datos del archivo usuarios.csv.
         * se deben cargar los datos en un array de usuarios.
         * Retorna los datos que contiene ese array en una lista
         * 
         * #1: Creo el array.
         * #2: Me fijo si existe el archivo sino lo creo y ademas si es leible.
         * #3: Miestras el csv contenga información leo.
         * #4: Cargo el usuario al array.
         * #5: Cierro el archivo.
         * #6: Retorno el array.
         */
        public static function CargarUsuariosCSV($nombreArchivo){
            $usuariosArray = array();//#1
        
            if(file_exists($nombreArchivo) && (is_readable($nombreArchivo))){//#2
                $archivo = fopen($nombreArchivo,'r');

                if($archivo !== false){

                    while(($informacion = fgetcsv($archivo)) !== false){//#3
                        $usuariosArray[] = new Usuario(//#4
                            $informacion[0],
                            $informacion[1],
                            $informacion[2]
                        );
                    }

                    fclose($archivo);//#5
                }
            }
            return $usuariosArray;//#6
        }

        /**
         * Verificar si es un usuario registrado, Retorna
         * un :
         * “Verificado” si el usuario existe y coincide la clave también.
         * “Error en los datos” si esta mal la clave.
         * “Usuario no registrado si no coincide el mail“
         * 
         * #1: Verifico que la lista no sea null.
         * #2: Recorro la lista.
         * #3: Me fijo si coinciden los mails. Si lo hacen verifico que no
         *     haya error con la clave.
         * #4: Si no esta verificado retorno que el usuario ingresado
         *     NO esta registrado.
         */
        public function Equals($listaUsuarios){
            if(!($listaUsuarios == null) && !empty($listaUsuarios)){
                foreach ($listaUsuarios as $usuario) {
                    if ($usuario->_mail === $this->_mail) {
                        if ($usuario->_clave === $this->_clave) { //#3
                            return "[Usuario verificado!]";
                        } else {
                            return "[Error en los datos ingresados!]";
                        }
                    }
                }
            }
            return "[Usuario NO registrado!]";
        }
    }


?>