<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['sucursal'])) {
        $sql=$pdo->prepare("SELECT * FROM ventas WHERE sucursal_id=:sucursal AND venta_proceso=:proceso");
        $sql->bindValue(':sucursal', $_GET['sucursal']);
        $sql->bindValue(':proceso', 'CONFIRMADO');
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()>=1){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
    } else {
        
        header("HTTP/1.1 200 OK");
        echo json_encode("Ingrese un valor");
        exit;
        
        
    }
    
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");