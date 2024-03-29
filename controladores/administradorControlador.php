<?php

   //variable que identifica si se realiza una peticion ajax
if ( $peticionAjax) {
    require_once "../modelos/administradorModelo.php";
}else{
    require_once "./modelos/administradorModelo.php";
}
         class administradorControlador extends administradorModelo{
             //funcion para ahrehar un administrador
            public function agregar_administrador_controlador(){
                $dni=mainModel::limpiar_cadena($_POST['dni-reg']);
                $nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
                $apellido=mainModel::limpiar_cadena($_POST['apellido-reg']);
                $telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
                $direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);
                
                $usuario=mainModel::limpiar_cadena($_POST['usuario-reg']);
                $password1=mainModel::limpiar_cadena($_POST['password1-reg']);
                $password2=mainModel::limpiar_cadena($_POST['password2-reg']);
                $email=mainModel::limpiar_cadena($_POST['email-reg']);
                $genero=mainModel::limpiar_cadena($_POST['optionsGenero']);
                $privilegio=mainModel::limpiar_cadena($_POST['optionsPrivilegio']);

                if($genero=="Masculino"){
                    $foto="Male3Avatar.png";
                }else{
                    $foto="Female3Avatar.pgn";
                }

                if($password1!=$password2){//se comprueba que las dos contraseñas sean las mismas
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrio Un Error Inesperado",
                        "Texto"=>"La contraseña No Coincide por favor verifique las Contraseñas",
                        "Tipo"=>"error"
                    ];//array

                }else{
                    //consicion para que el dni no se repita
                    $consulta1=mainModel::ejecutar_consulta_simple("SELECT AdminDNI 
                    FROM admin WHERE AdminDNI='$dni'");
                    if($consulta1->rowCount()>=1){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrio Un Error Inesperado",
                            "Texto"=>"El DNI que acaba de ingresar ya se encuentra registrado en el sistema",
                            "Tipo"=>"error"
                        ];//array
    
                    }else{
                        //condicion para que sea valido el gmail
                        if ($email!="") {
                            $consulta2=mainModel::ejecutar_consulta_simple("SELECT CuentaEmail 
                            FROM cuenta WHERE CuentaEmail='$email'");
                            $ec=$consulta2->rowCount();
                        }else{
                            $ec=0;
                        }
                        if ($ec>=1) {
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrio Un Error Inesperado",
                                "Texto"=>"El Email que acaba de ingresar ya se encuentra registrado en el sistema",
                                "Tipo"=>"error"
                            ];//array
                        } else {
                            $consulta3=mainModel::ejecutar_consulta_simple("SELECT 
                            CuentaUsuario FROM cuenta WHERE CuentaUsuario='$usuario'");
                            if ($consulta3->rowCount()>=1) {
                                $alerta=[
                                    "Alerta"=>"simple",
                                    "Titulo"=>"Ocurrio Un Error Inesperado",
                                    "Texto"=>"El USUARIO que acaba de ingresar ya se encuentra registrado en el sistema",
                                    "Tipo"=>"error"
                                ];
                            } else {
                                $consulta4=mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta");
                                $numero=($consulta4->rowCount())+1;

                                $codigo=mainModel::generar_codigo_aleatorio("AC",7,$numero);

                                //se encripta la contraseña
                                $clave=mainModel::encryption($password1);

                                $dataAC=[
                                    "Codigo"=>$codigo, 
                                    "Privilegio"=>$privilegio,
                                    "Usuario"=>$usuario,
                                    "Clave"=>$clave,
                                    "Email"=>$email,
                                    "Estado"=>"Activo",
                                    "Tipo"=>"Administrador",
                                    "Genero"=>$genero,
                                    "Foto"=>$foto
                                ];
                                $guardarCuenta=mainModel::agregar_cuenta($dataAC);
                                if($guardarCuenta->rowCount()>=1){
                                    $dataAD=[
                                        "DNI"=>$dni,
                                        "Nombre"=>$nombre,
                                        "Apellido"=>$apellido,
                                        "Telefono"=>$telefono,
                                        "Direccion"=> $direccion,
                                        "Codigo"=>$codigo
                                    ];
                                    $guardarAdmin=administradorModelo::
                                    agregar_administrador_modelo($dataAD);

                                    if ($guardarAdmin->rowCount()>=1) {
                                        $alerta=[ 
                                            "Alerta"=>"limpiar",
                                            "Titulo"=>"Administrador Registrado",
                                            "Texto"=>"El ADMINISTRADOR SE REGISTRO CON EXITO EN EL SISTEMA",
                                            "Tipo"=>"success"
                                        ];
                                    } else {
                                        mainModel::eliminar_cuenta($codigo);
                                        $alerta=[
                                            "Alerta"=>"simple",
                                            "Titulo"=>"Ocurrio Un Error Inesperado",
                                            "Texto"=>"No Hemos Podido Registrar el ADMINISTRADOR",
                                            "Tipo"=>"error"
                                        ];
                                    }
                                }else{
                                    $alerta=[
                                        "Alerta"=>"simple",
                                        "Titulo"=>"Ocurrio Un Error Inesperado",
                                        "Texto"=>"No Hemos Podido Registrar el ADMINISTRADOR",
                                        "Tipo"=>"error"
                                    ];
                                }

                            
                            }
                            
                        }
                        

                    }

                }
                return mainModel::sweet_alert($alerta);

            }

         }


?>