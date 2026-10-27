<?php
session_start();
require_once __DIR__ . '/includes/config.php';   // define DIR_IMATGES

$carpeta = DIR_IMATGES;
$nom  = basename($_GET['arxiu'] ?? '');
$path    = "$carpeta/$nom";

if ($nom === '' || !is_file($path)) {
    http_response_code(404);
    exit('Arxiu no trobat');
}

set_time_limit(0);                       // traiem límit de temp
while (ob_get_level()) { ob_end_clean(); }   // evita que PHP guarde el archivo entero en memoria

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $nom . '"');
header('Content-Length: ' . filesize($path));
readfile($path);
