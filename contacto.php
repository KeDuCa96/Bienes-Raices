<?php

require 'includes/funciones.php'; /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Contacto</h1>

    <picture>
        <source srcset="build/img/destacada3.webp" type="img/webp">
        <source srcset="build/img/destacada3.jpg" type="img/jepg">
        <img loading="lazy" src="build/img/destacada3.jpg" alt="contacto">
    </picture>

    <h2>Llene el formulario de contacto</h2>
    <form class="formulario" action="">
        <fieldset>
            <!-- Nos sirve para agregar campos relacionados -->
            <legend>Información Personal</legend>

            <label for="Nombre"">Nombre</label>
                <input type=" text" placeholder="Tu nombre" id="Nombre">

                <label for="E-mail"">E-mail</label>
                <input type=" email" placeholder="Tu Email" id="E-mail">

                    <label for="Telefono"">Teléfono</label>
                <input type=" tel" placeholder="Número de contacto" id="Telefono">

                        <label for="Mensaje"">Mensaje:</label>
                <textarea id=" Mensaje"></textarea>
        </fieldset>

        <fieldset>
            <legend>Información sobre la propiedad</legend>

            <label for="opciones">Vende o compra</label>
            <select name="" id="opciones">
                <!-- Select nos permite hacer una lista de desplegables u opciones -->
                <option value="" disabled selected>---Seleccione---</option> <!-- disable selectd nos sirve para mostrar una opción que no se puede seleccionar y es la primera en mostrarse como un placeholder-->
                <option value="Compra">Compra</option> <!-- value es lo que se va a enviar al servidor -->
                <option value="Venta">Venta</option>
            </select>

            <label for="presupuesto">Precio o presupuesto</label>
            <input type="number" placeholder="Precio o presupuesto" id="presupuesto">
        </fieldset>

        <fieldset>
            <legend>Contacto</legend>

            <p>¿Cómo desea ser contactado?</p>

            <div class="forma-contacto">

                <label for="contactar-telefono">Teléfono</label>
                <input type="radio" name="contacto" value="telefono" checked id="contactar-telefono"> <!-- name es importante porque es la forma con que podremos seleccionar desde php -->
                <label for="contactar-email">E-mail</label>
                <input type="radio" name="contacto" value="email" checked id="contactar-email">
            </div>

            <p>Si eligió teléfono, elija la fecha y la hora</p>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha">

            <label for="hora">Hora:</label>
            <input type="time" id="hora" min="09:00" max="18:00"> <!-- Con el min y max dpodemos decirle al usuario o cliente las horas disponibles para contactar -->
        </fieldset>

        <input type="submit" value="Enviar" class="boton-verde">
    </form>
</main>

<?php incluirTemplate('footer'); ?>