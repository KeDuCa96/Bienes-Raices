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
$query = "SELECT * FROM nosotros";

//Consultar la DB.
$resultadoDB = mysqli_query($DB, $query);

//? Mostrar mensaje adicional:

$mensaje = $_GET['mensaje'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idNosotros = $_POST['id']; 
    $idNosotros = filter_var($idNosotros, FILTER_VALIDATE_INT);

/*     var_dump($_POST['id']); */

    if($idNosotros){

        //Delete Files
        $queryNosotros = "SELECT imagenNosotros FROM nosotros WHERE idNosotros = ${idNosotros}";

        $resultadoDeleteNosotros = mysqli_query($DB, $queryNosotros);
        $propiedadNosotros = mysqli_fetch_assoc($resultadoDeleteNosotros);

        unlink('../imagenes/' . $propiedadNosotros['imagenNosotros']);

        //Delete propierti
        $queryNosotros = "DELETE FROM Nosotros WHERE idNosotros = ${idNosotros}";
        
        $resultadoDeleteNosotros = mysqli_query($DB, $queryNosotros);

        if($resultadoDeleteNosotros){
            header('Location: /admin\index-nosotros.php?mensaje=3'); 
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
        <p class="alerta exito">¡Nosotros Creado Correctamente!</p>
    <?php elseif( intval($mensaje) === 2) :?>  
        <p class="alerta exito">¡Nosotros Actualizado Correctamente!</p>
    <?php elseif( intval($mensaje) === 3) :?>  
        <p class="alerta error">¡Nosotros Eliminado Correctamente!</p>
    <?php endif; ?>



    <table class="propiedades">
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody> 
        <?php while( $propiedadNosotros = mysqli_fetch_assoc($resultadoDB)): ?>
            <tr>
                <td> <?php echo $propiedadNosotros['tituloNosotros']; ?> </td>
                <td> <img src="/imagenes/<?php echo $propiedadNosotros['imagenNosotros']; ?>" alt="imagen Nosotros" class="imagen-tabla"> </td> 
                <td> <?php echo $propiedadNosotros['descripcionNosotros']; ?> </td>
                <td>
        
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $propiedadNosotros['idNosotros']; ?>"> <!-- Estos input tipo hidden no se pueden ver, pero si inspeccionamos el código só los podemos ver. No usamos tipo TEXT porque los usarios pueden modificarlo. -->

                    <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form>

                    <a href="/admin/propiedades/actualizar-nosotros.php?id=<?php echo $propiedadNosotros['idNosotros']; ?>" class="boton-amarillo-block">Actualizar</a> <!-- Con este QueryString podremos mostrar por url el id de la propiedad a actualizar y esto nos ayudará a traernos la info de cada propiedad. -->
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="/admin\propiedades\crear-nosotros.php" class="boton boton-verde">Nuevo Blog</a>
</main>

<?php
    //* 5. Cerrar la conexión:
    mysqli_close($DB);
    incluirTemplate('footer');
?>