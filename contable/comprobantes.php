<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_contable.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM comprobantes WHERE comprobante_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()==1){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("No hay resultados");
            exit;
        }
    } else {
        $sql=$pdo->prepare("SELECT * FROM comprobantes");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()>0){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("NO hay resultados");
            exit;
        }
        
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO comprobantes(comprobante_tipo, fecha_emision, empresa_emision, cliente_documento_tipo, cliente_documento_numero, cliente_razon_social, cliente_direccion, comprobante_serie, comprobante_numero, envio_sunat, venta_id) 
    VALUES(:tipo, :fecha, :empresa, :clienteDocumento, :clienteNumero, :clienteNombre, :clienteDireccion, :serie, :numero, :envio, :venta)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tipo', $_POST['tipo']);
    $stmt->bindValue(':fecha', $_POST['fecha']);
    $stmt->bindValue(':empresa', $_POST['empresa']);
    $stmt->bindValue(':clienteDocumento', $_POST['clienteDocumento']);
    $stmt->bindValue(':clienteNumero', $_POST['clienteNumero']);
    $stmt->bindValue(':clienteNombre', $_POST['clienteNombre']);
    $stmt->bindValue(':clienteDireccion', $_POST['clienteDireccion']);
    $stmt->bindValue(':serie', $_POST['serie']);
    $stmt->bindValue(':numero', $_POST['numero']);
    $stmt->bindValue(':envio', 'ESPERA');
    $stmt->bindValue(':venta', $_POST['venta']);

    $stmt->execute();
    $idComprobante = $pdo->lastInsertId();
    if ($idComprobante) {
        echo json_encode($idComprobante);

        // Corregir el orden de inicializaci®Æn de $stmt2 y $sql2
        $sql2 = "UPDATE comprobante_numeracion SET numero=:numero WHERE comprobante_tipo=:tipo AND serie=:serie";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindValue(':tipo', $_POST['tipo']);
        $stmt2->bindValue(':serie', $_POST['serie']);
        $stmt2->bindValue(':numero', $_POST['numero'] + 1);
        $stmt2->execute();
        exit;
    }
}



if($_SERVER['REQUEST_METHOD'] == 'PUT')
	{		
		$sql = "UPDATE comprobante_numeracion SET cantidad_venta=:cantidad, precio_venta=:precio, descuento=:descuento WHERE det_id=:id";
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
    $sql = "DELETE FROM comprobante_numeracion WHERE det_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Si no corresponde a ninguna opci√≥n anterior
header("HTTP/1.1 400 Bad Request");