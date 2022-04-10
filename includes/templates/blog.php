<?php

//Importamos la BD
require __DIR__ . '/../config/database.php';
$DB = DB();

//Escribir el Query.
$queryBlog = "SELECT * FROM blog INNER JOIN vendedores ON blog.vendedorid=vendedores.id LIMIT ${limite}";

//Consultar la DB.
$resultadoDB = mysqli_query($DB, $queryBlog);

?>
<?php while ($blog = mysqli_fetch_assoc($resultadoDB)): ?>
<article class="entrada-blog">
    

        <div class="imagen">
            <img loading="lazy" src="/imagenes/<?php echo $blog['imagenBlog']; ?>" alt="texto de Entrada blog">
        </div>

        <div class="texto-entrada">
            <a href="#.php">
                <h4> <?php echo $blog['tituloBlog']; ?> </h4>
                <p>Escrito el: <span> <?php echo $blog['creadoBlog']; ?> </span> por:<span>  <?php echo $blog['nombre']." ".$blog['apellido']; ?> </span></p>

                <p> <?php echo $blog['descripcionBlog']; ?> </p>
            </a>
        </div>

</article>
<?php endwhile ?>

<?php
mysqli_close($DB);
?>