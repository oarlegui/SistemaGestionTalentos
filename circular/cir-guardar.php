<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "config.php";

$funcionario = $_GET["funcionario"];
$evaluador = $_GET["evaluador"];
$promedio = $_POST["promedio"];
$fecha = date('Y-m-d');

$funcionario = substr_replace($funcionario ,"", -1);
$evaluador = substr_replace($evaluador ,"", -1);


function ultimoID()
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    
    $query = "
    SELECT MAX(eva_det_id) as id
    FROM cir_eva_detalle
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            if ($row['id'] == NULL)
            {
                return '1';
            }
            else {
                return $row['id'];
            }
        }
    }
	$result->free();
}

function insertaDB($consulta)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = $consulta;
    $result = $mysqli->query($query);
    return $id = mysqli_insert_id($mysqli);
    $result->free();
}


$query1 = "INSERT INTO cir_evaluacion (eva_funcionario, eva_evaluado, eva_fecha, eva_nota) VALUES ('".$funcionario."', '".$evaluador."', '".$fecha."', $promedio);";
console_log($query1);

$last_id = insertaDB($query1);

for ($i = 1; $i <= 25; $i++)    
{
    if ($_POST[$i] == 0) { $_POST[$i] = 0;}  //Esto transformaba todos los 0 en NULL, evitaba guardar 0 en la BD, pero el calculo no se hace en la BD.
    $query2 = "INSERT INTO cir_eva_detalle (eva_id, dim_id, eva_det_valor) VALUES ($last_id, $i, $_POST[$i]);";
    console_log($query2);
	insertaDB($query2);
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    
<style>
:root {
  --bg: white;
  --color: black;
}
table tbody tr {
    background: var(--bg);
    color: var(--color);
    transition: .5s
}
table tbody tr:hover {
    background: #cfe2ff;
    color: black;

}  
</style>
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
            <a class="nav-link" href="index.php"><i class="bi bi-file-earmark-person-fill" style="font-size: 1.5rem;"></i> Evaluación Circular</a>
            </li>
          </ul>

          <div class="d-flex nav-item">
            <a class="nav-link text-primary" href="/talentos/portal.php"><i class="bi bi-house-up" style="font-size: 1.5rem;"></i> <strong>Portal Talentos</strong></a>
          </div>
        </div>
      </div>
    </nav>
    <div class="container my-5">
        <?php muestraAlerta("<i class='bi bi-check2-circle'></i> Evaluación Guardada", "success");?>
        <a href="../portal.php"><button type="button" class="btn btn-primary"><i class="bi bi-house-fill"></i> Portal</button></a>

    </div>
  </body>
</html>