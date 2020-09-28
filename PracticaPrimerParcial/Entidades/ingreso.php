<?php

require_once './filemanager.php';

Class ingreso{

    public $_patente;
    public $_fecha_ingreso;
    public $_tipo;

    public function __construct($patente, $fecha_ingreso = '', $tipo = '') {
        $this->_patente = $patente;
        $this->_fecha_ingreso = $fecha_ingreso;
        $this->_tipo = $tipo;       
    }

    public function __get($name)
    {
        return $this->$name;
    }
    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return $this->_email.'*'.$this->_clave;
        //return json_encode($this);
    }



    public static function guardarIngreso($patente, $fecha_ingreso , $tipo ){
        $ingreso = new ingreso($patente, $fecha_ingreso, $tipo);
        filemanager::guardarJSON("ingreso.json", $ingreso);
    }


    public static function leerIngreso(){
        $arrayJson = filemanager::LeerJSON("ingreso.json");
        $arrayIngresos = array();
        foreach($arrayJson as $datos){
            if(count((array)$datos) == 3){
                $ingresoNuevo = new ingreso($datos->_patente, $datos->_fecha_ingreso, $datos->_tipo);
                array_push($arrayIngresos, $ingresoNuevo);
            }
        }
        return $arrayIngresos; 
    }    
    

    public function verificar($array){
        $login = false;
        foreach($array as $item){
            if($item->_email == $this->_email){
                if($item->_clave == $this->_clave){
                    $login = true;
                }
            }
        }
        return $login;
    }

}