<?php
session_start();
//require __DIR__ . '/rutas.php';   // define DIR_IMATGES
require_once __DIR__ . '/includes/config.php';   // define DIR_IMATGES
$loguinat = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
$errormsg = $_SESSION['app_error'] ?? '';
unset($_SESSION['app_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Rebost</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; }
    nav button { padding: 8px 16px; }
    .panel { display: none; border: 1px solid #ccc; padding: 15px; margin-top: 10px; }
    table { width: 100%; border-collapse: collapse; }
    td, th { border-bottom: 1px solid #ddd; padding: 6px; text-align: left; }
    progress { width: 100%; }
    .error { color: red; }


    /* CAPÇALERA */
    .cap { display: flex; align-items: center; gap: 15px; padding: 12px 20px;
           background: #7a4b2a; color: #fff; border-radius: 8px; }
    .cap-logo { width: 64px; height: 64px; }
    .cap-text h1 { margin: 0; font-size: 2rem; }
    .cap-text p { margin: 0; opacity: .85; }
    .cap-usuari { margin-left: auto; text-align: right; font-size: .9rem; }
    .cap-usuari a { color: #f2cc8f; }

  </style>
</head>
<body>
  <!-- CAPÇALERA amb logo -->
  <header class="cap">
    <img src="img/rebost.svg" alt="Logo de Rebost" class="cap-logo">
    <div class="cap-text">
      <h1>Rebost</h1>
      <p>Imatges de màquines Vagrant</p>
    </div>
    <?php if ($loguinat): ?>
      <div class="cap-usuari">
        Usuari: <strong><?= htmlspecialchars($_SESSION['usuari']) ?></strong><br>
        <a href="logout.php">Tanca sessió</a>
      </div>
    <?php endif; ?>
  </header>
  <!-- Principal -->
  <nav>
    <button onclick="mostrarPestana('login')">Login</button>
    <?php if ($loguinat): ?>
      <button onclick="mostrarPestana('pujar')">Pujar imatges</button>
    <?php endif; ?>
      <button onclick="mostrarPestana('llistat')">Llistat</button>
  </nav>

  <!-- Pestanya login -->
  <div id="login" class="panel">
    <?php if ($loguinat): ?>
      <p>Ja estàs connectat.</p>
    <?php else: ?>
      <?php if ($errormsg !== ''): ?>
        <p class="error"><?php echo $errormsg ?></p>
      <?php endif; ?>
      <form action="login.php" method="post">
        <p>Usuari: <input type="text" name="usuari" required></p>
        <p>Contrasenya: <input type="password" name="password" required></p>
        <button type="submit">Entra</button>
      </form>
    <?php endif; ?>
  </div>

  <?php if ($loguinat): ?>
  <!-- PESTANYA Pujar -->
  <div id="pujar" class="panel">
    <h2>Pujar una imatge</h2>
    <input type="file" id="arxiu">
    <button onclick="pujarImatge()">Pujar</button>
    <progress id="barra" value="0" max="100"></progress>
    <p id="missatge"></p>
  </div>
  <?php endif; ?>

  <!-- Pestanya llistat: PHP llegeix la carpeta i pintauna fila per fitxer -->
  <div id="llistat" class="panel">
    <h2>Imatges disponibles</h2>
    <table>
      <tr><th>Fitxers</th><th>Tamany</th><th>Data</th><th></th></tr>
      <?php
      $path = DIR_IMATGES;
      $arxius = array_diff(scandir($path), ['.', '..']);
      foreach ($arxius as $nom):
          $ruta = "$path/$nom";
          if (!is_file($ruta)) continue;
      ?>
        <tr>
          <td><?= htmlspecialchars($nom) ?></td>
          <td><?= round(filesize($ruta) / 1048576, 1) ?> MB</td>
          <td><?= date('d/m/Y H:i', filemtime($ruta)) ?></td>
          <td><a href="descarregar.php?arxiu=<?= urlencode($nom) ?>">Descarregar</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <script src="pujar.js"></script>
  <script>
    // Al cargar la página: si hay sesión mostramos el listado, si no, el login
    mostrarPestana('<?= $loguinat ? "llistat" : "login" ?>');
  </script>
</body>
</html>
