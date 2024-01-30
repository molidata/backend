<?php
header("Access-Control-Allow-Origin:*");
include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['name'])) {
        $sql=$pdo->prepare("SELECT * FROM usuarios WHERE user_name=:name");
        $sql->bindValue(':name', $_GET['name']);
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
        
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
        
        exit;
    }
}
