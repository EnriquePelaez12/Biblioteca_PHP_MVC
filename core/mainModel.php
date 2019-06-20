<?php
//variable que identifica si se realiza una peticion ajax
if ( $peticionAjax) {
     require_once "../core/configAPP.php";
}else{
     require_once "./core/configAPP.php";
}
     class mainModel{
          //metodo para hacer la conexion
          protected function conectar(){
               $enlace = new PDO(DSN,USERNAME,PASSWORD);
               return $enlace;
          }

         protected function ejecutar_consulta_simple($consulta){
              $respuesta= self::conectar()->prepare($consulta);
              $respuesta->execute();
              return $respuesta;
         }
         //Para agregar una cuenta
         protected function agregar_cuenta($datos){
              $sql=self::conectar()->prepare("INSERT INTO cuenta(CuentaCodigo, CuentaPrivilegio, CuentaUsuario,
              CuentaClave, CuentaEmail, CuentaEstado, CuentaTipo, CuentaGenero, CuentaFoto) 
              VALUES(:Codigo,:Privilegio,:Usuario,:Clave,:Email,:Estado,:Tipo,:Genero,:Foto)");
              $sql->bindParam(":Codigo", $datos['Codigo']);
              $sql->bindParam(":Privilegio", $datos['Privilegio']);
              $sql->bindParam(":Usuario", $datos['Usuario']);
              $sql->bindParam(":Clave", $datos['Clave']);
              $sql->bindParam(":Email", $datos['Email']);
              $sql->bindParam(":Estado", $datos['Estado']);
              $sql->bindParam(":Tipo", $datos['Tipo']);
              $sql->bindParam(":Genero", $datos['Genero']);
              $sql->bindParam(":Foto", $datos['Foto']);
              $sql->execute();
              return $sql;
         }

         //Metodo para Eliminar una cuenta
         protected function eliminar_cuenta(){
              $sql=self::conectar()->prepare("DELETE FROM cuenta WHERE
              CuentaCodigo=:Codigo");
              $sql->bindParam(":Codigo",$codigo);
              $sql->execute();
              return $sql;
         }

         //para encriptar contraseña
         public static function encryption($string){
          $output=FALSE;
          $key=hash('sha256', SECRET_KEY);
          $iv=substr(hash('sha256', SECRET_IV), 0, 16);
          $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
          $output=base64_encode($output);
          return $output;
     }
     //para desencriptar
     public static function decryption($string){
          $key=hash('sha256', SECRET_KEY);
          $iv=substr(hash('sha256', SECRET_IV), 0, 16);
          $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
          return $output;
     }
     //Para geberar codigos aleatorios
     protected function generar_codigo_aleatorio($letra,$longitud,$num){
          //para calcular los numeros aleatorios
          for ($i=1; $i<=$longitud; $i++) {
               $numero = rand(0,9);
               $letra.= $numero; 
          }
          return $letra.$num;

     }

     //funcion para limpiar las cadenas en los formularios y evitar inyecion sql
     protected function limpiar_cadena($cadena){
          $cadena=trim($cadena); //quita los espacion que no necesita
          $cadena=stripslashes($cadena); //quita las barras invertidas
          $cadena=str_ireplace("<script>", "", $cadena); //para eliminar los script
          $cadena=str_ireplace("</script>", "", $cadena);//para eliminar la etiqueta de cierre
          $cadena=str_ireplace("<script src", "", $cadena);
          $cadena=str_ireplace("<script type=", "", $cadena);
          $cadena=str_ireplace("SELECT * FROM", "", $cadena);
          $cadena=str_ireplace("DELETE FROM", "", $cadena);
          $cadena=str_ireplace("INSERT INTO", "", $cadena);
          $cadena=str_ireplace("--", "", $cadena);
          $cadena=str_ireplace("^", "", $cadena);
          $cadena=str_ireplace("[", "", $cadena);
          $cadena=str_ireplace("]", "", $cadena);
          $cadena=str_ireplace("==", "", $cadena);
          $cadena=str_ireplace(";", "", $cadena);

          return $cadena;
     }

     //para las alertas
     protected function sweet_alert($datos){
          if($datos['Alerta']=="simple"){
               $alerta="
               <script>

               swal(
                    '".$datos['Titulo']."',
                    '".$datos['Texto']."',
                    '".$datos['Tipo']."'
               );
               
               </script>
               ";

          }elseif($datos['Alerta']=="recargar"){
               $alerta="
               <script>
               swal({
                    title: '".$datos['Titulo']."',
                    text:  '".$datos['Texto']."',
                    type:   '".$datos['Tipo']."',
                    confirmButtonText: 'Aceptar'
                  }).then(function () {
                       location.reload();
                  });
               </script>
               ";


          }elseif($datos['Alerta']=="limpiar"){
               $alerta="
               <script>
               swal({
                    title: '".$datos['Titulo']."',
                    text:  '".$datos['Texto']."',
                    type:   '".$datos['Tipo']."',
                    confirmButtonText: 'Aceptar'
                  }).then(function () {
                       $('.FormularioAjax')[0].reset();
                  });
               </script>
               ";

          
          }

          return $alerta;
     
     
     
     }


     }

    