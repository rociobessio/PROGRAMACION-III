<?php

    require_once "./27/clases/usuario.php";
    
    /**
     * Clase para la logica de negocio
     */
    class usuarioController{
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
    }