<?php
require '../../includes/funciones.php'; /*usamos el ../ para retroceder  la cantidad de carpetas que necesitemos */

$autenticado = siAutenticado();

if ($autenticado === "1" || $autenticado === "0") {
    header('Location: /');
}



/*  Base de datos */
require '../../includes/config/database.php'; /* Exportamos la conexión */

//Llamamos la función base de datos
$DB = DB();

//? Array para verificar errores en el formulario.
$errores = [];

$nombre = '';
$apellido = '';
$telefono = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*     echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";

    echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>";
    
    exit; */

    // Sanitizamos y validamos.
    $nombre = mysqli_real_escape_string($DB, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($DB, $_POST['apellido']);
    $telefono = mysqli_real_escape_string($DB, $_POST['telefono']);


    // Validamos el formulario
    if (!$nombre) {
        $errores[] = "Por favor ingrese un nombre";
    }
    if (!$apellido) {
        $errores[] = "Por favor ingrese un apellido";
    }
    if (!$telefono) {
        $errores[] = "Por favor ingrese un telefono";
    }

    /*     echo "<pre>";
    var_dump($errores); 
    echo "</pre>";

    exit; */

    // Create properties.
    if (empty($errores)) {

        //-Query para BD
        $query = " INSERT INTO vendedores (nombre, apellido, telefono) VALUES ('$nombre', '$apellido', '$telefono') ";

        /* echo $query; */

        //Le pasamos los datos a la base de datos:
        $resultado = mysqli_query($DB, $query);

        if ($resultado) {
            header('Location: /admin/index-vendedores.php?mensaje=1');
        }
    }
}


$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Crear Vendedor</h1>
    <?php foreach ($errores as $error) : ?>

        <div class="alerta error">
            <?php echo $error; ?>
        </div>

    <?php endforeach; ?>


    <form method="POST" action="/admin/propiedades/crear-vendedor.php" class="formulario" enctype="multipart/form-data">

        <fieldset>

            <legend>Información General</legend>

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre " value="<?php echo $nombre ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" placeholder="Apellido " value="<?php echo $apellido ?>">

            <label for="telefono">Telefono:</label>
            <input type="number" id="telefono" name="telefono" placeholder="Telefono " value="<?php echo $telefono ?>">

        </fieldset>
        <input type="submit" value="Crear Vendedor" class="boton boton-verde">
    </form>
    <a href="/admin" class="boton boton-amarillo">Volver</a>
</main>

<?php incluirTemplate('footer'); ?>