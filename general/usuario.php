<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM usuarios WHERE user_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
    } else {
        $sql=$pdo->prepare("SELECT * FROM usuarios");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit;
    }
}

//INSERTAR NUEVO USUARIO
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO usuarios(user_name, user_nombre, user_correo, user_telefono, user_clave, fecha_registro, rol_id, sucursal_id, user_panel) 
    VALUES(:user_name, :user_nombre, :user_correo, :user_telefono, :user_clave, :fecha_registro, :rol_id, :sucursal_id, :user_panel)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':user_name', $_POST['user_name']);
    $stmt->bindValue(':user_nombre', $_POST['user_nombre']);
    $stmt->bindValue(':user_correo', $_POST['user_correo']);
    $stmt->bindValue(':user_telefono', $_POST['user_telefono']);
    $stmt->bindValue(':user_clave', MD5($_POST['user_clave']));
    $stmt->bindValue(':fecha_registro', $_POST['fecha_registro']);
    $stmt->bindValue(':rol_id', $_POST['rol_id']);
    $stmt->bindValue(':sucursal_id', $_POST['sucursal_id']);
    $stmt->bindValue(':user_panel', $_POST['user_panel']);
    
    $stmt->execute();
    $idUser=$pdo->lastInsertId();
    if($idUser)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idUser);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $condicion = $data['condicion'];

    if ($condicion === 'ADMINISTRAR-USUARIO') {
        $user_name = $data['user_name'];
        $user_nombre = $data['user_nombre'];
        $user_correo = $data['user_correo'];
        $user_telefono = $data['user_telefono'];
        $rol_id = $data['rol_id'];
        $sucursal_id = $data['sucursal_id'];
        $user_panel = $data['user_panel'];
        $sql = "UPDATE usuarios SET user_name=:user_name, user_nombre=:user_nombre, user_correo=:user_correo, user_telefono=:user_telefono, rol_id=:rol_id, sucursal_id=:sucursal_id, user_panel=:user_panel  WHERE user_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_name', $user_name);
        $stmt->bindValue(':user_nombre', $user_nombre);
        $stmt->bindValue(':user_correo', $user_correo);
        $stmt->bindValue(':user_telefono', $user_telefono);
        $stmt->bindValue(':rol_id', $rol_id);
        $stmt->bindValue(':sucursal_id', $sucursal_id);
        $stmt->bindValue(':user_panel', $user_panel);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    } elseif ($condicion === '') {
        /* $proceso = $data['proceso'];
        $sql = "UPDATE usuarios SET user_panel=:user_panel WHERE user_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_panel', $user_panel);
        $stmt->bindValue(':id', $id);
        $stmt->execute(); */
    }
    $idUser = $id;
    if ($idUser) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idUser);
        exit;
    }
}
