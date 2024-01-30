<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_contable.php';
$pdo = new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $sql = $pdo->prepare("SELECT * FROM comprobante_tipos WHERE tipo_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if ($sql->rowCount() == 1) {
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        } else {
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
    } else {
        $sql = $pdo->prepare("SELECT * FROM comprobante_tipos");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if ($sql->rowCount() > 0) {
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        } else {
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
    }
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO comprobante_tipos(tipo_nombre, codigo_sunat) 
    VALUES(:tipo, :codigo)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tipo', $_POST['tipo']);
    $stmt->bindValue(':codigo', $_POST['codigo']);

    $stmt->execute();
    $idDet = $pdo->lastInsertId();
    if ($idDet) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idDet);
        exit;
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $sql = "UPDATE comprobante_tipos SET tipo_nombre=:tipo, codigo_sunat=:codigo WHERE tipo_id =:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tipo', $_GET['tipo']);
    $stmt->bindValue(':codigo', $_GET['codigo']);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Eliminar registro
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $sql = "DELETE FROM comprobante_tipos WHERE tipo_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");
