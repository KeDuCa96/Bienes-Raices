<?php
require '../../includes/funciones.php'; /*usamos el ../ para retroceder  la cantidad de carpetas que necesitemos */

$autenticado = siAutenticado();

if(!$autenticado){
    header('Location: /');
}

/*  Base de datos */
require '../../includes/config/database.php'; /* Exportamos la conexión */

$DB = DB(); //* Llamamos la función base de datos

//? PHP: Cuenta con variables globales GET, POST, otra para manejar archivos y otra para manejar secciones.

//* $_GET = Lee los datos, pero no es recomendado porque se almacenan datos en URL y se exponen los datos. Es recomendable para paginas web donde se puede compartir informacion del produto, etc o para visitar sitios.

//* $_POST = Maneja los datos internamente en el archivo. Se recomienda usarlo cuando necestiamos leer datos sensibles o envíar datos a formularios.

//* $_SERVER = Contiene toda la info del servidor por ejemplo: donde esta ubicado, cual es la url, el software que se esta usando e inlcuso el navegador que usar y el SO que usamos, por ejemplo sería cuando entramos a descargar una app notamos como nos identifca nuestro SO y nos da la versión que mas se adapte a nuestro so.

//* $_FILES = Nos permite leer información de archivos como imagenes.


//? Array para verificar errores en el formulario.
$errores = [];

