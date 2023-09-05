<?php

    echo "<h1 align=" . "center" .">Explicación Clase 02</h1>";

    //Inclusion de archivos:
    include "ruta_archivo.php"; //-->El include corre aunque el script lance error
    include_once "ruta_archivo.php";//-->Se incluira una unica vez

    require "ruta_archivo.php";//-->El requiere si hay error no sigue el script
    require_once "ruta_archivo.php";//-->El requiere si hay error no sigue el script


    /* Creo una FUNCIÓN
       Puedo asignarle valores por defecto como en el $parametro_2 
       en caso de que no reciba nada ese parametro y toma el que le 
       defino en la funcion.
     */
    function nombreFuncion($parametro_1,$parametro_2 = null){


        return (boolean)$parametro_2;//Retorno de mi función, si lo casteo le especifico lo que debe de devolver si o si
    }


    //--> Declaracion de una CLASE
    class NombreClase{

        //ATRIBUTOS -> [modificador de visibilidad (private,protected,public/var, static) - nombre]
        public $nombreAtributo;
        static $atributoEstatico;
        protected $id;

        //Declaración de CONSTRUCTOR --> Solo existe UN constructor, no hay SOBRECARGAS
        //Permite recibir PARAMETROS
        public function __construct()
        {
            //código
        }

        //Declaración de METODOS -> [ modificador - function - nombre - parametros ]
        public function NombreMetodo($id,$nombre){
            //código
            //Inicializar variables
            if($this->validar($id)){
                $this->id = $id; 
            }
        }

        static function MetodoDeClase(){}

        //Retornará true si el id es mayor a 0
        private function validar($id){
            if($id > 0)
                return true;
        }

    }


    //HERENCIA de CLASES
    class ClaseDerivada extends NombreClase{

        public function __construct()
        {
            parent::__construct();//Llamo al constructor de la clase BASE
        }
    }

    //Declaración de un OBJETO
    $nombreObjeto = new NombreClase();

    //METODOS de INSTANCIA
    $nombreObjeto->NombreMetodo(0,"rocio");

    //ATRIBUTOS de INSTANCIA
    $nombreObjeto->$nombreAtributo;

    //Metodos de CLASE -> Se accede mediante ::
    NombreClase::MetodoDeClase();

    //ATRIBUTOS ESTATICOS -> Se accede mediante ::
    NombreClase::$atributoEstatico;

    //*******************************************************************/

    //Declaración de INTERFACES
    interface IInterfaz{
        function Metodo();
    }

    //Implementacion de una INTERFAZ en la CLASE
    class ClaseInterfaz implements IInterfaz{
        public function Metodo() {}
    }
?>