<?php

function DB() : mysqli { //? Aclaramos el tipo de dato que se va a retornar. 
    $DB = mysqli_connect('localhost', 'root', '', 'bienes_raices'); //* los parametros que damos son : nombre del servidor, usuario, clave y nombre de la BD
    $DB->set_charset("utf8"); //* Para poder leer acentos, ñ, etc..

    //?  Verificamos si se conectó a la BD
    if(!$DB){
        echo "No se puedo conectar a la BD";
        exit;   //! EN CASO TAL NO SE CONECTE SE DETIENEN TODO EL CÓDGO
    }

    return $DB; //*  Retornamos para poder ejecutar cuando lo necesitemos. 
}