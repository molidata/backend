<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_caja.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM operaciones WHERE ope_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM operaciones");
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
    $sql="INSERT INTO operaciones(user_id, fecha_pago, ope_tipo, monto_pago, motivo_pago, motivo_codigo, descripcion_pago, medio_pago, medio_detalle) 
    VALUES(:usuario, :fecha, :tipo, :monto, :motivo, :motivoCodigo, :descripcion, :medio, :medioDetalle)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':usuario', $_POST['usuario']);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':tipo', $_POST['tipo']);
    $stmt->bindValue(':monto', $_POST['monto']);
    $stmt->bindValue(':motivo', $_POST['motivo']);
    $stmt->bindValue(':motivoCodigo', $_POST['motivoCodigo']);
    $stmt->bindValue(':descripcion', $_POST['descripcion']);
    $stmt->bindValue(':medio', $_POST['medio']);
    $stmt->bindValue(':medioDetalle', $_POST['medioDetalle']);
    $stmt->execute();
    $idOpe=$pdo->lastInsertId();
    if($idOpe)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idOpe);
        exit;
    }
}