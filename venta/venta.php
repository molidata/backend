<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM ventas WHERE venta_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM ventas");
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
    $sql="INSERT INTO ventas(venta_fecha, cliente_id, venta_proceso, usuario_id, sucursal_id) 
    VALUES(:fecha, :cliente, :proceso, :usuario, :sucursal)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':cliente', $_POST['cliente']);
    $stmt->bindValue(':proceso', $_POST['proceso']);
    $stmt->bindValue(':usuario', $_POST['usuario']);
    $stmt->bindValue(':sucursal', $_POST['sucursal']);
    
    $stmt->execute();
    $idVenta=$pdo->lastInsertId();
    if($idVenta)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idVenta);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $proceso = $data['proceso'];

    $sql = "UPDATE ventas SET venta_proceso=:proceso WHERE venta_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':proceso', $proceso);
    $stmt->execute();

    header("HTTP/1.1 200 OK");
    echo json_encode($id);
    exit;
}

//Si no corresponde a ninguna opción anterior
//header("HTTP/1.1 400 Bad Request");