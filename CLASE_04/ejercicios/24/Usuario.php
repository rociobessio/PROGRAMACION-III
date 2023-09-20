<?php
/**
 * Aplicación Nº 24 ( Listado JSON y array de usuarios)
 * 
 * Archivo: listado.php
 * 
 * método:GET
 * 
 * Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,etc.),por ahora solo tenemos
 * usuarios).
 * En el caso de usuarios carga los datos del archivo usuarios.json.
 * se deben cargar los datos en un array de usuarios.
 * Retorna los datos que contiene ese array en una lista.
 * Hacer los métodos necesarios en la clase usuario
 * 
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
         * Metodo para poder leer usuarios
         * desde un archivo json.
         * 
         * #1: Me fijo si el archivo existe y si se puede
         *     leer.
         * #2: Obtengo el contenido del archivo json.
         * #3: json_decode => Decodifica una json string y
         *     la convierte a un valor php.
         *     Recibe la string json a decodificar y true para 
         *     arrays asociativos q los retorne, si es false lo
         *     retorna como obj.
         * #4: Verifico que lo que devuelve el json_decode no
         *     sea null.
         * #5: Recorro usuarios_data y en un array voy guardando
         *     la info de los usuarios instanciandolos para luego 
         *     retornarlos.
         */
        public static function ObtenerUsuarios($file_name){
            $usuariosArray = array();

            if(file_exists($file_name) && is_readable($file_name)){
                $usuariosJSON = file_get_contents($file_name);//#2
                $usuarios_data = json_decode($usuariosJSON,true);//#3

                if($usuarios_data !== null){//#4

                    foreach ($usuarios_data as  $usuario) {//#5
                        $usuariosArray[] = new Usuario(
                            $usuario['id'],
                            $usuario['nombre'],
                            $usuario['mail'],
                            $usuario['clave'],
                            new DateTime($usuario['registro']),
                            $usuario['img']
                        );
                     }
                }
            }
            return $usuariosArray;
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
    }

?>