$titulo = '';
$precio ='';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorid = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
/*     echo "<pre>";
    var_dump($_POST); 
    echo "</pre>";

    echo "<pre>";
    var_dump($_FILES); 
    echo "</pre>";
    
    exit; */

    //? SANITIZAR
    //* Cuando sanitizamos nuestros datos estamos limpando de caracteres que nos pueden terminar afectando nuestra BD. Por temas de seguridad se recomienda sanitizar los datos antes de envíarlos a la DB, para luego validarlos.
    //? Validar
    //* Cuando validamos simplemente corroboramos que lo que nos da el usuario es cierto, es el tipo de dato que nuestra base de datos espera.
         // https://www.php.net/manual/es/filter.filters.validate.php
         // https://www.php.net/manual/es/filter.filters.sanitize.php

    //* Una vez hayamos sanitizado y validado nuestros datos debemos asegurarnos que verdaderamente estan limpios y para eso php nos da una función llamada: mysqli_real_scape_string  y esta función lo que basicamente es escapaar los datos (guardarlos como entidad o datos no ejectuables)con los cuales nos intenten hacer sqlinyection o cross site scripting.
        //PD: //! NUNCA CONFIES EN TUS USUARIOS. SIEMPRE VALIDA, SANITIZA Y USA LA FUNCIÓN. (aunque con PDO o POO podemos darle hacerlo de otra forma.)

    $titulo = mysqli_real_escape_string($DB, $_POST['titulo']); //* Como toda función tiene sus parametros; el primero es la base de datos y el segundo es lo que vamos a validar.
    $precio = mysqli_real_escape_string($DB, $_POST['precio']);
    $descripcion = mysqli_real_escape_string($DB, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($DB, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string($DB, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($DB, $_POST['estacionamiento']);
    $creado = date('Y/m/d');
    $vendedorid = mysqli_real_escape_string($DB, $_POST['vendedorid']);

    //? Validamos los archivos FILES

    $imagen = $_FILES['imagen'];


    //? Validamos el formulario

    if (!$titulo) {
        $errores[] = "Por favor ingrese un titulo a propiedad";
    }
    if (!$precio) {
        $errores[] = "Por favor ingrese el precio de la propiedad";
    }
    if (strlen(!$descripcion) > 50) {  //* Recordar que la función srtlen nos ayuda a verificar la cantidad de caracteres tiene.
        $errores[] = "Por favor ingrese la descripción de la propiedad. Mínimo 50 caracteres.";
    }
    if (!$habitaciones) {
        $errores[] = "Por favor ingrese la cantidad de habitaciones";
    }
    if (!$wc) {
        $errores[] = "Por favor ingrese la cantidad de baños";
    }
    if (!$estacionamiento) {
        $errores[] = "Por favor ingrese la cantidad de es estacionamientos";
    }
    if (!$vendedorid) {
        $errores[] = "Por favor eliga un vendedor";
    }
    if (!$imagen['name'] || $imagen['error']) {
        $errores[] = "Por favor agregue una imagen";
    }else if(!($imagen["type"] == "image/jpeg" || $imagen["type"] == "image/png")){ //* validamos el tipo de archivo que se va a subir.
        $errores[] = "El archivo debe tener formato de imagen *.jpg/png";
    } 

    //? Validamos archivos por tamaño.

    $medida = 1000 * 1000; //* Esto nos convertira de bytes a KB. Nos permitirá subir máximo 1mb.

    if($imagen['size'] > $medida){
        $errores [] = 'La imagen es muy pesada';
    }    
/*     echo "<pre>";
    var_dump($errores); 
    echo "</pre>";

    exit; */

    //? Create properties.
    if (empty($errores)) { //* Si el arreglo esta vacio haga:

            //? SUBIDA DE ARCHIVOS
        //* Php nos permite crear carpetas:

        //? Crate folder:

        $carpetaImagenes = '../../imagenes/'; //* Creamos la carpeta de imagenes en la raiz del proyecto.

        //* Evitamos que cree muchas las veces la carpeta o que intente crearlas:
        if(!is_dir($carpetaImagenes)){ //* is_dir nos retorna si una carpeta existe o no existe.
            mkdir($carpetaImagenes);  //* Como no existe la creamos.
        }

        //? rename img
        //* Renombamos la imagen para evitar que se borre la anterior. Recuerda que no pueden exisitir dos archivos con el mismo nombre en la misma carpeta. 

        $imgInfo = new SplFileInfo( $imagen['name'] );
        $extension = "." . $imgInfo->getExtension(); // Obtiene la extension de la imagen
        $nombreImagen = md5( uniqid( rand(), true)). $extension; //* md5 nos sirve para hashear (//! hashear no es lo mismo que encriptar. Hashear es tomar una entrada y convertirla), pero no es suficiente ya que nos sigue dando un nombre "raro", pero se puede repetir. uniqid nos crea un id único. rand nos da un número randon. 
            //! ESTAS FUNCIONES NO TIENEN NADA QUE VER CON SEGURIDAD. MD5 YA FUE HACKEADO.
                                                            

        //? upload img
            //* Cuando subimos una imagen se almacena de forma temporal en el servidor, pero necesitamos de de uan función de php para llevarla a su destino:
        
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen); //* esta función nos permite mover nuestro archivo que se encuentra guardado de forma temporal en el servidor para llevarlo a nuestra carpeta destino. El primer parametro es el nombre de la variable donde se almacenó junto con la ruta relativa (recordar que la ruta relativa la encontramos en tmp_name que es un dato que nos arroja $_FILES.), el segundo es la úbicación de la carpeta y por último el nombre que le querramos dar al archivo.


        
        //? Esto es lenguaje MySql (podemos repasar las clases pasadas para comprobarlo.)
        $query = " INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorid ) VALUES ('$titulo', '$precio', '$nombreImagen', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorid') ";

        /* echo $query; */

        //! Le pasamos los datos a la base de datos:

        $resultado = mysqli_query($DB, $query);  //* Primero le pasamos la conexión a la DB y luego el query.

        if ($resultado) {  //* Hacemos un if por si es resultado nos avise que se hizo correctamente.
            
            //* Redireccionamos al usuario a otra pag para evitar cree muchos resultados a la vez.
            //! Este header solo funciona si no hay HTML previo. Debe estar antes de abrir cualquier etiqueta.
            header('Location: /admin?mensaje=1'); //* Aplicamos QueryString (URL con parametros). El Query String siempre inicia con un signo de interrogacion (?). con un andperson & podemos agregarle otra variable u otro parametro. 
        }
    }
}

//? Read vendors.

$consultaVen = "SELECT * FROM vendedores";
$resultadoVende = mysqli_query($DB, $consultaVen);





$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Crear</h1>


    <?php foreach($errores as $error): ?> 

        <div class="alerta error">
            <?php echo $error; ?>
        </div>  

    <?php endforeach; ?>

    <!-- Creamos el formulario para poder CREAR-->
    <form method="POST" action="/admin/propiedades/crear.php" class="formulario" enctype="multipart/form-data"> <!-- Con el action indicamos donde queremos procesar o envíar la información. Method nos brinda dos metodos ( GET Y POST ). enctype nos permite subir archivos. Es necesario en cualquier tecnologia o lenguaje. -->


        <fieldset>
            <legend>Información General</legend>

            <!-- Los datos que ingresemos en estos input son los que se guardarán en la DB -->
            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo ?>"><!-- name nos sirve para leer la información que nos indica el cliente. Se recomienda usar el mismo nombre del name para el for, el id y donde se va a almecenar en la base de datos.-->

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png" > <!-- Type="file" nos seleccionar archivos desde nueestra pc. accept nos permite seleccionar el tipo de archivo que el usuario debe subir puede ser un pdf, worg,imagen, etc -->

            <label for="descripcion">Descripcion:</label>
            <textarea id="descripcion" name="descripcion" maxlength="300"><?php echo $descripcion ?></textarea>
            <p id="contador">0/300</p>
        </fieldset>

        <!-- información de la propiedad -->
        <fieldset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Indique la cantidad de habitaciones" min="1" max="9" value="<?php echo $habitaciones ?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Indique la cantidad de baños" min="1" max="9" value="<?php echo $wc ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Indique la cantidad de estacionamientos/parqueaderos" max="9" value="<?php echo $estacionamiento ?>">

        </fieldset>

        <!-- Información del vendedor -->

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedorid" id="vendedor">
                <option value="">---Seleccione un vendedor---</option>
                <?php while($row = mysqli_fetch_assoc($resultadoVende)): ?> //* Recordar que con fetch_assoc accedemos a las filas de la bd y las regresa como array asociativos.
                    <option <?php echo $vendedorid === $row['id'] ? 'selected' : ''; ?> value="<?php echo $row['id']; ?>"> <?php echo $row['nombre']. " ". $row['apellido']; ?> </option> 
                    
                    //* Agregamos el operador ternario en el option para poder almacenar el vendedor seleccionado en la variable de vendedorid y se puede leer de esta forma: si el vendedorid es exactamente igual al id del vendedor agrega el atributo de selected sino no agreges nada.
                    //* Concatenamos nombre y apellido para visualizarlo en la lista desplegable. Dentro del value imprimimos o mostramos los ID de cada vendedor para poder darle ingresarle un valor a la BD al momento de completar el formulario.
                    
                <?php endwhile; ?>

            </select>
        </fieldset>

        <input type="submit" value="Crear propiedad" class="boton boton-verde">

    </form>

    <a href="/admin" class="boton boton-amarillo">Volver</a>

</main>

<?php incluirTemplate('footer'); ?>


