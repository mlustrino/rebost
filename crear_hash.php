<?php

fwrite(STDERR, "Usuario: ");
$usuario = trim(fgets(STDIN));
fwrite(STDERR, "Contraseña: ");
$clave = trim(fgets(STDIN));
echo $usuario . ':' . password_hash($clave, PASSWORD_DEFAULT) . "\n";