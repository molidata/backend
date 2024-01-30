<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_logistica.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM requerimiento_detalle WHERE detalle_id=:id");
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
        
            header("HTTP/1.1 200 OK");
            echo json_encode("Ingrese un valor");
        
        
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO requerimiento_detalle(requerimiento_id, producto_id, requerimiento_cantidad, unidad_medida) 
    VALUES(:requerimiento, :producto, :cantidad, :medida)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':requerimiento', $_POST['requerimiento']);
    $stmt->bindValue(':producto', $_POST['producto']);
    $stmt->bindValue(':cantidad', $_POST['cantidad']);
    $stmt->bindValue(':medida', $_POST['medida']);
    
    $stmt->execute();
    $idDet=$pdo->lastInsertId();
    if($idDet)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idDet);
        exit;
    }
}



if($_SERVER['REQUEST_METHOD'] == 'PUT')
	{		
		$sql = "UPDATE requerimiento_detalles SET cantidad_venta=:cantidad, precio_venta=:precio, descuento=:descuento WHERE det_id=:id";
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
if($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    $sql = "DELETE FROM venta_detalles WHERE det_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");