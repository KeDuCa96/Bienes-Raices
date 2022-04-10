<?php

require 'app.php'; //* Exportamos o llamamos app.php 
                        /* Usamos un poco de typescript para buenas practicas en el codigo */
function incluirTemplate(string  $nombre, bool $inicio = false ){  
    include TEMPLATES_URL."/${nombre}.php";    //* Llamamos la constante definidad en app.php. Recordar que la constante trae la dirección que encontró php con la ayuda de __DIR__
}


function limitar_cadena($cadena, $limite, $sufijo){
    // Si la longitud es mayor que el límite...
    if(strlen($cadena) > $limite){
        // Entonces corta la cadena y ponle el sufijo
        return substr($cadena, 0, $limite) . $sufijo;
    }
    
    // Si no, entonces devuelve la cadena normal
    return $cadena;
}

function siAutenticado() : string{
    session_start();

    $autenticacion = $_SESSION['login']; //* Como esta definido como true sí existe.
    $rol = $_SESSION['rol'];

/*     var_dump($_SESSION); */

    if($autenticacion === true && $rol === "0"){ 
        return "1";
    }
    if($autenticacion === true && $rol === "1"){
        return "2";
    }
    return "0";
}