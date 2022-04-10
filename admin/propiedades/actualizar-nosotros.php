<?php

require '../../includes/funciones.php';

$autenticado = siAutenticado();

if ($autenticado === "1" || $autenticado === "0") {
    header('Location: /');
}

$idNosotros = $_GET['id'];
$idNosotros = filter_var($idNosotros, FILTER_VALIDATE_INT);

if(!$idNosotros){
    header('Location: /');
}

//Base de datos
require '../../includes/config/database.php';
$DB = DB();


//Read nosotros.
$consultaNosotros = "SELECT * FROM nosotros WHERE idNosotros = ${idNosotros}";
$resultadoNosotros = mysqli_query($DB, $consultaNosotros);
$nosotros = mysqli_fetch_assoc($resultadoNosotros);

//Array para verificar errores en el formulario.
$errores = [];


$tituloNosotros = $nosotros['tituloNosotros'];
$descripcionNosotros = $nosotros['descripcionNosotros'];
$nombreImagen = $nosotros['imagenNosotros'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   /*  echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";
    exit;  */

    /* echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>"; */  

    //SANITIZAR Y VAIDAR
    $tituloNosotros = mysqli_real_escape_string($DB, $_POST['tituloNosotros']); 
    $descripcionNosotros = mysqli_real_escape_string($DB, $_POST['descripcionNosotros']);
    $creadoNosotros = date('Y/m/d');

    //Validamos los archivos FILES
    $imagenNosotros = $_FILES['imagenNosotros'];

    //Validamos el formulario

    if (!$tituloNosotros) {
        $errores[] = "Por favor ingrese un titulo a nosotros";
    }
    if (strlen(!$descripcionNosotros) > 50) { 
        $errores[] = "Por favor ingrese la descripción de nosotros. Mínimo 50 caracteres.";
    }

    //Validamos archivos por tamaño.
    $medida = 1000 * 1000; 

    if($imagenNosotros['size'] > $medida){
        $errores [] = 'La imagen es muy pesada';
    }    
/*     echo "<pre>";
    var_dump($errores); 
    echo "</pre>";

    exit; */

    //Create properties.
    if (empty($errores)) {

        //SUBIDA DE ARCHIVOS

        //Create folder:

        $carpetaImagenes = '../../imagenes/'; 
        
        if(!is_dir($carpetaImagenes)){
            mkdir($carpetaImagenes);  
        }

        //Actualizamos imagen:
        if($imagenNosotros['name']){
            unlink($carpetaImagenes . $nosotros['imagenNosotros']);

        //Rename img

        $imgInfo = new SplFileInfo( $imagenNosotros['name'] );
        $extension = "." . $imgInfo->getExtension(); 
        $nombreImagen = md5( uniqid( rand(), true)). $extension;      

        //Upoload img
        
        move_uploaded_file($imagenNosotros['tmp_name'], $carpetaImagenes . $nombreImagen);
        }else{
            $nombreImagen = $nosotros['imagenNosotros'];
        }

        //UPDATE:
        $queryNosotros = " UPDATE nosotros SET imagenNosotros  = '${nombreImagen}', tituloNosotros = '${tituloNosotros}', descripcionNosotros = '${descripcionNosotros}' WHERE idNosotros = ${idNosotros}";

       /*   echo $query;
            exit; */

        $resultadoNosotros = mysqli_query($DB, $queryNosotros); 

        if ($resultadoNosotros) { 
            header('Location: /admin/index-nosotros.php?mensaje=2');
        }
    }
}

$inicio = true; 
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Actualizar nosotros</h1>


    <?php foreach($errores as $error): ?> 

        <div class="alerta error">
            <?php echo $error; ?>
        </div>  

    <?php endforeach; ?>

    <!-- Creamos el formulario para poder CREAR-->
    <form method="POST" class="formulario" enctype="multipart/form-data"> <!-- Borramos el action para que nos redireccione a la misma URL, respete nuestra validación y siga funcionando correctamente. -->


        <fieldset>
            <legend>Información General del nosotros</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="tituloNosotros" placeholder="Titulo nosotros" value="<?php echo $tituloNosotros ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagenNosotros" accept="image/jpeg, image/png">

            <img src="/imagenes/<?php echo $nombreImagen ?>" class="imagen-admin">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcionNosotros" maxlength="100"><?php echo $descripcionNosotros ?></textarea>
            <p id="contador">0/100</p>
        </fieldset>

        <input type="submit" value="Actualizar nosotros" class="boton boton-verde">

    </form>

    <a href="/admin" class="boton boton-amarillo">Volver</a>

</main>

<?php incluirTemplate('footer'); ?>