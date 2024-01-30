<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
// email.php

if ($_SERVER['REQUEST_METHOD']=='POST') {
// Recibir datos desde la solicitud POST
$toEmail = $_POST['to_email'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$pdfBase64 = $_POST['attachment'];

// Decodificar el PDF base64
$pdfContent = base64_decode($pdfBase64);

// Definir cabeceras MIME para el correo electr贸nico
$boundary = md5(time());

// Cabecera principal del correo electr贸nico
$headers = "MIME-Version: 1.0\r\n";
$headers .= "From: qara@qara.com.pe\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

// Cuerpo del mensaje
$body = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";  // Cambiado a UTF-8 para manejar caracteres especiales
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= wordwrap($message, 70, "\r\n");  // Añadido wordwrap para manejar líneas largas

// Archivo adjunto (PDF)
$body .= "\r\n--$boundary\r\n";
$body .= "Content-Type: application/pdf; name=\"RECIBO_ELECTRONICO.pdf\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment; filename=\"RECIBO_ELECTRONICO.pdf\"\r\n\r\n";
$body .= chunk_split(base64_encode($pdfContent)) . "\r\n";

// Finalizar el cuerpo del mensaje
$body .= "--$boundary--";

// Enviar el correo electr贸nico
$result = mail($toEmail, $subject, $body, $headers);

// Devolver respuesta al cliente
echo json_encode(['success' => $result]);

}
?>