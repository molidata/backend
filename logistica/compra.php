<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM compras WHERE compra_id=:id");
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
        $sql=$pdo->prepare("SELECT * FROM compras");
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
    $sql="INSERT INTO compras(proveedor_id, compra_fecha, compra_moneda, empresa_id, tipo_pago, usuario_id, proceso, comprobante_tipo, comprobante_serie, comprobante_numero,destino_id) 
    VALUES(:proveedor, :fecha, :moneda, :empresa, :pago, :usuario, :proceso, :comprobante, :seriecomprobante, :numerocomprobante, :destino)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':proveedor', $_POST['proveedor']);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':moneda', $_POST['moneda']);
    $stmt->bindValue(':empresa', $_POST['empresa']);
    $stmt->bindValue(':pago', $_POST['pago']);
    $stmt->bindValue(':usuario', $_POST['usuario']);
    $stmt->bindValue(':proceso', $_POST['proceso']);
    $stmt->bindValue(':comprobante', $_POST['comprobante']);
    $stmt->bindValue(':seriecomprobante', $_POST['seriecomprobante']);
    $stmt->bindValue(':numerocomprobante', $_POST['numerocomprobante']);
    $stmt->bindValue(':destino', $_POST['destino']);
    
    $stmt->execute();
    $idcompra=$pdo->lastInsertId();
    if($idcompra)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idcompra);
        exit;
    }
}

//ACTUALIZAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $proceso = $data['proceso'];
    $comprobante = $data['comprobante'];
    $seriecomprobante = $data['seriecomprobante'];
    $numerocomprobante = $data['numerocomprobante'];
    $destino = $data['destino'];

    $sql = "UPDATE compras SET proceso=:proceso, comprobante_tipo=:comprobante, comprobante_serie=:seriecomprobante, comprobante_numero=:numerocomprobante, destino_id=:destino WHERE compra_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':proceso', $proceso);
    $stmt->bindValue(':comprobante', $comprobante);
    $stmt->bindValue(':seriecomprobante', $seriecomprobante);
    $stmt->bindValue(':numerocomprobante', $numerocomprobante);
    $stmt->bindValue(':destino', $destino);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $idcompra = $id;
    if ($idcompra) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idcompra);
        exit;
    }
}


//Si no corresponde a ninguna opción anterior
//header("HTTP/1.1 400 Bad Request");