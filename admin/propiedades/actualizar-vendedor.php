<?php
require '../../includes/funciones.php';

$autenticado = siAutenticado();

if ($autenticado === "1" || $autenticado === "0") {
    header('Location: /');
}

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id){
    header('Location: /');
}

//Base de datos
require '../../includes/config/database.php';
$DB = DB();


//Read vendedor.
$consultaVendedores = "SELECT * FROM vendedores WHERE id = ${id}";
$resultadoVendedores = mysqli_query($DB, $consultaVendedores);
$vendedores = mysqli_fetch_assoc($resultadoVendedores);

//Array para verificar errores en el formulario.
$errores = [];


$nombre = $vendedores['nombre'];
$apellido = $vendedores['apellido'];
$telefono = $vendedores['telefono'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   /*  echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";
    exit;  */

    /* echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>"; */  

    //SANITIZAR Y VAIDAR
    $nombre = mysqli_real_escape_string($DB, $_POST['nombre']); 
    $apellido = mysqli_real_escape_string($DB, $_POST['apellido']);
    $telefono = mysqli_real_escape_string($DB, $_POST['telefono']);

    //Validamos el formulario
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

    //Create properties.
    if (empty($errores)) {

        //UPDATE:
        $queryVendedores = "UPDATE vendedores SET nombre = '${nombre}', apellido = '${apellido}', telefono = '${telefono}' WHERE id = ${id}";

       /*   echo $query;
            exit; */

        $resultadoVendedores = mysqli_query($DB, $queryVendedores); 

        if ($resultadoVendedores) { 
            header('Location: /admin/index-vendedores.php?mensaje=2');
        }
    }
}

$inicio = true; 
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Actualizar vendedores</h1>


    <?php foreach($errores as $error): ?> 

        <div class="alerta error">
            <?php echo $error; ?>
        </div>  

    <?php endforeach; ?>

    <!-- Creamos el formulario para poder CREAR-->
    <form method="POST" class="formulario" enctype="multipart/form-data"> <!-- Borramos el action para que nos redireccione a la misma URL, respete nuestra validación y siga funcionando correctamente. -->


        <fieldset>
            <legend>Información General de vendedores</legend>
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre " value="<?php echo $nombre ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" placeholder="Apellido " value="<?php echo $apellido ?>">

            <label for="telefono">Telefono:</label>
            <input type="number" id="telefono" name="telefono" placeholder="Telefono " value="<?php echo $telefono ?>">
        </fieldset>

        <input type="submit" value="Actualizar vendedor" class="boton boton-verde">

    </form>

    <a href="/admin" class="boton boton-amarillo">Volver</a>

</main>

<?php incluirTemplate('footer'); ?>