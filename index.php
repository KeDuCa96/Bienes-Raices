<?php

require 'includes/funciones.php'; /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */


incluirTemplate('header', $inicio = true); /*  */
?>

<main class="contenedor seccion">
    <h1>Más sobre nosotros</h1> <!-- agregamos información sobre nosotros -->
    <div class="iconos-nosotros">
        <div class="icono">
            <img src="build/img/icono1.svg" alt="Icono seguridad" loading="lazy">
            <h3>Seguridad</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita dolorem totam aut sequi nihil, numquam cumque molestias illo, suscipit quod odio adipisci inventore iste doloribus excepturi in quidem assumenda ex.</p>
        </div>
        <div class="icono">
            <img src="build/img/icono2.svg" alt="Icono Precio" loading="lazy">
            <h3>Precio</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita dolorem totam aut sequi nihil, numquam cumque molestias illo, suscipit quod odio adipisci inventore iste doloribus excepturi in quidem assumenda ex.</p>
        </div>
        <div class="icono">
            <img src="build/img/icono3.svg" alt="Icono Tiempo" loading="lazy">
            <h3>A tiempo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita dolorem totam aut sequi nihil, numquam cumque molestias illo, suscipit quod odio adipisci inventore iste doloribus excepturi in quidem assumenda ex.</p>
        </div>
    </div>
</main>

<section class="seccion contenedor">
    <!-- Sección de ventas -->
    <!-- Estos seccion los usamos siempre que tengamos un heading que nos lleva a una nueva ssción  -->
    <h2> Casas y Depas en Ventas</h2>

    <!-- Agregamos el template de anuncions -->

    <?php 
    $limite = 3;
    include 'includes/templates/anuncios.php';        
    ?>

    <div class="alinear-derecha">
        <a href="anuncios.php" class="boton-verde">Ver todas</a>
    </div>
</section>

<section class="imagen-contacto">
    <h2>Encuentra la casa de tus sueños</h2>
    <p>Llena el formulario de contacto y un asesor de porndrá en contacto contigo</p>
    <a href="contacto.php" class="boton-amarillo">Contactanos</a>
</section>

<div class="contenedor seccion seccion-inferior">
    <!-- testimoniales -->
    <section class="blog">
        <h3>Nuestro blog</h3>

        <article class="entrada-blog">
            <!-- por regla, las entradas de blog o un post de foro deben estar dentro de un article -->
            <div class="imagen">
                <picture>
                    <source srcset="build/img/blog1.webp" type="image/webp">
                    <source srcset="build/img/blog1.jpeg" type="image/jpeg">
                    <img loading="lazy" src="build/img/blog1.jpg" alt="texto de Entrada blog">
                </picture>
            </div>

            <div class="texto-entrada">
                <a href="entrada.php">
                    <h4>Terraza en el techo de tu casa</h4>
                    <p>Escrito el: <span>17/03/2022</span> por:<span> Admin</span></p>

                    <p>
                        Consejos para construir una terraza en el techo de tu casa con los mejores materiales y ahorrando dinero
                    </p>
                </a>
            </div>

        </article>

        <article class="entrada-blog">
            <!-- por regla, las entradas de blog o un post de foro deben estar dentro de un article -->
            <div class="imagen">
                <picture>
                    <source srcset="build/img/blog2.webp" type="image/webp">
                    <source srcset="build/img/blog2.jpeg" type="image/jpeg">
                    <img loading="lazy" src="build/img/blog2.jpg" alt="texto de Entrada blog">
                </picture>
            </div>

            <div class="texto-entrada">
                <a href="entrada.php">
                    <h4>Guía para la decoración de tu casa</h4>
                    <p>Escrito el: <span>20/03/2022</span> por:<span> Kevin</span></p>

                    <p>
                        Maximiza el espacio de tu hogar con esta guía, aprende a combinar muebles y colores para darle vida a tu espacio.
                    </p>
                </a>
            </div>

        </article>
    </section>

    <section class="testimoniales">
        <h3>Testimoniales</h3>
        <div class="testimonial">
            <blockquote class="testimonio">
                Muy buena atención, cumplieron con lo que prometieron. Recomendados. Llegaron a la hora pactada, dejaron la casa limpia, los acabados son lo mejor. No me arrepiento de adquirir el servicio.
            </blockquote>
            <p>- Kevin Durán</p>
        </div>
    </section>
</div>

<?php incluirTemplate('footer'); ?>