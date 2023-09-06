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
            public function Equals(Auto $auto){
                foreach($this->_autos as $autoAux){
                    if ($auto === $autoAux )
                        return true;
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
        }

?>