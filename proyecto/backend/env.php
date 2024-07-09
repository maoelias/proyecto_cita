<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Cargar variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $mail = new PHPMailer(true);

    // Configuración de PHPMailer para enviar correo electrónico
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST']; // Configurar el host SMTP
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER']; // Configurar el usuario SMTP
    $mail->Password = $_ENV['SMTP_PASS']; // Configurar la contraseña SMTP
    
    // Configurar el método de encriptación según lo requiera tu proveedor de servicios de correo
    // Ejemplo: Gmail usa 'tls' (STARTTLS) en el puerto 587, y 'ssl' en el puerto 465
    if ($_ENV['SMTP_PORT'] == 587) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Para STARTTLS en el puerto 587
    } elseif ($_ENV['SMTP_PORT'] == 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Para SSL/TLS en el puerto 465
    }
    
    $mail->Port = $_ENV['SMTP_PORT']; // Configurar el puerto SMTP

    $mail->setFrom($_ENV['SMTP_USER'], 'Tu Nombre');
    $mail->addAddress('correo_destino@example.com', 'Destinatario');

    $mail->isHTML(true);
    $mail->Subject = 'Asunto del Correo';
    $mail->Body    = '<p>Este es el cuerpo del correo.</p>';

    $mail->send();
    echo 'El correo electrónico se envió correctamente.';
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}
?>
