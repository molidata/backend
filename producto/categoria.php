<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'acceso_producto.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM categorias WHERE cat_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM categorias");
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
    $sql="INSERT INTO categorias(cat_nombre,cat_descripcion) VALUES(:nombre, :descripcion)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':descripcion', $_POST['descripcion']);
    $stmt->execute();
    $idCat=$pdo->lastInsertId();
    if($idCat)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idCat);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Lee el cuerpo de la solicitud PUT como JSON.
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica si se han recibido los campos necesarios.
    if (isset($data['id']) && isset($data['nombre']) && isset($data['descripcion'])) {
        $id = $data['id'];
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];

        $sql = "UPDATE categorias SET cat_nombre=:nombre, cat_descripcion=:descripcion WHERE cat_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':descripcion', $descripcion);
        $stmt->execute();

        header("HTTP/1.1 200 OK");
        echo json_encode(['message' => 'Actualización exitosa']);
        exit;
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['message' => 'Datos faltantes en la solicitud']);
        exit;
    }
}