
<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_caja.php';
$pdo = new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $sql = $pdo->prepare("SELECT * FROM caja_sesion WHERE sesion_id=:id");
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
        $sql = $pdo->prepare("SELECT * FROM caja_sesion");
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
    $sql = "INSERT INTO caja_sesion(user_id, sesion_fecha, sesion_tipo, sesion_monto) VALUES(:usuario, :fecha, :tipo, :monto)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':usuario', $_POST['usuario']);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':tipo', $_POST['tipo']);
    $stmt->bindValue(':monto', $_POST['monto']);
    $stmt->execute();
    $idSesion = $pdo->lastInsertId();
    if ($idSesion) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idSesion);
        exit;
    }
}
