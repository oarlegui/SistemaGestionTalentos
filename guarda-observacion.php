<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Gestión de Talentos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">
  
	<script>
		document.addEventListener('DOMContentLoaded', () => {
	
		// Get all "navbar-burger" elements
		const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
	
		// Check if there are any navbar burgers
		if ($navbarBurgers.length > 0) {
	
			// Add a click event on each of them
			$navbarBurgers.forEach( el => {
			el.addEventListener('click', () => {
	
				// Get the target from the "data-target" attribute
				const target = el.dataset.target;
				const $target = document.getElementById(target);
	
				// Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
				el.classList.toggle('is-active');
				$target.classList.toggle('is-active');
	
			});
			});
		}
	
		});
	</script>

</head>

<?php

require_once "config.php";
include "console_log.php";



$acuerdos		   = $_POST["observaciones"];
$momento           = $_POST["mom_id"];
if ($_POST["promedio"] == NULL) { $_POST["promedio"] = 1.0;}
if ($_POST["promedio"] == 'NaN') { $_POST["promedio"] = 1.0;}
$nota              = $_POST["promedio"];
$obs_curso         = $_POST["cur_id"];
$obs_asignatura    = $_POST["mat_id"];
$fun_run           = $_POST["eva_id"];
$obs_fun_observado = $_POST["fun_id"];
$obs_fecha         = $_POST["date"];
$obs_hora_inicio   = $_POST["horainicio"];
$obs_hora_fin      = $_POST["horatermino"];
$obs_tipo          = $_POST["tipo_obs"];



$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
$mysqli->set_charset("utf8mb4");
$acuerdos = $mysqli->real_escape_string($acuerdos);


$sql = "INSERT INTO observacion (FUN_RUN, OBS_FUN_OBSERVADO, OBS_FECHA, OBS_HORA_INICIO, OBS_HORA_FIN, OBS_CURSO, OBS_ASIGNATURA, OBS_NOTA, OBS_MOMENTO, OBS_COMENTARIO) VALUES ('".$fun_run."', '".$obs_fun_observado."', '".$obs_fecha."', '".$obs_hora_inicio."', '".$obs_hora_fin."', $obs_curso, $obs_asignatura, $nota, $momento, '".$acuerdos."');";

if ($mysqli->query($sql))
{
    console_log("Observacion Ingresada");
}
if ($mysqli->errno)
{
    console_log("No se pudo Registrar la Observacion a la tabla: %s ----- ", $mysqli->error);
}

$last_id = $mysqli -> insert_id;
$primera_dim = get_first_dim($obs_tipo);
$cantidad_dim = get_count_dim($obs_tipo);


for ($i = $primera_dim; $i <= ($primera_dim + $cantidad_dim - 1); $i++)    
{
    $sql2 = "INSERT INTO observacion_detalle (OBS_ID, DIM_ID, ODE_VALOR) VALUES ($last_id, $i, $_POST[$i]);";
	$mysqli->query($sql2);
    console_log("Observacion_Detalle Ingresada");
}
$mysqli->close();
?>
<body>
<div class="box">

<article class="message is-success">
  <div class="message-body">
    Con fecha <strong><?php echo $obs_fecha; ?></strong> se ha registrado correctamente la observación al funcionario <strong><?php echo get_nombre_from_funcionario($obs_fun_observado); ?>.</strong>   La nota obtenida es <strong><?php echo $nota; ?></strong>
  </div>
</article>

<!---boton--->
<button onclick="window.location='mi_avance.php';" class="button is-outline is-success">
			<span class="icon is-small">
			  <i class="fas fa-home"></i>
			</span>
			<span>Ver Mis Observaciones</span>
</button>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
    const $notification = $delete.parentNode;

    $delete.addEventListener('click', () => {
      $notification.parentNode.removeChild($notification);
    });
  });
});
</script>
</body>
</html>
