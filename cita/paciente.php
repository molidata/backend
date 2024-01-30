<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_cita.php';

$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM pacientes WHERE paciente_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()==1){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
        }
    } else {
        $sql=$pdo->prepare("SELECT * FROM pacientes");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()>0){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
        }
        
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO pacientes (apellido_paterno, apellido_materno, nombres, fecha_nacimiento, lugar_nacimiento, paciente_sexo, paciente_direccion, paciente_telefono, paciente_tipo, numero_poliza, eps_seguro, familiar_declarado) 
    VALUES(:apaterno, :amaterno, :nombres, :fechaNacimiento, :lugarNacimiento, :pacienteSexo, :direccion, :telefono, :tipoPaciente, :poliza, :eps, :familiar)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':apaterno', $_POST['apaterno']);
    $stmt->bindValue(':amaterno', $_POST['amaterno']);
    $stmt->bindValue(':nombres', $_POST['nombres']);
    $stmt->bindValue(':fechaNacimiento', $_POST['fechaNacimiento']);
    $stmt->bindValue(':lugarNacimiento', $_POST['lugarNacimiento']);
    $stmt->bindValue(':pacienteSexo', $_POST['pacienteSexo']);
    $stmt->bindValue(':direccion', $_POST['direccion']);
    $stmt->bindValue(':telefono', $_POST['telefono']);
    $stmt->bindValue(':tipoPaciente', $_POST['tipoPaciente']);
    $stmt->bindValue(':poliza', $_POST['poliza']);
    $stmt->bindValue(':eps', $_POST['eps']);
    $stmt->bindValue(':familiar', $_POST['familiar']);


    $stmt->execute();
    $idpac=$pdo->lastInsertId();
    if($idpac)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idpac);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $apaterno = $data['apaterno'];
    $amaterno = $data['amaterno'];
    $nombres = $data['nombres'];
    $fechaNacimiento = $data['fechaNacimiento'];
    $lugarNacimiento = $data['lugarNacimiento'];
    $pacienteSexo = $data['pacienteSexo'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
    $tipoPaciente = $data['tipoPaciente'];
    $poliza = $data['poliza'];
    $eps = $data['eps'];
    $familiar = $data['familiar'];

    $sql = "UPDATE pacientes SET apellido_paterno=:apaterno, apellido_materno=:amaterno, nombres=:nombres, fecha_nacimiento=:fechaNacimiento, lugar_nacimiento=:lugarNacimiento, paciente_sexo=:pacienteSexo, paciente_direccion=:direccion, paciente_telefono=:telefono, paciente_tipo=:tipoPaciente, numero_poliza=:poliza, eps_seguro=:eps, familiar_declarado=:familiar WHERE paciente_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':apaterno', $apaterno);
    $stmt->bindValue(':amaterno', $amaterno);
    $stmt->bindValue(':nombres', $nombres);
    $stmt->bindValue(':fechaNacimiento', $fechaNacimiento);
    $stmt->bindValue(':lugarNacimiento', $lugarNacimiento);
    $stmt->bindValue(':pacienteSexo', $pacienteSexo);
    $stmt->bindValue(':direccion', $direccion);
    $stmt->bindValue(':telefono', $telefono);
    $stmt->bindValue(':tipoPaciente', $tipoPaciente);
    $stmt->bindValue(':poliza', $poliza);
    $stmt->bindValue(':eps', $eps);
    $stmt->bindValue(':familiar', $familiar);
    
    $stmt->execute();
    $idPaciente = $id;
    if ($idPaciente) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idPaciente);
        exit;
    }
}