<?php
// Rep cada part de l'arxiu cada vegada que el JS l'envia.
session_start();
require_once __DIR__ . '/includes/config.php'; // define paths DIR_IMATGES DIR_TEMP

// 1) Control sessió
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    http_response_code(401);
    exit('no_autenticat');
}

$usuari = $_SESSION['usuari'];
$destiTemp = DIR_TEMP;
$destiFinal    = DIR_IMATGES;

// 2) Rebrem dades del JS i construim el nom
$nom = basename($_POST['nom'] ?? '');
$nom = preg_replace('/[^A-Za-z0-9._-]/', '_', $nom);
$numero = (int)($_POST['numero'] ?? 0);          // Numero de la part.
$total  = (int)($_POST['total']  ?? 1);          // cuántos blocs hay en total

// 3) filtrant e básicas
$extension = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
if (!in_array($extension, EXTENSIONS_PERMITIDES)) {
    exit('extensio_no_permesa');
}
if (!isset($_FILES['bloc']) || $_FILES['bloc']['error'] !== UPLOAD_ERR_OK) {
    exit('error_al_rebre_bloc');
}

// 4) El nom serà usuari, més el nom del fitxer adjunt max_miMaquina.box
$arxiuTemp = "$destiTemp/{$usuari}_{$nom}.part";
$arxiuFinal    = "$destiFinal/{$usuari}_{$nom}";

/** 
 * Part 0: Inizialitzem l'arxiu ('wb') perquè el primer bloc és on crearem l'arxiu
 * Resta: append
 * */

$modo = ($numero === 0) ? 'wb' : 'ab';
$desti = fopen($arxiuTemp, $modo);
fwrite($desti, file_get_contents($_FILES['bloc']['tmp_name']));
fclose($desti);

// 6) Una vegada arribem a l'últim bloc, movem a la carpeta de imatges de vagrant.
if ($numero === $total - 1) {
    rename($arxiuTemp, $arxiuFinal);
}

echo 'ok';
