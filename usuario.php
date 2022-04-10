<?php
//! Este archivo se debe eliminar antes de envíar a producción.

//* 1. Importar la conexión.

require 'includes/config/database.php';
$DB = DB();

//* 2. Creamos un e-mail and password
$email = 'superadmin@gmail.com';
$password = '1234';
$rol = 1;

//? HASHEAMOS EL PASSWORD.

$passwordHash = password_hash($password, PASSWORD_BCRYPT); //* Con esta función podremos hashear nuestra contraseña y de esta forma evitar intentos de robo o ataques a la web. Siempre se debe crear el pash con un CHAR(60). PASSWORD_DEFAULT también es valido como segundo parametro.


//* 3. Query para crear el usuario
$query = "INSERT INTO usuarios (email, password, rol) VALUES ( '${email}', '${passwordHash}', ${rol}); ";

//* 4. Agregar a la DB.

mysqli_query($DB, $query);