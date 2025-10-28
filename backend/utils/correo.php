<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // si usas Composer
// Si no usas Composer, cambia estas líneas por los require manuales de PHPMailer (te los doy si los necesitas)

function mail_simple($destinatario, $asunto, $contenido_html) {
  $mail = new PHPMailer(true);
  try {
    // Configuración SMTP del buzón Hostinger
    $mail->isSMTP();
    $mail->Host       = 'srv485.main-hosting.eu';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'admin@edunet.com.mx'; // Tu cuenta actual
    $mail->Password   = 'Tres_123!'; // Es la contraseña de ese buzón en Hostinger
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    // Nombre institucional mostrado en el correo
    $mail->setFrom('admin@edunet.com.mx', 'E.S.T. 96 “Miguel Alemán Valdés”');

    // Destinatario
    $mail->addAddress($destinatario);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body    = $contenido_html;

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log("Error al enviar correo: " . $mail->ErrorInfo);
    return false;
  }
}
?>
