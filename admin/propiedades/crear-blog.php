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

$tituloBlog = '';
$vendedorid = '';
$descripcionBlog = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*     echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";

    echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>";
    
    exit; */

    // Sanitizamos y validamos.
    $tituloBlog = mysqli_real_escape_string($DB, $_POST['tituloBlog']);
    $creadoBlog = date('Y/m/d');
    $vendedorid = mysqli_real_escape_string($DB, $_POST['vendedorid']);
    $descripcionBlog = mysqli_real_escape_string($DB, $_POST['descripcionBlog']);

    // Validamos los archivos FILES
    $imagenBlog = $_FILES['imagenBlog'];

    // Validamos el formulario
    if (!$tituloBlog) {
        $errores[] = "Por favor ingrese un titulo al Blog";
    }
    if (strlen(!$descripcionBlog) > 50) {
        $errores[] = "Por favor ingrese la descripción del Blog. Mínimo 50 caracteres.";
    }
    if (!$vendedorid) {
        $errores[] = "Por favor eliga un vendedor";
    }
    if (!$imagenBlog['name'] || $imagenBlog['error']) {
        $errores[] = "Por favor agregue una imagen";
    } else if (!($imagenBlog["type"] == "image/jpeg" || $imagenBlog["type"] == "image/png")) { //* validamos el tipo de archivo que se va a subir.
        $errores[] = "El archivo debe tener formato de imagen *.jpg/png";
    }

    //Validamos archivos por tamaño.
    $medida = 1000 * 1000;

    if ($imagenBlog['size'] > $medida) {
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
        $imgInfo = new SplFileInfo($imagenBlog['name']);
        $extension = "." . $imgInfo->getExtension();
        $nombreImagen = md5(uniqid(rand(), true)) . $extension;

        //Upload img
        move_uploaded_file($imagenBlog['tmp_name'], $carpetaImagenes . $nombreImagen);


        //-Query para BD
        $queryBlog = " INSERT INTO blog (imagenBlog, tituloBlog, creadoBlog, vendedorid, descripcionBlog) VALUES ('$nombreImagen', '$tituloBlog', '$creadoBlog', '$vendedorid', '$descripcionBlog') ";

        /* echo $query; */

        //Le pasamos los datos a la base de datos:
        $resultadoBlog = mysqli_query($DB, $queryBlog);

        if ($resultadoBlog) {
            header('Location: /admin/index-blog.php?mensaje=1');
        }
    }
}

//Read vendors.

$consultaVen = "SELECT * FROM vendedores";
$resultadoVende = mysqli_query($DB, $consultaVen);


$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Crear Blog</h1>
    <?php foreach ($errores as $error) : ?>

        <div class="alerta error">
            <?php echo $error; ?>
        </div>

    <?php endforeach; ?>

    <form method="POST" action="/admin/propiedades/crear-blog.php" class="formulario" enctype="multipart/form-data">

        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="tituloBlog" placeholder="Titulo propiedad" value="<?php echo $tituloBlog ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagenBlog" accept="image/jpeg, image/png">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcionBlog" maxlength="100"><?php echo $descripcionBlog ?></textarea>
            <p id="contador">0/100</p>

            <select name="vendedorid" id="vendedor">
                <option value="">---Seleccione un vendedor---</option>

                <?php while ($row = mysqli_fetch_assoc($resultadoVende)) : ?>

                    <option <?php echo $vendedorid === $row['id'] ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"> <?php echo $row['nombre'] . " " . $row['apellido']; ?> </option>

                <?php endwhile; ?>

            </select>
        </fieldset>
        <input type="submit" value="Crear Blog" class="boton boton-verde">
    </form>
    <a href="/admin" class="boton boton-amarillo">Volver</a>
</main>

<?php incluirTemplate('footer'); ?>