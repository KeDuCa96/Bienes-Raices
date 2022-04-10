<?php

//Importamos la BD
require __DIR__ . '/../config/database.php';
$DB = DB();

//Escribir el Query.
$queryNosotros = "SELECT * FROM nosotros LIMIT ${limite}";

//Consultar la DB.
$resultadoDB = mysqli_query($DB, $queryNosotros);

?>

<?php while($Nosotros = mysqli_fetch_assoc($resultadoDB)): ?>
<div class="seccion-nosotros">
    <div class="nosotros">
        <picture>
            <img loading="lazy" src="/imagenes/<?php echo $Nosotros['imagenNosotros'] ?>" alt="Sobre Nosotros">
    </div>

    <div class="texto-nosotros">
        <blockquote>
        <?php echo $Nosotros['tituloNosotros'] ?>
        </blockquote>
        <p> <?php echo $Nosotros['descripcionNosotros'] ?> </p>
    </div>
</div>
<?php endwhile; ?>

<?php
mysqli_close($DB);
?>

