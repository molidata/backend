<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_contable.php';
$pdo = new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $sql = $pdo->prepare("SELECT * FROM comprobante_detalles WHERE detalle_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if ($sql->rowCount() == 1) {
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        } else {
            header("HTTP/1.1 200 OK");
            echo json_encode("No hay resultados");
            exit;
        }
    } else {
        $sql = $pdo->prepare("SELECT * FROM comprobante_detalles");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if ($sql->rowCount() > 0) {
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        } else {
            header("HTTP/1.1 200 OK");
            echo json_encode("No hay resultados");
            exit;
        }
    }
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO comprobante_detalles(comprobante, producto_codigo, producto_nombre, precio_venta, cantidad_venta, medida) 
    VALUES(:comprobante, :codigo, :nombre, :precio, :cantidad, :medida)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':comprobante', $_POST['comprobante']);
    $stmt->bindValue(':codigo', $_POST['codigo']);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':precio', $_POST['precio']);
    $stmt->bindValue(':cantidad', $_POST['cantidad']);
    $stmt->bindValue(':medida', $_POST['medida']);
    $stmt->execute();
    $idComprobanteDetalle = $pdo->lastInsertId();
    if ($idComprobanteDetalle) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idComprobanteDetalle);
        exit;
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $sql = "UPDATE comprobante_detalles SET cantidad_venta=:cantidad, precio_venta=:precio, descuento=:descuento WHERE detalle_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cantidad', $_GET['cantidad']);
    $stmt->bindValue(':precio', $_GET['precio']);
    $stmt->bindValue(':descuento', $_GET['descuento']);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Eliminar registro
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $sql = "DELETE FROM comprobante_numeracion WHERE det_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");
