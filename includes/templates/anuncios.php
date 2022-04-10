<?php

//* 1. Importamos la BD
require __DIR__ . '/../config/database.php';
$DB = DB();

//* 2. Escribir el Query.
$query = "SELECT * FROM propiedades LIMIT ${limite}";

//* 3. Consultar la DB.
$resultadoDB = mysqli_query($DB, $query);

//* 4. Mostrar los resultados.
//* 5. Cerrar la conexión. (opcional porque php detecta cuando no esta en uso y se cierra)

?>

<div class="contenedor-anuncios">
    <?php while ($propiedad = mysqli_fetch_assoc($resultadoDB)) : ?>

        <div class="anuncio">

            <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="anuncio">

            <div class="contenido-anuncio">
                <h3> <?php echo $propiedad['titulo']; ?> </h3>
                <p> <?php echo limitar_cadena($propiedad['descripcion'], 50, "..."); ?> </p>
                <p class="precio"> $ <?php echo number_format($propiedad['precio']); ?> </p>

                <ul class="iconos-caracteristicas">
                    <li>
                        <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                        <p> <?php echo $propiedad['wc']; ?> </p>
                    </li>
                    <li>
                        <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                        <p> <?php echo $propiedad['estacionamiento']; ?> </p>
                    </li>
                    <li>
                        <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono dormitorio">
                        <p> <?php echo $propiedad['habitaciones']; ?> </p>
                    </li>
                </ul>

                <a href="anuncio.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">
                    VER PROPIEDAD
                </a>

            </div> <!-- .contenido-anuncion -->
        </div> <!-- anucion -->
    <?php endwhile; ?>
</div> <!-- .contenido -->

<?php
mysqli_close($DB);
?>