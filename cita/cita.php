<?php
include 'acceso_cita.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM cita WHERE cita_id=:id");
        $sql->bindValue(':id', $_GET['id']);
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
        $sql=$pdo->prepare("SELECT * FROM cita");
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
    $sql="INSERT INTO cita(cita_documento, numero_documento,cita_nombre, cita_direccion, cita_email, cita_telefono) 
    VALUES(:documento, :numero, :nombre, :direccion, :email, :telefono)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':documento', $_POST['documento']);
    $stmt->bindValue(':numero', $_POST['numero']);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':direccion', $_POST['direccion']);
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->bindValue(':telefono', $_POST['telefono']);
    $stmt->execute();
    $idcli=$pdo->lastInsertId();
    if($idcli)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idcli);
        exit;
    }
}