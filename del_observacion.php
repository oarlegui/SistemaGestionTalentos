<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require_once "config.php";

if (isset($_GET['id'])) {

  $id_coded = $_GET['id'];
  $id_decoded = base64_decode($id_coded);
  del_observacion($id_decoded);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Gestión de Talentos</title>
    <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<div class="box">
  <div class="container">
    <div class="notification is-primary">
      Se ha borrado la Observación
    </div>
    <p class="control">
      <a href="javascript:history.back()" class="button is-info is-outlined">
        Volver
      </a>
    </p>
  </div>
</div>
</body>
</html>