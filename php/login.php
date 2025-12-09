<?php
// inicio sesión
session_start();

// chequeo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // conexion base de datos
    $conexion = mysqli_connect("localhost", "root", "", "alquiler_disfraces");

    if (!$conexion) {
        die("Error en la conexión a la base de datos.");
    }
    
    //para mejorar la seguridad
    $emailFormSeguro = mysqli_real_escape_string($conexion, $_POST['email']);
    $passwordFormSeguro = mysqli_real_escape_string($conexion, $_POST['password']);
    
    // valido:
    // busco coincidencias entre usuarios y contraseñas  
$consulta = "SELECT * FROM usuarios 
             WHERE email='$emailFormSeguro' 
             AND `contrasena`='$passwordFormSeguro'";

    $resultado = mysqli_query($conexion, $consulta);
    
    // validacion
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        
        $usuario = mysqli_fetch_assoc($resultado);

        // Guardo datos
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre']= $usuario['nombre'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['tipo']= $usuario['tipo']; 
        $_SESSION['hora_inicio'] = time();
        $_SESSION['saludo'] = "Bienvenido/a, " . $usuario['nombre'];

        // Redirección según el tipo de usuario
        if ($usuario['tipo'] == 'administrador') {
            header("Location: admin.php");
        } else {
            header("Location: midisfraz.php"); 
        }

        mysqli_close($conexion);
        exit();

    } else {
        
        // Login fallido
        $_SESSION['error_login'] = "Email o contraseña incorrectos.";
        
        // Redirigimos de vuelta para que se muestre el error
        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* ... Estilos CSS ... */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #1e1e1e;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #f0f0f0;
        }

        .container {
            background: #2a2a2a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 350px;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        h3 {
            margin-bottom: 25px;
            font-weight: normal;
            color: #cccccc;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
            background: #3a3a3a;
            color: #ffffff;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #4a4a4a;
            border: none;
            border-radius: 8px;
            color: #ffffff;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background: #6a6a6a;
        }
    </style>
    <title>Alquiler de disfraces</title>
</head>
<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Bienvenido al alquiler de disfraces</h1>
            <h3>Inicie sesión para continuar con el proceso</h3>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Ingresar">
            
            <?php
            //error de login 
            if (isset($_SESSION['error_login'])) {
                echo '<p style="color: #ff5555; margin-top: 15px;">' . $_SESSION['error_login'] . '</p>';
                unset($_SESSION['error_login']); 
            }
            ?>
        </form>
    </div>
</body>
</html>