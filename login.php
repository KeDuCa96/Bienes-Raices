<?php


//* Conectamos la BD
require 'includes/config/database.php';
$DB = DB();


$errores = [];

//* Autenticar el usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //* SANITIZAMOS, VALIDAMOS Y PASAMOS DATOS A POST. 
    $email = mysqli_real_escape_string($DB, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)); // Toca siemprar validar tanto en FronT como en BAck.
    $password = mysqli_real_escape_string($DB, $_POST['password']);

    //* Revisamos los errores
    if (!$email) {
        $errores[] = "E-mail incorrecto. Intente nuevamente";
    }
    if (!$password) {
        $errores[] = "Contraseña incorrecta. Intente nuevamente";
    }

    if (empty($errores)) {
        //* Revisamos si el usuario existe:
        $query = "SELECT * FROM usuarios WHERE email = '${email}' "; //* Siempre se debe usar WHERE para validar porque si usamos un like la BD nos trae todos los parecidos.
        $resultadoUsuario = mysqli_query($DB, $query);

        /*  var_dump($resultadoUsuario); */

        // Comprobamos que el usuario exista.
        if ($resultadoUsuario->num_rows) {
            // Revisamos si el password es correcto.
            $usuario = mysqli_fetch_assoc($resultadoUsuario);

            // Verificamos si el password es correcto.
            $autenticacion = password_verify($password, $usuario['password']); // Con esto verificamos si el password es igual al hasheado.

            if ($autenticacion) {
                // El usuario esta autenticado.
                session_start(); //* Con esta función podemos validar usuariosa utenticados. Le podemos agregar cualquier cosa, roles, email. password, etc..

                // LLenar el arreglo de la sesión.
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol']; //Esto lo podemos usar para validar roles y queramos que x usuarios tengan acceso a cierta información.
                $_SESSION['login'] = TRUE;

                /*  echo "<pre>";
                var_dump($_SESSION);
                echo "</pre>"; */

                if ($_SESSION['rol'] === '1') {
                    header('Location: /admin');
                } else {
                    header('Location: /admin');
                }
            } else {
                $errores[] = 'Contraseña incorrecta.';
            }
        } else {
            $errores[] = "El usuario no existe";
        }
    }
}


//* LLamamos el header.
require 'includes/funciones.php'; /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar sesión</h1>

    <form method="POST" class="formulario">
        <fieldset>

            <legend>Iniciar sesión</legend>

            <?php foreach ($errores as $error) : ?>
                <div class="alerta error">
                    <?php echo $error; ?>
                </div>
            <?php endforeach; ?>

            <label for="E-mail"">E-mail</label>
                <input type=" email" name="email" placeholder="Ingrese su E-mail" id="E-mail">

                <label for="password"">Contraseña</label>
                <input type=" password" name="password" placeholder="Ingrese su contraseña" id="password">
                    <button type="button" onclick="mostrarContrasena()">Mostrar Contraseña</button>
        </fieldset>

        <input type="submit" class="boton boton-verde" value="Iniciar sesión">

    </form>
</main>

<?php
mysqli_close($DB);
incluirTemplate('footer');
?>