<?php
session_start();

// 1. Página protegida solo para administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: ../html/index.html");
    exit();
}

$nombreUsuario = $_SESSION['nombre'];

// 2. Conectando a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "alquiler_disfraces");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// 3. Manejo de la modificación (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verificamos el ID
    if (!isset($_POST['id_disfraces']) || (int)$_POST['id_disfraces'] === 0) {
        $_SESSION['saludo'] = "Error: ID de disfraz no especificado para modificar.";
        header("Location: admin.php");
        exit();
    }

    $id_disfraces   = (int) $_POST['id_disfraces'];
    
    // Saneamiento de datos antes de usarlos en la consulta
    $nombre_disfraz = mysqli_real_escape_string($conexion, $_POST['nombre_disfraz']);
    $tipo           = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $imagen         = mysqli_real_escape_string($conexion, $_POST['imagen']); // Se permite modificar imagen también

    // Consulta UPDATE
    $sqlUpdate = "UPDATE disfraces
                  SET nombre_disfraz='$nombre_disfraz',
                      tipo='$tipo',
                      imagen='$imagen'
                  WHERE id_disfraces=$id_disfraces";
                  
    if (mysqli_query($conexion, $sqlUpdate)) {
        $_SESSION['saludo'] = "Disfraz '$nombre_disfraz' modificado correctamente.";
    } else {
        $_SESSION['saludo'] = "Error al modificar el disfraz.";
    }

    // 4. Redireccionar al panel
    header("Location: admin.php");
    exit();
}

// 5. Carga de datos del disfraz (GET)
$id_disfraces = (isset($_GET['id'])) ? (int) $_GET['id'] : 0;

if ($id_disfraces === 0) {
    $_SESSION['saludo'] = "Error: Disfraz no especificado.";
    header("Location: admin.php");
    exit();
}

// Consulta para obtener el disfraz
$sqlDisfraz = "SELECT * FROM disfraces WHERE id_disfraces=$id_disfraces";
$resultDisfraz = mysqli_query($conexion, $sqlDisfraz);
$disfraz = mysqli_fetch_assoc($resultDisfraz);

// Si no se encuentra el disfraz
if (!$disfraz) {
    $_SESSION['saludo'] = "Error: Disfraz no encontrado con ID $id_disfraces.";
    header("Location: admin.php");
    exit();
}

// La conexión se cierra al final del HTML.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Disfraz</title>
    
    <style>
        /* Estilos base (tomados de admin.php) */
        body {
            margin: 0;
            padding: 0;
            background: #1e1e1e;
            font-family: Arial, sans-serif;
            color: #f5f5f5;
        }

        .contenedor {
            max-width: 600px;
            margin: 50px auto;
            background: #2a2a2a;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 18px rgba(0,0,0,0.6);
        }
        
        h2 {
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .form-edicion {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-edicion label {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-size: 14px;
            gap: 4px;
        }

        .form-edicion input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            background: #444;
            color: #f5f5f5;
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

        .btn-primario {
            background: #ff6600;
            color: #fff;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primario:hover {
            background: #ff8533;
        }
        
        .btn-secundario {
            background: #555;
            color: #fff;
            margin-top: 20px;
        }
        
        .btn-secundario:hover {
            background: #777;
        }
    </style>
</head>
<body>

    <div class="contenedor">
        <h2>Editar Disfraz ID: <?php echo $disfraz['id_disfraces']; ?></h2>

        <form method="post" class="form-edicion">
            <input type="hidden" name="id_disfraces" value="<?php echo $disfraz['id_disfraces']; ?>">

            <label>
                Nombre del disfraz
                <input type="text" name="nombre_disfraz" value="<?php echo $disfraz['nombre_disfraz']; ?>" required>
            </label>

            <label>
                Tipo
                <input type="text" name="tipo" value="<?php echo $disfraz['tipo']; ?>" required>
            </label>

            <label>
                Imagen
                <input type="text" name="imagen" value="<?php echo $disfraz['imagen']; ?>" required>
            </label>

            <input class="btn btn-primario" type="submit" value="Guardar Cambios">
        </form>
        
        <a class="btn btn-secundario" href="admin.php">Volver al Panel de Administrador</a>

    </div>

</body>
</html>
<?php
mysqli_close($conexion);
?>