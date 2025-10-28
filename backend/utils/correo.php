<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rutas relativas a los archivos de PHPMailer
require_once(__DIR__ . '/PHPMailer/src/Exception.php');
require_once(__DIR__ . '/PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/PHPMailer/src/SMTP.php');

/**
 * Enviar correo de comprobante de reporte disciplinario
 * 
 * @param string $destinatario Correo del docente
 * @param string $nombre_docente Nombre completo del docente
 * @param int $folio Folio del reporte
 * @param string $url_comprobante URL al comprobante HTML
 * @return bool true si se envió correctamente
 */
function enviarCorreoReporte($destinatario, $nombre_docente, $folio, $url_comprobante) {
  $mail = new PHPMailer(true);

  try {
    // =========================================
    // 🔧 CONFIGURACIÓN SMTP (Hostinger)
    // =========================================
    $mail->isSMTP();
    $mail->Host = 'srv485.main-hosting.eu';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@edunet.com.mx';  // ⚠️ tu buzón Hostinger
    $mail->Password = 'Tres_123!';   // ⚠️ reemplázala por la real
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // =========================================
    // 📩 REMITENTE Y DESTINATARIO
    // =========================================
    $mail->setFrom('admin@edunet.com.mx', 'Secundaria Téc. 96 Miguel Alemán Valdés');
    $mail->addAddress($destinatario ?: 'admin@edunet.com.mx', $nombre_docente);

    // =========================================
    // 💬 CONTENIDO DEL MENSAJE
    // =========================================
    $mail->isHTML(true);
    $mail->Subject = "Comprobante de Reporte — Folio $folio";

    $mail->Body = "
      <div style='font-family:system-ui,sans-serif; color:#333;'>
        <h2 style='color:#b91c1c;'>Comprobante de Reporte Disciplinario</h2>
        <p>Estimado/a <strong>$nombre_docente</strong>,</p>
        <p>Se ha registrado un nuevo reporte con el folio <strong>$folio</strong>.</p>
        <p>Puedes consultar el comprobante en el siguiente enlace:</p>
        <p>
          <a href='$url_comprobante' target='_blank' style='color:#0f2a6d;'>
            Ver comprobante del reporte
          </a>
        </p>
        <hr>
        <small>Este mensaje fue enviado automáticamente desde el sistema de reportes de la
        <strong>Escuela Secundaria Técnica No. 96 “Miguel Alemán Valdés”</strong>.<br>
        Por favor, no respondas a este correo.</small>
      </div>
    ";

    // =========================================
    // 🚀 ENVÍO
    // =========================================
    $mail->send();
    return true;

  } catch (Exception $e) {
    error_log("Error al enviar correo (PHPMailer): " . $mail->ErrorInfo);
    return false;
  }
}
?>
