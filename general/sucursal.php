<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM sucursales WHERE suc_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
    } else {
        $sql=$pdo->prepare("SELECT * FROM sucursales");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO sucursales(suc_nombre,suc_direccion) VALUES(:nombre, :direccion)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':direccion', $_POST['direccion']);
    $stmt->execute();
    $idSuc=$pdo->lastInsertId();
    if($idSuc)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idSuc);
        exit;
    }
}
//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $condicion = $data['condicion'];
    
    if ($condicion === 'CAMBIO-CODIGO-VALIDACION') {
        $codigo = $data['codigo'];
         
        $sql = "UPDATE sucursales SET codigo_autorizacion=:codigo WHERE suc_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':codigo', $codigo);
        $stmt->execute();
    } else {
        $nombre = $data['nombre'];
        $direccion = $data['direccion'];
    
        $sql = "UPDATE sucursales SET suc_nombre=:nombre, suc_direccion=:direccion WHERE suc_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':direccion', $direccion);
        $stmt->execute();
    }
    

    header("HTTP/1.1 200 OK");
    echo json_encode(['message' => 'Actualizacion exitosa: ' . $id]);
    exit;
}