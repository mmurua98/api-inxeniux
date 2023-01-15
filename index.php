<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root";  $contraseña = ""; $nombreBaseDatos = "api-inxeniux";
$conexionBD = new mysqli($servidor, $usuario, $contraseña, $nombreBaseDatos);


// Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
if (isset($_GET["consultar"])){
    $sqlUsuarios = mysqli_query($conexionBD,"SELECT id, nombre,apellido_paterno,apellido_materno, 
                                            DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fechaNacimiento,
                                            estado_civil, 
                                            telefono, pais, estado, municipio,localidad, codigo_postal, 
                                            idioma, pasatiempo, preferencias FROM users WHERE id=".$_GET["consultar"]);
    if(mysqli_num_rows($sqlUsuarios) > 0){
        $userId = mysqli_fetch_all($sqlUsuarios,MYSQLI_ASSOC);
        echo json_encode($userId);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//borrar pero se le debe de enviar una clave ( para borrado )
if (isset($_GET["borrar"])){
    $sqlUsuarios = mysqli_query($conexionBD,"DELETE FROM users WHERE id=".$_GET["borrar"]);
    if($sqlUsuarios){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//Inserta un nuevo registro y recepciona en método post los datos de nombre y correo
if(isset($_GET["insertar"])){
    $data = json_decode(file_get_contents("php://input"));
    $nombre=$data->nombre;
    $apellido_paterno=$data->apellido_paterno;
    $apellido_materno=$data->apellido_materno;
    $fecha_nacimiento=$data->fecha_nacimiento;
    $estado_civil=$data->estado_civil;
    $telefono=$data->telefono;
    $pais=$data->pais;
    $estado=$data->estado;
    $municipio=$data->municipio;
    $localidad=$data->localidad;
    $codigo_postal=$data->codigo_postal;
    $idioma=$data->idioma;
    $pasatiempo=$data->pasatiempo;
    $preferencias=$data->preferencias;
        if(($nombre!="")&&($apellido_paterno!="")&&($apellido_materno!="")&&($fecha_nacimiento!="")
        &&($estado_civil!="")&&($pais!="")&&($estado!="")&&($municipio!="")&&($localidad!="")&&($codigo_postal!="")&&($idioma!="")&&($pasatiempo!="")&&($preferencias!="")){
            
    $sqlUsuarios = mysqli_query($conexionBD,"INSERT INTO users (nombre,apellido_paterno,apellido_materno,
                                                fecha_nacimiento,estado_civil,telefono, pais, estado, municipio, 
                                                localidad,codigo_postal,idioma,pasatiempo,preferencias) 
                                                VALUES('$nombre','$apellido_paterno','$apellido_materno',
                                                (STR_TO_DATE('$fecha_nacimiento','%d-%m-%Y')),'$estado_civil','$telefono', '$pais', '$estado', '$municipio', 
                                                '$localidad','$codigo_postal','$idioma','$pasatiempo','$preferencias') ");
    echo json_encode(["success"=>1]);
        }
    exit();
    
}
// Actualiza datos pero recepciona datos de nombre, correo y una clave para realizar la actualización
if(isset($_GET["actualizar"])){
    
    $data = json_decode(file_get_contents("php://input"));

    $id=(isset($data->id))?$data->id:$_GET["actualizar"];

    $nombre=$data->nombre;
    $apellido_paterno=$data->apellido_paterno;
    $apellido_materno=$data->apellido_materno;

    $fecha_nacimiento=$data->fecha_nacimiento;

    $estado_civil=$data->estado_civil;
    $telefono=$data->telefono;
    $pais=$data->pais;
    $estado=$data->estado;
    $municipio=$data->municipio;
    $localidad=$data->localidad;
    $codigo_postal=$data->codigo_postal;
    $idioma=$data->idioma;
    $pasatiempo=$data->pasatiempo;
    $preferencias=$data->preferencias;
    
    $sqlUsuarios = mysqli_query($conexionBD,"UPDATE users SET nombre='$nombre', apellido_paterno='$apellido_paterno', apellido_materno='$apellido_materno',
                                            fecha_nacimiento= STR_TO_DATE('$fecha_nacimiento','%d-%m-%Y'), estado_civil='$estado_civil',telefono='$telefono', pais='$pais', 
                                            estado='$estado', municipio='$municipio', 
                                            localidad='$localidad',codigo_postal='$codigo_postal',idioma='$idioma',
                                            pasatiempo='$pasatiempo',preferencias='$preferencias' WHERE id='$id'");
    echo json_encode(["success"=>1]);
    exit();
}
// Consulta todos los registros de la tabla usuarios
$sqlUsuarios = mysqli_query($conexionBD,"SELECT id, CONCAT(nombre, ' ' ,apellido_paterno, ' ' ,apellido_materno) AS nombre_completo, 
                                        DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fechaNacimiento,
                                        TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) AS edad, estado_civil, 
                                        telefono, pais, estado, municipio,localidad, codigo_postal, 
                                        idioma, pasatiempo, preferencias FROM users;");
if(mysqli_num_rows($sqlUsuarios) > 0){
    $usuarios = mysqli_fetch_all($sqlUsuarios,MYSQLI_ASSOC);
    echo json_encode($usuarios);
}
else{ echo json_encode([["success"=>0]]); }


?>