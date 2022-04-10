<?php

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id){
    header('Location: /');
}
//* 1. Importamos la BD
require 'includes\config\database.php';
$DB = DB();

//* 2. Escribir el Query.
$query = "SELECT * FROM propiedades WHERE id = ${id}";

//* 3. Consultar la DB.
$resultadoDB = mysqli_query($DB, $query);

if(!$resultadoDB->num_rows){
    header('Location: /');
}
$propiedad = mysqli_fetch_assoc($resultadoDB);

//* 4. Mostrar los resultados.
//* 5. Cerrar la conexión. (opcional porque php detecta cuando no esta en uso y se cierra).

require 'includes/funciones.php'; /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>



<main class="contenedor seccion contenido-centrado">
    <h1> <?php echo $propiedad['titulo']; ?> </h1>

    <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="anuncio">

    <div class="resumen-propiedad">
        <p class="precio"> $ <?php echo number_format($propiedad['precio']); ?> </p>

        <ul class="iconos-caracteristicas">
            <li>
                <img loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                <p> <?php echo $propiedad['wc']; ?> </p>
            </li>
            <li>
                <img loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                <p> <?php echo $propiedad['estacionamiento']; ?> </p>
            </li>
            <li>
                <img loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                <p> <?php echo $propiedad['habitaciones']; ?> </p>
            </li>
        </ul>

        <p> <?php echo $propiedad['descripcion']; ?> </p>
    </div>
    </div>


</main>

<?php 
    mysqli_close($DB);
    incluirTemplate('footer');
?>