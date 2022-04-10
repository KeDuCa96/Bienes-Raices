<?php

require '../../includes/funciones.php';

$autenticado = siAutenticado();

if ($autenticado === "1" || $autenticado === "0") {
    header('Location: /');
}

$idblog = $_GET['id'];
$idblog = filter_var($idblog, FILTER_VALIDATE_INT);

if(!$idblog){
    header('Location: /');
}

//Base de datos
require '../../includes/config/database.php';
$DB = DB();

//Read vendors.
$consultaVen = "SELECT * FROM vendedores";
$resultadoVende = mysqli_query($DB, $consultaVen);

//Read Blog.
$consultaBlog = "SELECT * FROM blog WHERE idblog = ${idblog}";
$resultadoBlog = mysqli_query($DB, $consultaBlog);
$blog = mysqli_fetch_assoc($resultadoBlog);

//Array para verificar errores en el formulario.
$errores = [];


$tituloBlog = $blog['tituloBlog'];
$descripcionBlog = $blog['descripcionBlog'];
$vendedorid = $blog['vendedorid'];
$nombreImagen = $blog['imagenBlog'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   /*  echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";
    exit;  */

    /* echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>"; */  

    //SANITIZAR Y VAIDAR
    $tituloBlog = mysqli_real_escape_string($DB, $_POST['tituloBlog']); 
    $descripcionBlog = mysqli_real_escape_string($DB, $_POST['descripcionBlog']);
    $creadoBlog = date('Y/m/d');
    $vendedorid = mysqli_real_escape_string($DB, $_POST['vendedorid']);

    //Validamos los archivos FILES

    $imagenBlog = $_FILES['imagenBlog'];

    //Validamos el formulario

    if (!$tituloBlog) {
        $errores[] = "Por favor ingrese un titulo al Blog";
    }
    if (strlen(!$descripcionBlog) > 50) { 
        $errores[] = "Por favor ingrese la descripción del Blog. Mínimo 50 caracteres.";
    }
    if (!$vendedorid) {
        $errores[] = "Por favor eliga un vendedor";
    }

    //Validamos archivos por tamaño.
    $medida = 1000 * 1000; 

    if($imagenBlog['size'] > $medida){
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
        if($imagenBlog['name']){
            unlink($carpetaImagenes . $blog['imagenBlog']);

        //Rename img

        $imgInfo = new SplFileInfo( $imagenBlog['name'] );
        $extension = "." . $imgInfo->getExtension(); 
        $nombreImagen = md5( uniqid( rand(), true)). $extension;      

        //Upoload img
        
        move_uploaded_file($imagenBlog['tmp_name'], $carpetaImagenes . $nombreImagen);
        }else{
            $nombreImagen = $blog['imagenBlog'];
        }

        //UPDATE:
        $queryBlog = " UPDATE blog SET imagenBlog  = '${nombreImagen}', tituloBlog = '${tituloBlog}', vendedorid = ${vendedorid}, descripcionBlog = '${descripcionBlog}' WHERE idblog = ${idblog}";

       /*   echo $query;
            exit; */

        $resultadoBlog = mysqli_query($DB, $queryBlog); 

        if ($resultadoBlog) { 
            header('Location: /admin/index-blog.php?mensaje=2');
        }
    }
}

$inicio = true; 
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Actualizar Blog</h1>


    <?php foreach($errores as $error): ?> 

        <div class="alerta error">
            <?php echo $error; ?>
        </div>  

    <?php endforeach; ?>

    <!-- Creamos el formulario para poder CREAR-->
    <form method="POST" class="formulario" enctype="multipart/form-data"> <!-- Borramos el action para que nos redireccione a la misma URL, respete nuestra validación y siga funcionando correctamente. -->


        <fieldset>
            <legend>Información General del Blog</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="tituloBlog" placeholder="Titulo Blog" value="<?php echo $tituloBlog ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagenBlog" accept="image/jpeg, image/png">

            <img src="/imagenes/<?php echo $nombreImagen ?>" class="imagen-admin">

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcionBlog" maxlength="100"><?php echo $descripcionBlog ?></textarea>
            <p id="contador">0/100</p>
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedorid" id="vendedor">
                <option value="">---Seleccione un vendedor---</option>
                <?php while($row = mysqli_fetch_assoc($resultadoVende)): ?> //* Recordar que con fetch_assoc accedemos a las filas de la bd y las regresa como array asociativos.
                    <option <?php echo $vendedorid === $row['id'] ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"> <?php echo $row['nombre']. " ". $row['apellido']; ?> </option> 
                    
                <?php endwhile; ?>

            </select>
        </fieldset>

        <input type="submit" value="Actualizar Blog" class="boton boton-verde">

    </form>

    <a href="/admin" class="boton boton-amarillo">Volver</a>

</main>

<?php incluirTemplate('footer'); ?>