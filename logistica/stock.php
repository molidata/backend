<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM stocks WHERE sucursal_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM stocks");
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
    $sql="INSERT INTO stocks(almacen_id, producto_id, cantidad, unidad_medida) 
    VALUES(:almacen, :producto, :cantidad, :medida)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':almacen', $_POST['almacen']);
    $stmt->bindValue(':producto', $_POST['producto']);
    $stmt->bindValue(':cantidad', $_POST['cantidad']);
    $stmt->bindValue(':medida', $_POST['medida']);
    
    $stmt->execute();
    $idstock=$pdo->lastInsertId();
    if($idstock)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idstock);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $condicion = $data['condicion'];

    if ($condicion === 'MOVIMIENTO-ALMACEN' || $condicion === 'CAJA-PAGAR') {
        $cantidad = $data['cantidad'];
        $sql = "UPDATE stocks SET cantidad=:cantidad WHERE stock_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cantidad', $cantidad);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    } elseif ($condicion === 'STOCK-MINIMO') {
        $stockmin = $data['stockmin'];
        $sql = "UPDATE stocks SET stock_minimo=:stockmin WHERE stock_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':stockmin', $stockmin);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    $idstock = $id;
    if ($idstock) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idstock);
        exit;
    }
}

//Si no corresponde a ninguna opción anterior
//header("HTTP/1.1 400 Bad Request");