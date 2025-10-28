<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/PHPMailer/src/Exception.php');
require_once(__DIR__ . '/PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/PHPMailer/src/SMTP.php');

function enviarCorreoReporte($destinatario, $nombre_docente, $folio, $url_comprobante) {
  $mail = new PHPMailer(true);
  try {
    // Configuración del servidor SMTP (Hostinger)
    $mail->isSMTP();
    $mail->Host = 'srv485.main-hosting.eu';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@edunet.com.mx'; // tu correo Hostinger
    $mail->Password = 'AQUÍ_TU_CONTRASEÑA_DEL_CORREO'; // ⚠️ cámbiala
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente
    $mail->setFrom('admin@edunet.com.mx', 'Secundaria Téc. 96 Miguel Alemán Valdés');
    $mail->addAddress($destinatario, $nombre_docente);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Comprobante de reporte disciplinario — Folio $folio";
    $mail->Body = "
      <h3>Comprobante de Reporte</h3>
      <p>Estimado/a <strong>$nombre_docente</strong>,</p>
      <p>Se ha generado el reporte disciplinario con folio <strong>$folio</strong>.</p>
      <p>Puedes consultarlo en el siguiente enlace:</p>
      <p><a href='$url_comprobante' target='_blank'>$url_comprobante</a></p>
      <hr>
      <small>Mensaje automático — No responder.</small>
    ";

    $mail->send();
    return true;

  } catch (Exception $e) {
    error_log("Error PHPMailer: {$mail->ErrorInfo}");
    return false;
  }
}

