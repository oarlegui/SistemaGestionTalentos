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

<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="styles.css">
  
  </head>
  <body>
  <?php include_once "navbar.php";?>
<?php
$nombre= getFullname($_SESSION["username"]);
$correo = $_SESSION["username"];
$rut = get_rut_from_correo($correo);
$tipo = get_tipo_from_rut($rut);
$codigo = get_codigo_from_tipo($tipo);
$_SESSION["rut"] = $rut;
$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$_SESSION["full_rut"] = $rut.dv($rut);

$full_rut = $rut.dv($rut);

if(esEvaluador($full_rut) == 1)
{
  //autorizado
}
else
{
  echo "<div class='container my-5'>";
  muestraAlerta("<i class='bi bi-exclamation-circle-fill'></i> Usted <strong>no está autorizado</strong> para acceder a esta función", "danger");

  echo "<a href='javascript:history.back()'><button type='button' class='btn btn-secondary'><i class='bi bi-arrow-return-left'></i> Regresar</button></a>";
  echo "</div>";
  exit;
}
?>
    <div class="container my-5">
      <h4><?php echo $nombre;?></h4>
      <p>A continuación, la lista de funcionarios que Ud. debe evaluar:</p>
      <div class="col-lg-0 px-0">
      <?php getEvaluados($full_rut);?>
      </div>
      <div class="container my-5">
    

      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="main.js"></script>
    <script>
      /*Enable ToolTips*/
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
  </body>
</html>
