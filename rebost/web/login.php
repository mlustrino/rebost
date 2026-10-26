<?php
session_start();
require_once __DIR__ . '/includes/config.php'; // define paths

// només acceptem dades POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$usuari  = $_POST['usuari']  ?? '';
$password = $_POST['password'] ?? '';
$loginOK = false;


if (!file_exists(ARX_USUARIS)){
    error_log('login.php: '. ERR_FITXER_NO_TROBAT . ARX_USUARIS);
    $_SESSION['app_error'] = ERR_FITXER_NO_TROBAT;
    header('Location: index.php?error=1');
    exit;
    
}

$fUsers = fopen(ARX_USUARIS,"r");

if ($fUsers === false){
    error_log('login.php: '. ERR_FITXER_NO_OBRIR . ARX_USUARIS);
    $_SESSION['app_error'] = ERR_FITXER_NO_OBRIR;
    header('Location: index.php?error=1');
    exit;
}

while(! feof($fUsers)) {

    $getline = explode(':',trim(fgets($fUsers)));
    if (count($getline) !== 2) continue;

    list($regUser, $regPassHash) = $getline;
    $regPassHash = trim($regPassHash);

    if ($regUser === $usuari && password_verify($password, $regPassHash)) {
        $loginOK = true;
        break;
    }
}

fclose($fUsers);

if ($loginOK) {
    session_regenerate_id(true);       // Regenerar la sessió
    $_SESSION['usuari'] = $usuari;
    $_SESSION['logged']  = true;
    header('Location: index.php');
} else {
    error_log('login.php: '. ERR_LOGIN );
    $_SESSION['app_error'] = ERR_LOGIN;
    header('Location: index.php?error=1');
}
exit;
