<?php
include 'acceso_producto.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM stocks WHERE prod_id=:id AND suc_id=:sucursal");
        $sql->bindValue(':id', $_GET['id']);
        $sql->bindValue(':sucursal', $_GET['sucursal']);
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
        $sql=$pdo->prepare("SELECT * FROM stocks");
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
    $sql="INSERT INTO stocks(prod_id, suc_id, stock_disponible, stock_critico) 
    VALUES(:producto, :sucursal, :cantidad, :critico)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':producto', $_POST['producto']);
    $stmt->bindValue(':sucursal', $_POST['sucursal']);
    $stmt->bindValue(':cantidad', $_POST['cantidad']);
    $stmt->bindValue(':critico', $_POST['critico']);

    $stmt->execute();
    $idProd=$pdo->lastInsertId();
    if($idProd)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idProd);
        exit;
    }
}