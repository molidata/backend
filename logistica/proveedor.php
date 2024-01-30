<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM proveedores WHERE proveedor_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM proveedores");
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
    $sql="INSERT INTO proveedores(documento_tipo, documento_numero, razon_social, proveedor_direccion, proveedor_telefono, proveedor_email, proveedor_descripcion, representante_ventas ) 
    VALUES(:documento, :numero, :nombre, :direccion, :telefono, :email, :descripcion, :representante)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':documento', $_POST['documento']);
    $stmt->bindValue(':numero', $_POST['numero']);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':direccion', $_POST['direccion']);
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->bindValue(':telefono', $_POST['telefono']);
    $stmt->bindValue(':descripcion', $_POST['descripcion']);
    $stmt->bindValue(':representante', $_POST['representante']);
    $stmt->execute();
    $idProveedor=$pdo->lastInsertId();
    if($idProveedor)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idProveedor);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $documento = $data['documento'];
    $numero = $data['numero'];
    $nombre = $data['nombre'];
    $direccion = $data['direccion'];
    $email = $data['email'];
    $telefono = $data['telefono'];
    $descripcion = $data['descripcion'];
    $representante = $data['representante'];


    $sql = "UPDATE proveedores SET documento_tipo=:documento, documento_numero=:numero, razon_social=:nombre, proveedor_direccion=:direccion, proveedor_email=:email, proveedor_telefono=:telefono, proveedor_descripcion=:descripcion, representante_ventas=:representante WHERE proveedor_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':documento', $documento);
    $stmt->bindValue(':numero', $numero);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':direccion', $direccion);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':telefono', $telefono);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':representante', $representante);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $idProveedor = $id;
    if ($idProveedor) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idProveedor);
        exit;
    }
}