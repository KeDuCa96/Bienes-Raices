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
$query = "SELECT * FROM vendedores";

//Consultar la DB.
$resultadoDB = mysqli_query($DB, $query);

//? Mostrar mensaje adicional:

$mensaje = $_GET['mensaje'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id']; 
    $id = filter_var($id, FILTER_VALIDATE_INT);

/*     var_dump($_POST['id']); */

    if($id){

        //Delete propierti
        $queryVendedores = "DELETE FROM vendedores WHERE id = ${id}";
        
        $resultadoDeleteVendedores = mysqli_query($DB, $queryVendedores);

        if($resultadoDeleteVendedores){
            header('Location: /admin\index-vendedores.php?mensaje=3'); 
        }
    }
    
}

//? Incluir template:

$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');

?>

<main class="contenedor seccion">


    <h1>Administrador de Vendedores</h1> 
    <div class="seccion-admin">
        <!-- <h1>Bienvenido Super admin</h1> -->

        <a href="/admin" class="boton boton-admin">Propiedad</a>
        <a href="/admin\index-blog.php" class="boton boton-admin">Blog</a>
        <a href="/admin\index-nosotros.php" class="boton boton-admin">Nosotros</a>
        <a href="/admin\index-vendedores.php" class="boton boton-admin">Vendedor</a>
        <a href="/" class="boton boton-amarillo">Salir</a>

    </div>
    
    <?php if( intval($mensaje) === 1) :?> 
        <p class="alerta exito">¡Vendedor Creado Correctamente!</p>
    <?php elseif( intval($mensaje) === 2) :?>  
        <p class="alerta exito">¡Vendedor Actualizado Correctamente!</p>
    <?php elseif( intval($mensaje) === 3) :?>  
        <p class="alerta error">¡Vendedor Eliminado Correctamente!</p>
    <?php endif; ?>



    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody> 
        <?php while( $propiedadVendedores = mysqli_fetch_assoc($resultadoDB)): ?>
            <tr>
                <td> <?php echo $propiedadVendedores['id']; ?> </td>
                <td> <?php echo $propiedadVendedores['nombre']; ?> </td>
                <td> <?php echo $propiedadVendedores['apellido']; ?> </td>
                <td> <?php echo $propiedadVendedores['telefono']; ?> </td>
                <td>
        
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $propiedadVendedores['id']; ?>"> <!-- Estos input tipo hidden no se pueden ver, pero si inspeccionamos el código só los podemos ver. No usamos tipo TEXT porque los usarios pueden modificarlo. -->

                    <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form>

                    <a href="/admin/propiedades/actualizar-vendedor.php?id=<?php echo $propiedadVendedores['id']; ?>" class="boton-amarillo-block">Actualizar</a> <!-- Con este QueryString podremos mostrar por url el id de la propiedad a actualizar y esto nos ayudará a traernos la info de cada propiedad. -->
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="/admin\propiedades\crear-vendedor.php" class="boton boton-verde">Nuevo vendedor</a>
</main>

<?php
    //* 5. Cerrar la conexión:
    mysqli_close($DB);
    incluirTemplate('footer');
?>