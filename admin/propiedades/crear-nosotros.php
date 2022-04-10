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

$tituloNosotros = '';
$descripcionNosotros = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*     echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";

    echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>";
    
    exit; */

    // Sanitizamos y validamos.
    $tituloNosotros = mysqli_real_escape_string($DB, $_POST['tituloNosotros']);
    $creadoNosotros = date('Y/m/d');
    $descripcionNosotros = mysqli_real_escape_string($DB, $_POST['descripcionNosotros']);

    // Validamos los archivos FILES
    $imagenNosotros = $_FILES['imagenNosotros'];

    // Validamos el formulario
    if (!$tituloNosotros) {
        $errores[] = "Por favor ingrese un titulo a Nosotros";
    }
    if (strlen(!$descripcionNosotros) > 50) {
        $errores[] = "Por favor ingrese la descripción de nosotros. Mínimo 50 caracteres.";
    }
    if (!$imagenNosotros['name'] || $imagenNosotros['error']) {
        $errores[] = "Por favor agregue una imagen";
    } else if (!($imagenNosotros["type"] == "image/jpeg" || $imagenNosotros["type"] == "image/png")) { //* validamos el tipo de archivo que se va a subir.
        $errores[] = "El archivo debe tener formato de imagen *.jpg/png";
    }

    //Validamos archivos por tamaño.
    $medida = 1000 * 1000;

    if ($imagenNosotros['size'] > $medida) {
        $errores[] = 'La imagen es muy pesada';
    }
    /*     echo "<pre>";
    var_dump($errores); 
    echo "</pre>";

    exit; */

    // Create properties.
    if (empty($errores)) {

        //SUBIDA DE ARCHIVOS

        //Create folder:
        $carpetaImagenes = '../../imagenes/';


        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        //Rename img
        $imgInfo = new SplFileInfo($imagenNosotros['name']);
        $extension = "." . $imgInfo->getExtension();
        $nombreImagen = md5(uniqid(rand(), true)) . $extension;

        //Upload img
        move_uploaded_file($imagenNosotros['tmp_name'], $carpetaImagenes . $nombreImagen);


        //-Query para BD
        $queryNosotros = " INSERT INTO nosotros (imagenNosotros, tituloNosotros,  descripcionNosotros, creadoNosotros) VALUES ('$nombreImagen', '$tituloNosotros', '$descripcionNosotros', '$creadoNosotros') ";

        /* echo $query; */

        //Le pasamos los datos a la base de datos:
        $resultadoNosotros = mysqli_query($DB, $queryNosotros);

        if ($resultadoNosotros) {
            header('Location: /admin/index-nosotros.php?mensaje=1');
        }
    }
}


$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Crear Nosotros</h1>
    <?php foreach ($errores as $error) : ?>

        <div class="alerta error">
            <?php echo $error; ?>
        </div>

    <?php endforeach; ?>

    <form method="POST" action="/admin/propiedades/crear-nosotros.php" class="formulario" enctype="multipart/form-data">

        <fieldset>

            <legend>Información General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="tituloNosotros" placeholder="Titulo nosotros" value="<?php echo $tituloNosotros ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagenNosotros" accept="image/jpeg, image/png">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcionNosotros" maxlength="500"><?php echo $descripcionNosotros ?></textarea>
            <p id="contador">0/500</p>

        </fieldset>
        <input type="submit" value="Crear Blog" class="boton boton-verde">
    </form>
    <a href="/admin" class="boton boton-amarillo">Volver</a>
</main>

<?php incluirTemplate('footer'); ?>