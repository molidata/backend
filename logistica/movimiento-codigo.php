<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {

        $sql=$pdo->prepare("SELECT * FROM movimientos WHERE movimiento_tipo=:tipo  AND movimiento_origen=:origen AND codigo_origen=:codigo");
        $sql->bindValue(':tipo', $_GET['tipo']);
        $sql->bindValue(':origen', $_GET['origen']);
        $sql->bindValue(':codigo', $_GET['codigo']);
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