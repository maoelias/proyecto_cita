<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conexion = mysqli_connect("localhost", "root", "", "logueo") or die("error de conexion");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['usuario']) && isset($_POST['password']) && isset($_POST['email'])) {
        $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($conexion, $_POST['email']);

        $query = "INSERT INTO usuarios (usuario, password, email) VALUES ('$usuario', '$password', '$email')";
        if (mysqli_query($conexion, $query)) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['SMTP_USER'];
                $mail->Password = $_ENV['SMTP_PASS'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = $_ENV['SMTP_PORT'];

                $mail->setFrom($_ENV['SMTP_USER'], 'Tu Nombre');
                $mail->addAddress($email, $usuario);

                $mail->isHTML(true);
                $mail->Subject = 'Confirmación de Registro';
                $mail->Body    = '<p>Gracias por registrarte, ' . $usuario . '!</p><p>Tu registro fue exitoso.</p>';

                $mail->send();
                echo "Registro exitoso. Revisa tu correo electrónico para la confirmación.";
            } catch (Exception $e) {
                echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            }

            echo '<p>Registro exitoso. Ahora puede iniciar sesión.</p>';
            echo '<a href="index.php">Iniciar sesión</a>';
            exit();
        } else {
            echo "Error en el registro: " . mysqli_error($conexion);
        }
    } else {
        echo "Por favor complete todos los campos.";
    }
} else {
    echo "Método de solicitud no permitido";
}

mysqli_close($conexion);
?>
