<?php
include 'acceso_producto.php';
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM productos WHERE prod_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM productos");
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
    $sql="INSERT INTO productos(prod_codigo, prod_nombre,prod_descripcion, precio_venta, med_id, imagen_nombre, cat_id) 
    VALUES(:codigo, :nombre, :descripcion, :precio, :medida, :imagen, :categoria)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':codigo', $_POST['codigo']);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':descripcion', $_POST['descripcion']);
    $stmt->bindValue(':precio', $_POST['precio']);
    $stmt->bindValue(':medida', $_POST['medida']);
    $stmt->bindValue(':imagen', $_POST['imagen']);
    $stmt->bindValue(':categoria', $_POST['categoria']);
    $stmt->execute();
    $idProd=$pdo->lastInsertId();
    if($idProd)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idProd);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $precio = $data['precio'];
    $medida = $data['medida'];
    $categoria = $data['categoria'];

    $sql = "UPDATE productos SET prod_nombre=:nombre, prod_descripcion=:descripcion, precio_venta=:precio, med_id=:medida, cat_id=:categoria WHERE prod_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':precio', $precio);
    $stmt->bindValue(':medida', $medida);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->execute();
    $idProd = $id;
    if ($idProd) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idProd);
        exit;
    }
}