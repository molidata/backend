<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM movimientos WHERE movimiento_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM movimientos");
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
    $sql="INSERT INTO movimientos(movimiento_fecha, movimiento_tipo, usuario_id, sucursal_id, movimiento_origen, codigo_origen, movimiento_observaciones) 
    VALUES(:fecha, :tipo, :usuario, :sucursal, :origen, :origencodigo, :observaciones)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':tipo', $_POST['tipo']);
    $stmt->bindValue(':usuario', $_POST['usuario']);
    $stmt->bindValue(':sucursal', $_POST['sucursal']);
    $stmt->bindValue(':origen', $_POST['origen']);
    $stmt->bindValue(':origencodigo', $_POST['origencodigo']);
    $stmt->bindValue(':observaciones', $_POST['observaciones']);
    
    $stmt->execute();
    $idMovimiento=$pdo->lastInsertId();
    if($idMovimiento)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idMovimiento);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD']=='PUT') {
    $sql="UPDATE movimientos SET cliente_id=:cliente, compra_proceso=:proceso, compra_estado=:estado WHERE compra_id=:id";
    $stmt=$pdo->prepare($sql);
    $sql->bindValue(':id', $_GET['id']);
    $stmt->bindValue(':cliente', $_GET['cliente']);
    $stmt->bindValue(':proceso', $_GET['proceso']);
    $stmt->execute();
    $idcompra=$_GET['id'];
    
        header("HTTP/1.1 200 OK");
        echo json_encode($idcompra);
        exit;
    
}

//Si no corresponde a ninguna opción anterior
//header("HTTP/1.1 400 Bad Request");