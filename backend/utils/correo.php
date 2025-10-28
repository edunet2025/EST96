<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';

function enviarCorreoReporte($correoDocente, $nombreDocente, $folio, $urlRecibo) {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'srv485.main-hosting.eu';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@miguelaleman.edunet.com.mx';
    $mail->Password = 'TU_CONTRASEÑA_DEL_CORREO'; // ⚠️ cámbiala
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('no-reply@miguelaleman.edunet.com.mx', 'E.S.T. 96 Miguel Alemán Valdés');
    $mail->addAddress($correoDocente, $nombreDocente);

    $mail->isHTML(true);
    $mail->Subject = "Comprobante de Reporte de Conducta (Folio $folio)";
    $mail->Body = "
      <h3>Reporte de Conducta registrado</h3>
      <p>Docente: <strong>$nombreDocente</strong></p>
      <p>Folio: <strong>$folio</strong></p>
      <p>Comprobante disponible aquí:</p>
      <p><a href='$urlRecibo' target='_blank'>Ver comprobante</a></p>
      <hr>
      <small>Este es un mensaje automático, no respondas a este correo.</small>
    ";
    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log("Error al enviar correo: " . $mail->ErrorInfo);
    return false;
  }
}
?>
