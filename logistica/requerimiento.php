<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM requerimientos WHERE requerimiento_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()==1){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
    } else {
        $sql=$pdo->prepare("SELECT * FROM requerimientos");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()>0){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
        
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO requerimientos(requerimiento_fecha, usuario_id, sucursal_id, requerimiento_proceso, requerimiento_observaciones) 
    VALUES(:fecha, :usuario, :sucursal, :proceso, :observaciones)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':usuario', $_POST['usuario']);
    $stmt->bindValue(':sucursal', $_POST['sucursal']);
    $stmt->bindValue(':proceso', $_POST['proceso']);
    $stmt->bindValue(':observaciones', $_POST['observaciones']);
    
    $stmt->execute();
    $idRequerimiento=$pdo->lastInsertId();
    if($idRequerimiento)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idRequerimiento);
        exit;
    }
}


//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $condicion = $data['condicion'];

    if ($condicion === 'ATENCION-REQUERIMIENTO') {
        $proceso = $data['proceso'];
        $observaciones = $data['observaciones'];
        $sql = "UPDATE requerimientos SET requerimiento_proceso=:proceso, requerimiento_observaciones=:observaciones WHERE requerimiento_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':proceso', $proceso);
        $stmt->bindValue(':observaciones', $observaciones);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    } elseif ($condicion === '') {
        /* $proceso = $data['proceso'];
        $sql = "UPDATE requerimientos SET requerimiento_proceso=:proceso WHERE requerimiento_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':proceso', $proceso);
        $stmt->bindValue(':id', $id);
        $stmt->execute(); */
    }
    $idReque = $id;
    if ($idReque) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idReque);
        exit;
    }
}

//Si no corresponde a ninguna opción anterior
//header("HTTP/1.1 400 Bad Request");