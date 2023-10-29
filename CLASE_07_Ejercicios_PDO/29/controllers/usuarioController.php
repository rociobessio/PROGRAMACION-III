<?php

    require_once "./29/clases/usuario.php";
    
    /**
     * Clase para la logica de negocio
     */
    class UsuarioController{
        /**
         * Me permitira generar una instancia de usuario
         * para luego registrarla en la tabla. Se utilizan
         * setters para asignarle los parametros.
         * 
         * @param string $nombre nombre del usuario.
         * @param string $apellido apellido del usuario.
         * @param string $clave clave del usuario.
         * @param string $mail mail del usuario.
         * @param string $localidad localidad del usuario.
         * 
         * @return bool true si pudo registrarlo, false sino.
         */
        public function registrarUsuario($nombre,$apellido,$clave,$mail,$localidad){
            $usuario = new Usuario();
            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            $usuario->setClave($clave);
            $usuario->setMail($mail);
            $usuario->setLocalidad($localidad);
            $usuario->setFechaRegistro(new DateTime());
            
            if ($usuario->registrarUsuarioParametros()) {
                return true; // Si el registro fue exitoso
            } else {
                var_dump($usuario->registrarUsuarioParametros());
                return false; // Si hubo un error al registrar
            }
        }

        /**
         * Me permitira listar los usuarios
         * @return 
         */
        public function listarUsuarios(){
            return Usuario::traerTodosLosUsuarios();
        }

        /**
         * Me permite saber si existe o no
         * un usuario en la db.
         */
        public function loginUsuario($mail,$contraseña){
            return Usuario::verificarUsuario($mail,$contraseña);
        }
    }