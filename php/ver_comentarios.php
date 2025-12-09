<?php
session_start();

// Solo puede entrar el administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: ../html/index.html");
    exit();
}

$nombreUsuario = $_SESSION['nombre'];

// Ruta del archivo donde guardaste los comentarios
$rutaArchivo = "../comentarios.html";

$contenido = "";
if (file_exists($rutaArchivo)) {
    $contenido = file_get_contents($rutaArchivo);
} else {
    $contenido = "<p>No hay comentarios todavía.</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios de clientes</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e1e1e;
            font-family: Arial, sans-serif;
            color: #f5f5f5;
        }

        .comentarios-contenedor {
            max-width: 900px;
            margin: 20px auto;
            background: #2a2a2a;
            border-radius: 12px;
            padding: 20px 25px 30px;
            box-shadow: 0 0 18px rgba(0,0,0,0.6);
        }

        .comentarios-encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .comentarios-usuario {
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
        }

        .btn-secundario {
            background: #555;
            color: #fff;
        }

        .btn-secundario:hover {
            background: #777;
        }

        .btn-primario {
            background: #ff6600;
            color: #fff;
        }

        .btn-primario:hover {
            background: #ff8533;
        }

        .comentarios-lista {
            background: #333;
            padding: 15px;
            border-radius: 10px;
            max-height: 500px;
            overflow-y: auto;
        }

        .comentarios-lista p {
            margin-bottom: 8px;
        }

        hr {
            border: none;
            border-top: 1px solid #555;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="comentarios-contenedor">
        <header class="comentarios-encabezado">
            <h2>Comentarios de clientes</h2>
            <div class="comentarios-usuario">
                Usuario: <?php echo $nombreUsuario; ?>
                &nbsp;|&nbsp;
                <a class="btn btn-secundario" href="admin.php">Volver al panel</a>
                <a class="btn btn-secundario" href="cerrarsesion.php">Cerrar sesión</a>
            </div>
        </header>

        <main>
            <div class="comentarios-lista">
                <!-- Acá muestro tal cual el HTML del archivo -->
                <?php echo $contenido; ?>
            </div>

            <br>

            <a class="btn btn-primario" href="admin.php">Volver al panel de administrador</a>
        </main>
    </div>
</body>
</html>
