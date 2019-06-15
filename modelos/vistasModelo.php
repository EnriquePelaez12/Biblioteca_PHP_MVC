<?php
//clase para protejer las rutas
class vistasModelo {
    protected function obtener_vistas_modelo($vistas){
        $listaBlanca=["adminlist", "adminseach", "admin", "bookconfig", "bookinfo", "book",
        "catalog", "categorylist", "category", "clientlist", "clientsearch", "client", "companylist",
        "company", "home", "login", "myaccount", "mydata", "providerlist", "provider", "search"];
        if (in_array($vistas,   $listaBlanca)) {
            if(is_file("./vistas/contenidos/".$vistas."-view.php")){
                $contenido ="./vistas/contenidos/".$vistas."-view.php";
            }else{
                $contenido="login";
            }
        }elseif($vistas =="login"){
            $contenido="login";
        }elseif($vistas=="index"){
            $contenido="login";
        }else{
            $contenido="404";
        }
        return   $contenido;

    
    }


    
}