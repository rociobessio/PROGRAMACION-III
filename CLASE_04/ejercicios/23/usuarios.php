<?php
/**
 * 
 * Aplicación No 23 (Registro JSON)
 * 
 * Archivo: registro.php
 * 
 * método:POST
 * 
 * Recibe los datos del usuario(nombre, clave,mail )por POST ,
 * crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). crear un dato con la
 * fecha de registro , toma todos los datos y utilizar sus métodos para poder hacer el alta,
 * guardando los datos en usuarios.json y subir la imagen al servidor en la carpeta
 * Usuario/Fotos/.
 * retorna si se pudo agregar o no.
 * Cada usuario se agrega en un renglón diferente al anterior.
 * Hacer los métodos necesarios en la clase usuario.
 * 
 * Bessio Rocio Soledad
 */

    class Usuario{
        private $_nombre;
        private $_clave;
        private $_mail; 

        private $_id;
        private $_fechaRegistro;
        private $_imgUsuario;

        /**
         * crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000).
         */
        public function __construct($clave,$mail,$nombre = null,$urlImg = null)
        {
            if($nombre !== null && $urlImg !== null){
                $this->_nombre = $nombre;
                $this->_clave = $clave;
                $this->_mail = $mail;   
    
                $this->_id = mt_rand(1,10000);
                $this->_fechaRegistro = date('Y-m-d H:m:s');//-->Le asigno la fecha actual y asi no se pisa
                $this->_imgUsuario = $urlImg;
            }
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
         * Retorno el id del usuario.
         */
        public function getID(){
            return $this->_id;
        }

        /**
         * Retorna la fecha de registro.
         */
        public function getFechaRegistro(){
            return $this->_fechaRegistro;
        } 

        /**
         * Retorno el path de la imagen del usuario.
         */
        public function getPathImagen(){
            return $this->_imgUsuario;
        }

        public function setImagen($img){
            $this->_imgUsuario = $img;
        }

        /**
         * Me permitirá dar el alta de un usuario en un archivo json
         * y subir la imagen al servidor en la carpeta.
         * 
         * Cada usuario se agrega en un renglón diferente al anterior.
         * 
         * #0: Me fijo si ya existe registro en usuarios y los obtengo.
         * 
         * #1: Compruebo que sea una instancia de Usuario.
         * 
         * #2: Creo un array asociativo pasandole los datos de
         *     la instancia de usuario que recibo.
         * 
         * #3: El array asociativo lo paso a formato JSON
         * 
         * #4: Guardo al usuario en el archivo .json 
         * 
         */
        public static function AltaUsuarios($usuario){
            if($usuario instanceof Usuario){//#1
                
                //#4
                $file_name = "usuarios.json";

                $usuarios = array();
                //#0
                if(file_exists($file_name)){
                    $content_json = file_get_contents($file_name);
                    $usuarios = json_decode($content_json,true);
                }

                //#2
                $user = [
                    'id' => $usuario->getID(),
                    'nombre' => $usuario->getNombre(),
                    'mail' => $usuario->getMail(),
                    'clave' => $usuario->getClave(),
                    'registro' => $usuario->getFechaRegistro(),
                    'img' => $usuario->getPathImagen()
                ];

                $usuarios[] = $user;
                
                //#3
                $usuarioJSON = json_encode($usuarios,JSON_PRETTY_PRINT);
                //#4
                if(file_put_contents($file_name,$usuarioJSON)){
                    return true;
                }

                return false;
            }
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
            if(!($listaUsuarios == null) && !empty($listaUsuarios)
               && (is_array($listaUsuarios))){

                foreach ($listaUsuarios as $usuario) {

                    if($usuario instanceof Usuario){
                        if ($usuario->_mail === $this->_mail) {
                            if ($usuario->_clave === $this->_clave) { //#3
                                return "[Usuario verificado!]";
                            } else {
                                return "[Error en los datos ingresados!]";
                            }
                        }
                    }
                }
            }
            return "[Usuario NO registrado!]";
        }
    
        /*
        public function Verificar($mail,$clave){
            $usuarios = Usuario::cargarUsuariosDesdeCSV('usuarios.csv');

            foreach($usuarios as $usuario){
                if ($usuario['mail'] === $mail) {
                    if ($usuario['clave'] === $clave) { //#3
                        return "[Usuario verificado!]";
                    } else {
                        return "[Error en los datos ingresados!]";
                    }
                }
            }
            return "[Usuario NO registrado!]";
        }*/
    }
