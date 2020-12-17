<?php

require_once "modelos/usuarios_MO.php";

class usuarios_CO{

    function __construct(){}

    function verificarInicioSesion(){

        $usuario=$_POST["usuario"];
        $contrasena=$_POST["contrasena"];

        $conexion=new conexion('A');

        $usuarios_MO=new usuarios_MO($conexion);

        $arreglo_usuarios=$usuarios_MO->verificarInicioSesion($usuario,$contrasena);

        if($arreglo_usuarios)
        {
            $_SESSION["id_usuario"]=$arreglo_usuarios[0]->id_usuario;
            $_SESSION["nombre_rol"]=$arreglo_usuarios[0]->nombre_rol;

            $_SESSION["autenticado"]="SI";
            header("Location: index.php");
        }
        else
        {
            header("Location: index.php?error=ERROR: Usuario No Registrado&usuario=$usuario");
        }
    }

    function agregar(){

        $nombre=$_POST["nombre"];
        $nit=$_POST["nit"];
        $direccion=$_POST["direccion"];
        $usuario=$_POST["usuario"];
        $contrasena=$_POST["contrasena"];

        $conexion=new conexion('A');

        $usuarios_MO=new usuarios_MO($conexion);

        $arreglo_usuarios=$usuarios_MO->seleccionarPorUsuario($usuario);

        if($arreglo_usuarios)
        {
            $respuesta = [
                "estado" => "ADVERTENCIA",
                'mensaje' => "ADVERTENCIA: El usuario <b>$usuario</b> ya existe"
            ];
        }else{

            $filas_afectadas=$usuarios_MO->agregarUsuario($nombre,$nit,$direccion,$usuario,$contrasena);
            
            if($filas_afectadas){

                $arreglo_usuarios=$usuarios_MO->seleccionarUsuario($usuario);

                $id_usuario=$arreglo_usuarios[0]->id_accesos;
                $nombre=$arreglo_usuarios[0]->nombre;
                $nit=$arreglo_usuarios[0]->nit;
                $direccion=$arreglo_usuarios[0]->direccion;
                $usuario=$arreglo_usuarios[0]->usuario;
                $nombre_rol=$arreglo_usuarios[0]->nombre_rol;
                
                $respuesta = [
                    "estado" => "EXITO",
                    'mensaje' => "EXITO: Registro Guardado",
                    'id_usuario' => $id_usuario,
                    'nombre' => $nombre,
                    'nit' => $nit,
                    'direccion' => $direccion,
                    'usuario' => $usuario,
                    'nombre_rol' => $nombre_rol
                ];
            }
            else
            {
                $respuesta = [
                    "estado" => "ADVERTENCIA",
                    'mensaje' => "ADVERTENCIA: No se completo la consulta"
                ];
            }
        }

        echo json_encode($respuesta);
    }
}
?>