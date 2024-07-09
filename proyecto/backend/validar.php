<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['usuario']) && isset($_POST['password'])) {
        $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
        $password = $_POST['password'];

        $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado) {
            $row = mysqli_fetch_assoc($resultado);
            if ($row && password_verify($password, $row['password'])) {
                // Login exitoso
                header("Location: home.php");
                exit();
            } else {
                // Credenciales incorrectas
                include("index.php");
                echo '<h1 class="bad">ERROR DE AUTENTIFICACION</h1>';
            }
            mysqli_free_result($resultado);
        } else {
            echo "Error en la consulta: " . mysqli_error($conexion);
        }
    } else {
        include("index.php");
        echo '<h1 class="bad">Por favor ingrese usuario y contraseña</h1>';
    }
} else {
    include("index.php");
    echo '<h1 class="bad">Método de solicitud no permitido</h1>';
}

mysqli_close($conexion);
?>
