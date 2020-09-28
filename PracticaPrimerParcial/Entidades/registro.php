<?php

require_once './filemanager.php';

Class registro{

    public $_email;
    public $_tipoUsuario;
    public $_password;


    public function __construct($email, $tipoUsuario,$password) {
        $this->_email = $email;
        $this->_tipoUsuario = $tipoUsuario;
        $this->_password = $password;
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

    public static function guardarRegistro($email, $tipoUsuario,$password){
        $usuario = new registro($email, $tipoUsuario,$password);
        filemanager::guardarJSON("registro.json", $usuario);
    }


    public static function LeerRegistro(){
        $arrayJson = filemanager::LeerJSON("registro.json");
        $arrayUsuarios = array();
        foreach($arrayJson as $datos){
            if(count((array)$datos) == 3){
                $usuarioNuevo = new registro($datos->_email, $datos->_tipoUsuario, $datos->_password);
                array_push($arrayUsuarios, $usuarioNuevo);
            }
        }
        return $arrayUsuarios; 
    }    
    

    public function verificar($array){
        $login = false;
        foreach($array as $item){
            if($item->_email == $this->_email){
                if($item->_password == $this->_password){
                    $login = true;
                }
            }
        }
        return $login;
    }

}