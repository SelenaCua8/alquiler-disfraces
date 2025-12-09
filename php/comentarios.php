<?php
session_start();

// Solo puede entrar un cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: ../html/index.html");
    exit();
}

// Verifico que venga por POST y que exista comentario
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['comentario'])) {
    header("Location: midisfraz.php");
    exit();
}

$nombreCliente = $_SESSION['nombre'];
$comentario    = $_POST['comentario'];
$fechaHora     = date('Y-m-d H:i:s');

// Archivo donde se guardan los comentarios
$rutaArchivo = "../comentarios.html";

// Armamos el bloque HTML
$bloque = "<p><span style='color:red; font-weight:bold;'>" .
          $nombreCliente .
          "</span> - " . $fechaHora . "<br>" .
          $comentario .
          "</p>\n<hr>\n";

// Guardar en el archivo
file_put_contents($rutaArchivo, $bloque, FILE_APPEND);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentario enviado</title>

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

        .comentario-box {
            background: #2a2a2a;
            padding: 30px 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 0 18px rgba(0,0,0,0.6);
        }

        h2 {
            margin-bottom: 15px;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 16px;
            border-radius: 8px;
            background: #ff6600;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-volver:hover {
            background: #ff8533;
        }
    </style>
</head>
<body>
    <div class="comentario-box">
        <h2>Mensaje enviado</h2>
        <p>Gracias por su comentario, <?php echo $nombreCliente; ?>.</p>
        <a class="btn-volver" href="midisfraz.php">Volver a mis disfraces</a>
    </div>
</body>
</html>
