<?php


require '../includes/funciones.php'; 

$autenticado = siAutenticado();

if($autenticado === "1" || $autenticado === "0" ){
    header('Location: /');
}
//? Importamos la DB:

//Importar la conexión:
require '../includes/config/database.php';
$DB = DB(); 

//Escribir el Query:
$query = "SELECT * FROM blog INNER JOIN vendedores ON blog.vendedorid=vendedores.id";

//Consultar la DB.
$resultadoDB = mysqli_query($DB, $query);

//? Mostrar mensaje adicional:

$mensaje = $_GET['mensaje'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idblog = $_POST['id']; 
    $idblog = filter_var($idblog, FILTER_VALIDATE_INT);

/*     var_dump($_POST['id']);
 */
    if($idblog){

        //Delete Files
        $queryBlog = "SELECT imagenBlog FROM blog WHERE idblog = ${idblog}";

        $resultadoDeleteBlog = mysqli_query($DB, $queryBlog);
        $propiedadBlog = mysqli_fetch_assoc($resultadoDeleteBlog);

        unlink('../imagenes/' . $propiedadBlog['imagenBlog']);

        //Delete propierti
        $queryBlog = "DELETE FROM blog WHERE idblog = ${idblog}";
        
        $resultadoDeleteBlog = mysqli_query($DB, $queryBlog);

        if($resultadoDeleteBlog){
            header('Location: /admin\index-blog.php?mensaje=3'); 
        }
    }
    
}

//? Incluir template:

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">


    <h1>Administrador de Blog</h1> 
    <div class="seccion-admin">
        <!-- <h1>Bienvenido Super admin</h1> -->

        <a href="/admin" class="boton boton-admin">Propiedad</a>
        <a href="/admin\index-blog.php" class="boton boton-admin">Blog</a>
        <a href="/admin\index-nosotros.php" class="boton boton-admin">Nosotros</a>
        <a href="/admin\index-vendedores.php" class="boton boton-admin">Vendedor</a>
        <a href="/" class="boton boton-amarillo">Salir</a>

    </div>
    
    <?php if( intval($mensaje) === 1) :?> 
        <p class="alerta exito">¡Blog Creado Correctamente!</p>
    <?php elseif( intval($mensaje) === 2) :?>  
        <p class="alerta exito">¡Blog Actualizado Correctamente!</p>
    <?php elseif( intval($mensaje) === 3) :?>  
        <p class="alerta error">¡Blog Eliminado Correctamente!</p>
    <?php endif; ?>



    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Vendedor</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody> 
        <?php while( $propiedadBlog = mysqli_fetch_assoc($resultadoDB)): ?>
            <tr>
                <td> <?php echo $propiedadBlog['idblog']; ?> </td>
                <td> <?php echo $propiedadBlog['tituloBlog']; ?> </td>
                <td> <img src="/imagenes/<?php echo $propiedadBlog['imagenBlog']; ?>" alt="imagen blog" class="imagen-tabla"> </td> 
                <td> <?php echo $propiedadBlog['descripcionBlog']; ?> </td>
                <td> <?php echo $propiedadBlog['nombre']." ".$propiedadBlog['apellido']; ?> </td>
                <td>
        
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $propiedadBlog['idblog']; ?>"> <!-- Estos input tipo hidden no se pueden ver, pero si inspeccionamos el código só los podemos ver. No usamos tipo TEXT porque los usarios pueden modificarlo. -->

                    <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form>

                    <a href="/admin/propiedades/actualizar-blog.php?id=<?php echo $propiedadBlog['idblog']; ?>" class="boton-amarillo-block">Actualizar</a> <!-- Con este QueryString podremos mostrar por url el id de la propiedad a actualizar y esto nos ayudará a traernos la info de cada propiedad. -->
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="/admin\propiedades\crear-blog.php" class="boton boton-verde">Nuevo Blog</a>
</main>

<?php
    //* 5. Cerrar la conexión:
    mysqli_close($DB);
    incluirTemplate('footer');
?>