<?php

require 'includes/funciones.php'; /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <main class="seccion contenedor">
        <h2>Casas y Depas en ventas</h2>

    <?php 
    $limite = 12;
    include 'includes/templates/anuncios.php';        
    ?>

    </main>
</main>

<?php incluirTemplate('footer'); ?>