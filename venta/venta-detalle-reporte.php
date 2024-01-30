<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {

        $sql=$pdo->prepare("SELECT * FROM venta_detalle vd LEFT JOIN ventas v ON v.venta_id=vd.venta_id  WHERE v.venta_proceso=:proceso AND v.venta_estado=:estado AND v.sucursal_id=:sucursal AND v.venta_fecha >=:fechaInicio AND v.venta_fecha<=:fechaFinal");
        $sql->bindValue(':proceso', $_GET['proceso']);
        $sql->bindValue(':estado', $_GET['estado']);
        $sql->bindValue(':sucursal', $_GET['sucursal']);

        $sql->bindValue(':fechaInicio', $_GET['fechaInicio']);
        $sql->bindValue(':fechaFinal', $_GET['fechaFinal']);

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
    
    
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");