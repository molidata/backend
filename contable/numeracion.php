<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include 'acceso_contable.php';
$pdo=new Conexion();
//SOLICITAR DATOS
if ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['id'])) {
        $sql=$pdo->prepare("SELECT * FROM comprobante_numeracion WHERE numeracion_id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()==1){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
    } else {
        $sql=$pdo->prepare("SELECT * FROM comprobante_numeracion");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        if($sql->rowCount ()>0){
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else{
            header("HTTP/1.1 200 OK");
            echo json_encode("no hay resultados");
            exit;
        }
        
    }
    
}
//INSERTAR DATOS
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $sql="INSERT INTO comprobante_numeracion(sede_id, comprobante_tipo, serie, numero) 
    VALUES(:sede, :tipo, :serie, :numero)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(':sede', $_POST['sede']);
    $stmt->bindValue(':tipo', $_POST['tipo']);
    $stmt->bindValue(':serie', $_POST['serie']);
    $stmt->bindValue(':numero', $_POST['numero']);
    
    $stmt->execute();
    $idDet=$pdo->lastInsertId();
    if($idDet)
    {
        header("HTTP/1.1 200 OK");
        echo json_encode($idDet);
        exit;
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $condicion = $data['condicion'];

    if ($condicion === 'ASIGNACION-SERIE') {
        $sede = $data['sede'];
        $tipo = $data['tipo'];
        $serie = $data['serie'];
        $numero = $data['numero'];
        $sql = "UPDATE comprobante_numeracion SET sede_id=:sede, comprobante_tipo=:tipo, serie=:serie, numero=:numero WHERE numeracion_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':sede', $sede);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':serie', $serie);
        $stmt->bindValue(':numero', $numero);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $idComproNumer = $id;
    } elseif ($condicion === 'UPDATED-SERIE-EMISION') {
        $sede = $data['sede'];
        $tipo = $data['tipo'];
        $serie = $data['serie'];
        $numero = $data['numero'];

        $sql = "UPDATE comprobante_numeracion SET numero=:numero WHERE sede_id=:sede AND comprobante_tipo=:tipo AND serie=:serie";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':sede', $sede);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':serie', $serie);
        $stmt->bindValue(':numero', $numero);
        $stmt->execute();
        $idComproNumer = $numero;
    }

    if ($idComproNumer) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idComproNumer);
        exit;
    }
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

//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");