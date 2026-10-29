<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
fwrite(STDERR, "Usuario: ");
$usuario = trim(fgets(STDIN));
fwrite(STDERR, "Contraseña: ");
$clave = trim(fgets(STDIN));
echo $usuario . ':' . password_hash($clave, PASSWORD_DEFAULT) . "\n";