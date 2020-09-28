<?php


class filemanager{

    public static function LeerJSON($archivo){
        $file = fopen($archivo, 'a+');
        $size = filesize($archivo);
        if(file_exists($archivo))
        {
            if($size!=0){
            $fread = fread($file, $size);
            $arrayJSON = json_decode($fread);
            fclose($file);
            return $arrayJSON;

            }
            
        }else{
            fclose($file);
            echo 'No se pude leer el archivo. ';
        }
    }


    public static function guardarJSON($archivo, $objeto){
        $arrayJson= filemanager::LeerJSON($archivo);
        if (is_null($arrayJson))
        {
            $arrayJson = array();
        }
        array_push($arrayJson, $objeto);
        $file = fopen($archivo, 'w');
        $fwrite = fwrite($file, json_encode($arrayJson));
        fclose($file);
        return $fwrite;
    }     

}