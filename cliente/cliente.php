<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_cliente.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM clientes WHERE cli_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM clientes");
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
    $sql="INSERT INTO clientes(tipo_documento, numero_documento,cli_nombre, cli_direccion, cli_email, cli_telefono) 
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

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD']=='PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $documento = $data['documento'];
    $numero = $data['numero'];
    $nombre = $data['nombre'];
    $direccion = $data['direccion'];
    $email = $data['email'];
    $telefono = $data['telefono'];


    $sql= "UPDATE clientes SET tipo_documento=:documento, numero_documento=:numero, cli_nombre=:nombre, cli_direccion=:direccion, cli_email=:email, cli_telefono=:telefono WHERE cli_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':documento', $documento);
    $stmt->bindValue(':numero', $numero);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':direccion', $direccion);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':telefono', $telefono);
    $stmt->execute();

    header("HTTP/1.1 200 OK");
    echo json_encode(['message' => 'Actualización exitosa: ']. $id);
    exit;
}