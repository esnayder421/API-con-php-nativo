<?php
require_once 'conexion.php';
echo "informacion:". file_get_contents('php://input');
//echo "metodo HTTP:".$_SERVER['REQUEST_METHOD'];

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        //echo "consultar";
        
        $respuesta = $con->query("SELECT * FROM usuarios");
        $datos[]= array();
        while ($fila=$respuesta->fetch_object()) {
            $datos[]=$fila;
        }
        echo json_encode($datos);
    break;

    case 'POST':
        //echo "guardar";
    $json = file_get_contents('php://input');
    $array = json_decode($json); 
    
    $respuesta = $con->query("SELECT * FROM usuarios WHERE usuario='{$array->usuario}'");
    if ($respuesta->num_rows>0) {
        echo json_encode(array('mensaje'=>'Ya existe el usuario'));
    }else {
        $respuesta = $con->query("INSERT INTO usuarios (`id`, `nombre`, `usuario`, `contraseña`, `rango`) 
        VALUES (default, '{$array->nombre}', '{$array->usuario}', '{$array->contraseña}', '{$array->rango}')");
        if ($respuesta) {
            echo json_encode(array('mensaje'=>'Se registro correctamente'));
        }
    }

    break;

    case 'PUT':
        //echo "actuallizar";
        $json = file_get_contents('php://input');
        $array = json_decode($json); 
        
        $respuesta = $con->query("SELECT * FROM usuarios WHERE usuario='{$array->usuario}'");
        if ($respuesta->num_rows>0) {
            echo json_encode(array('mensaje'=>'Ya existe el usuario'));
        }else {
            $respuesta = $con->query("UPDATE `usuarios` 
            SET `nombre`='{$array->nombre}',`usuario`='{$array->usuario}',`contraseña`='{$array->contraseña}',`rango`='{$array->rango}' 
            WHERE `id`='{$array->id}'");
            if ($respuesta) {
                echo json_encode(array('mensaje'=>'Se actualizo correctamente'));
            }
        }
    break;


    case 'DELETE':
        $json = file_get_contents('php://input');
        $array = json_decode($json); 
       // echo "eliminar";
       $respuesta = $con->query("DELETE FROM `usuarios` WHERE `id`='{$array->id}'");
            if ($respuesta) {
                echo json_encode(array('mensaje'=>'Se elimino correctamente'));
            }
    break;
}
?>