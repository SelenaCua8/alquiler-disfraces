<?php
session_start();

// Si no hay sesión, vuelvo al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../html/index.html");
    exit();
}

// Guardo datos
$idUsuario   = $_SESSION['id_usuario'];
$nombre      = $_SESSION['nombre'];

if (isset($_SESSION['hora_inicio'])) {
    $horaInicio = $_SESSION['hora_inicio'];
} else {
    $horaInicio = time();
}

$fechaInicio = date('Y-m-d H:i:s', $horaInicio);
$ahora       = time();
$minutos     = round(($ahora - $horaInicio) / 60);

if ($minutos < 1) {
    $minutos = 1;
}

// Escribo en accesos.txt
$linea = $fechaInicio . " - Usuario " . $nombre . " (ID " . $idUsuario . ") - " . $minutos . " minutos" . PHP_EOL;
file_put_contents("../accesos.txt", $linea, FILE_APPEND);

// Destruyo sesión
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de sesión</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e1e1e;
            font-family: Arial, sans-serif;
            color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .logout-box {
            background: #2a2a2a;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 18px rgba(0,0,0,0.6);
            text-align: center;
        }

        .logout-box h2 {
            margin-bottom: 15px;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            border-radius: 8px;
            background: #ff6600;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-volver:hover {
            background: #ff8533;
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <h2>Hasta luego, <?php echo $nombre; ?>. ¡Sesión cerrada correctamente!</h2>
        <a class="btn-volver" href="login.php">Volver al inicio</a>
    </div>
</body>
</html>
