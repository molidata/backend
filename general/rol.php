<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM roles WHERE rol_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
    } else {
        $sql=$pdo->prepare("SELECT * FROM roles");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO roles(rol_nombre,rol_descripcion) VALUES(:nombre, :descripcion)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':descripcion', $_POST['descripcion']);
    $stmt->execute();
    $idRol=$pdo->lastInsertId();
    if($idRol)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idRol);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];

    $sql = "UPDATE roles SET rol_nombre=:nombre, rol_descripcion=:descripcion WHERE rol_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->execute();

    header("HTTP/1.1 200 OK");
    echo json_encode(['message' => 'Actualización exitosa: '] . $id);
    exit;
}