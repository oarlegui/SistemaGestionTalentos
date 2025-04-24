<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "config.php";
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <!--<link rel="stylesheet" href="styles.css">-->
  </head>
  <body>

 
    <nav class="navbar navbar-expand-lg" style="background-color: #ffffff;">
      <div class="container">
        <a class="navbar-brand" href="index.php"><img alt="CES-Logo" src="assets/img/ces-logo.png" width="112" height="28"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-file-earmark-person-fill" style="font-size: 1.5rem;"></i> Mi AutoEvaluación</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="evaluaciones.php"><i class="bi bi-file-earmark-check-fill" style="font-size: 1.5rem;"></i> Mis Evaluaciones</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="comite.php"><i class="bi bi-person-check-fill" style="font-size: 1.5rem;"></i> Comité</a>
            </li>            
          </ul>

          <div class="d-flex nav-item">
            <a class="nav-link text-primary" href="/talentos/portal.php"><i class="bi bi-house-up" style="font-size: 1.5rem;"></i> <strong>Portal Talentos</strong></a>
          </div>
        </div>
      </div>
    </nav>
<?php
$nombre= get_nombres_from_correo($_SESSION["username"]);
$correo = $_SESSION["username"];
$rut = get_rut_from_correo($correo);
$tipo = get_tipo_from_rut($rut);
$codigo = get_codigo_from_tipo($tipo);
$_SESSION["rut"] = $rut;
$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$full_rut = $rut.dv($rut);
$siguiente_año = (date('Y')+1);
$este_mes = date('Y-m-01');
$prox_año = date('Y-12-31', strtotime('+1 year'));
?>

<div class="container my-5 text-center">
  <div class="row align-items-top">    
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i><strong> ¡Está acción no es reversible!</strong> ¿Desea borrar definitivamente su AutoEvaluación?
    </div>
    <div class="container my-1 text-start">  
    
    <a href="aeva-borrar-conf.php?id=<?php echo $full_rut; ?>"><button type="button" class="btn btn-danger"><i class="bi bi-trash-fill"></i> Borrar</button></a>
    <a href="javascript:history.back()"><button type="button" class="btn btn-secondary"><i class="bi bi-arrow-return-left"></i> Cancelar</button></a>
    </div>    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="main.js"></script>
</body>
</html>