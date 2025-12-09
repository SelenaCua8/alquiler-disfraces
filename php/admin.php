<?php
session_start();

// Página protegida solo para administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: ../html/index.html");
    exit();
}

$nombreUsuario = $_SESSION['nombre'];

if (isset($_SESSION['saludo'])) {
    $saludo = $_SESSION['saludo'];
} else {
    $saludo = "";
}

// Conectando a la base
$conexion = mysqli_connect("localhost", "root", "", "alquiler_disfraces");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Manejo de acciones: ingresar, modificar, eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
    } else {
        $accion = "";
    }

    if ($accion === 'ingresar') {
        $nombre_disfraz = $_POST['nombre_disfraz'];
        $tipo           = $_POST['tipo'];
        $imagen         = $_POST['imagen'];

        $sqlInsert = "INSERT INTO disfraces (nombre_disfraz, tipo, imagen)
                      VALUES ('$nombre_disfraz', '$tipo', '$imagen')";
        mysqli_query($conexion, $sqlInsert);

    } elseif ($accion === 'modificar') {
        $id_disfraces   = (int) $_POST['id_disfraces'];
        $nombre_disfraz = $_POST['nombre_disfraz'];
        $tipo           = $_POST['tipo'];

       
        $sqlUpdate = "UPDATE disfraces
                      SET nombre_disfraz='$nombre_disfraz',
                          tipo='$tipo'
                      WHERE id_disfraces=$id_disfraces";
        mysqli_query($conexion, $sqlUpdate);

    } elseif ($accion === 'eliminar') {
        $id_disfraces = (int) $_POST['id_disfraces'];
        $sqlDelete = "DELETE FROM disfraces WHERE id_disfraces=$id_disfraces";
        mysqli_query($conexion, $sqlDelete);
    }
}

// Búsqueda de disfraces
if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
} else {
    $busqueda = "";
}

$sqlDisfraces = "SELECT * FROM disfraces";
if ($busqueda !== '') {
    $sqlDisfraces .= " WHERE nombre_disfraz LIKE '%$busqueda%' OR tipo LIKE '%$busqueda%'";
}
$resultDisfraces = mysqli_query($conexion, $sqlDisfraces);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel administrador</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e1e1e;
            font-family: Arial, sans-serif;
            color: #f5f5f5;
        }

        .admin-contenedor {
            max-width: 1000px;
            margin: 20px auto;
            background: #2a2a2a;
            border-radius: 12px;
            padding: 20px 25px 30px;
            box-shadow: 0 0 18px rgba(0,0,0,0.6);
        }

        .admin-encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .admin-usuario {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 14px;
        }

        .admin-saludo {
            background: #345;
            padding: 8px 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .admin-seccion {
            margin-bottom: 20px;
        }

        .admin-seccion h3 {
            margin-bottom: 8px;
        }

        .admin-form,
        .busqueda-form {
            background: #333;
            padding: 12px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .admin-form label {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-size: 14px;
            gap: 4px;
        }

        .admin-form input[type="text"],
        .busqueda-form input[type="text"] {
            width: 100%;
            padding: 8px;
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
        }

        .btn-primario:hover {
            background: #ff8533;
        }

        .btn-secundario {
            background: #555;
            color: #fff;
        }

        .btn-secundario:hover {
            background: #777;
        }

        .btn-peligro {
            background: #c0392b;
            color: #fff;
        }

        .btn-peligro:hover {
            background: #e74c3c;
        }

        .btn-chico {
            padding: 5px 10px;
            font-size: 12px;
        }

        .tabla-contenedor {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 10px;
            border: 1px solid #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #333;
        }

        table th,
        table td {
            padding: 8px;
            border-bottom: 1px solid #444;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background: #444;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background: #383838;
        }

        table input[type="text"] {
            width: 100%;
            padding: 5px;
            border-radius: 4px;
            border: none;
            background: #444;
            color: #f5f5f5;
        }

        .celda-acciones {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>

    <div class="admin-contenedor">

        <header class="admin-encabezado">
            <h2>Panel de administrador</h2>
            <div class="admin-usuario">
                <span>Usuario: <?php echo $nombreUsuario; ?></span>
                <a class="btn btn-secundario" href="cerrarsesion.php">Cerrar sesión</a>
            </div>
        </header>

        <main>
            <?php 
            if ($saludo) {
                echo "<p class='admin-saludo'><strong>$saludo</strong></p>";
                unset($_SESSION['saludo']);
            }
            ?>

            <section class="admin-seccion">
                <h3>Ingresar nuevo disfraz</h3>
                <form method="post" class="admin-form">
                    <input type="hidden" name="accion" value="ingresar">

                    <label>
                        Nombre del disfraz
                        <input type="text" name="nombre_disfraz" placeholder="Nombre del disfraz" required>
                    </label>

                    <label>
                        Tipo
                        <input type="text" name="tipo" placeholder="Tipo (terror, princesa, etc)" required>
                    </label>

                    <label>
                        Imagen
                        <input type="text" name="imagen" placeholder="Nombre de archivo de imagen" required>
                    </label>

                    <input class="btn btn-primario" type="submit" value="Guardar">
                </form>
            </section>

            <section class="admin-seccion">
                <h3>Buscar disfraces</h3>
                <form method="get" class="busqueda-form">
                    <input type="text" name="busqueda" placeholder="Ingrese nombre o tipo" value="<?php echo $busqueda; ?>">
                    <input class="btn btn-primario" type="submit" value="Buscar">
                </form>
            </section>

            <section class="admin-seccion">
                <h3>Listado de disfraces</h3>
                <div class="tabla-contenedor">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                        <?php while ($fila = mysqli_fetch_assoc($resultDisfraces)) { ?>
                            <tr>
                                <form method="post">
                                    <td><?php echo $fila['id_disfraces']; ?></td>
                                    <td>
                                        <input type="text" name="nombre_disfraz" value="<?php echo $fila['nombre_disfraz']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="tipo" value="<?php echo $fila['tipo']; ?>">
                                    </td>
                                    <td class="celda-acciones">
                                        <input type="hidden" name="id_disfraces" value="<?php echo $fila['id_disfraces']; ?>">
                                        <button class="btn btn-chico btn-primario" type="submit" name="accion" value="modificar">Modificar</button>
                                        <button class="btn btn-chico btn-peligro" type="submit" name="accion" value="eliminar" onclick="return confirm('¿Eliminar este disfraz?');">Eliminar</button>
                                    </td>
                                </form>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </section>

            <section class="admin-seccion">
                <h3>Comentarios de clientes</h3>
                <form action="ver_comentarios.php" method="get">
                    <button class="btn btn-secundario" type="submit">Ver comentarios</button>
                </form>
            </section>
        </main>

    </div>

</body>
</html>
<?php
mysqli_close($conexion);
?>
