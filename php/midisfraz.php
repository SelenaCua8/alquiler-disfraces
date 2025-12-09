<?php
session_start();

// Página protegida: tiene que haber sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../html/index.html");
    exit();
}

$nombreUsuario = $_SESSION['nombre'];
$tipoUsuario   = $_SESSION['tipo'];   // administrador / empleado / cliente

// saludo
if (isset($_SESSION['saludo'])) {
    $saludo = $_SESSION['saludo'];
} else {
    $saludo = "";
}

// Si es admin, lo mando a admin.php
if ($tipoUsuario === 'administrador') {
    header("Location: admin.php");
    exit();
}

// Conexión a la base
$conexion = mysqli_connect("localhost", "root", "", "alquiler_disfraces");
if (!$conexion) {
    exit("Error de conexión");
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
    <title>Mis disfraces</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e1e1e;
            font-family: Arial, sans-serif;
            color: #f5f5f5;
        }

        .cliente-contenedor {
            max-width: 1000px;
            margin: 20px auto;
            background: #2a2a2a;
            border-radius: 12px;
            padding: 20px 25px 30px;
            box-shadow: 0 0 18px rgba(0,0,0,0.6);
        }

        .cliente-encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .cliente-usuario {
            display: flex;
            gap: 10px;
            align-items: center;
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

        .cliente-saludo {
            background: #345;
            padding: 8px 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .cliente-seccion {
            margin-bottom: 20px;
        }

        .cliente-seccion h3 {
            margin-bottom: 8px;
        }

        .busqueda-form {
            background: #333;
            padding: 12px;
            border-radius: 10px;
            display: flex;
            gap: 10px;
        }

        .busqueda-form input[type="text"] {
            flex: 1;
            padding: 8px;
            border-radius: 6px;
            border: none;
            background: #444;
            color: #f5f5f5;
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

        .comentario-form textarea {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: none;
            background: #333;
            color: #f5f5f5;
            resize: vertical;
        }

        .comentario-form input[type="submit"] {
            margin-top: 8px;
        }

        em {
            color: #ccc;
        }
    </style>
</head>
<body>

    <div class="cliente-contenedor">

        <header class="cliente-encabezado">
            <h2>Disfraces disponibles para alquiler</h2>
            <div class="cliente-usuario">
                <span>Usuario: <?php echo $nombreUsuario; ?></span>
                <a class="btn btn-secundario" href="cerrarsesion.php">Cerrar sesión</a>
            </div>
        </header>

        <main>
            <?php 
            if ($saludo) {
                echo "<p class='cliente-saludo'><strong>$saludo</strong></p>";
                unset($_SESSION['saludo']);
            }
            ?>

            <section class="cliente-seccion">
                <h3>Buscar disfraces</h3>
                <form method="get" class="busqueda-form">
                    <input type="text" name="busqueda" placeholder="Ingrese nombre o tipo" value="<?php echo $busqueda; ?>">
                    <input class="btn btn-primario" type="submit" value="Buscar">
                </form>
            </section>

            <section class="cliente-seccion">
                <h3>Listado de disfraces</h3>
                <div class="tabla-contenedor">
                    <table>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Imagen</th>
                        </tr>

                        <?php while ($fila = mysqli_fetch_assoc($resultDisfraces)) { ?>
                            <tr>
                                <td><?php echo $fila['nombre_disfraz']; ?></td>
                                <td><?php echo $fila['tipo']; ?></td>
                                <td><?php echo $fila['imagen']; ?></td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            </section>

            <?php if ($tipoUsuario === 'cliente') { ?>
                <section class="cliente-seccion">
                    <h3>Enviar comentario al administrador</h3>
                    <form method="post" action="comentarios.php" class="comentario-form">
                        <textarea name="comentario" rows="5" placeholder="Escriba su comentario..." required></textarea><br>
                        <input class="btn btn-primario" type="submit" value="Enviar comentario">
                    </form>
                </section>
            <?php } else { ?>
                <section class="cliente-seccion">
                    <p><em>Como empleado solo puede ver y buscar disfraces.</em></p>
                </section>
            <?php } ?>

        </main>

    </div>

</body>
</html>

<?php mysqli_close($conexion); ?>
