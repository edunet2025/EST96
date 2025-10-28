<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function mail_simple($destinatario, $asunto, $contenido_html) {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host       = 'srv485.main-hosting.eu';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'admin@edunet.com.mx';      // tu buzón activo
    $mail->Password   = 'Tres_123!';  // contraseña real del buzón
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('admin@edunet.com.mx', 'E.S.T. 96 “Miguel Alemán Valdés”');
    $mail->addAddress($destinatario);

    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body    = $contenido_html;

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log("Error correo: " . $mail->ErrorInfo);
    return false;
  }
}
?>
