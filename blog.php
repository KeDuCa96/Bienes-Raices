<?php

require 'includes/funciones.php'; /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Guías para la decoración de tu hogar</h1>

    <?php 
    $limite = 6;
    include 'includes/templates/blog.php';        
    ?>
    
</main>

<?php incluirTemplate('footer'); ?>