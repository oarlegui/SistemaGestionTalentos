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
$nota              = $_POST["promedio"];
$obs_curso         = $_POST["cur_id"];
$obs_asignatura    = $_POST["mat_id"];
$fun_run           = $_POST["eva_id"];
$obs_fun_observado = $_POST["fun_id"];
$obs_fecha         = $_POST["date"];
$obs_hora_inicio   = $_POST["horainicio"];
$obs_hora_fin      = $_POST["horatermino"];
$obs_tipo          = $_POST["tipo_obs"];
$obs_momento	   = $_POST["mom_id"];
$obs_nota		   = $_POST["promedio"];
$obs_id 		   = $_POST["id"];


$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 

$acuerdos = $mysqli->real_escape_string($acuerdos);
//var_dump($_POST);
$sql = "
UPDATE observacion
SET FUN_RUN='".$fun_run."',
OBS_FUN_OBSERVADO='".$obs_fun_observado."',
OBS_FECHA='".$obs_fecha."',
OBS_HORA_INICIO='".$obs_hora_inicio."',
OBS_HORA_FIN='".$obs_hora_fin."',
OBS_CURSO='".$obs_curso."',
OBS_ASIGNATURA='".$obs_asignatura."',
OBS_NOTA='".$obs_nota."',
OBS_MOMENTO='".$obs_momento."',
OBS_COMENTARIO='".$acuerdos."'
WHERE OBS_ID='".$obs_id."';
";

if ($mysqli->query($sql))
{
    console_log("Observacion Ingresada");
}
if ($mysqli->errno)
{
    console_log("No se pudo Registrar la Observacion a la tabla: %s ----- ", $mysqli->error);
}

$last_id = $obs_id;
$primera_dim = get_first_dim($obs_tipo);
$cantidad_dim = get_count_dim($obs_tipo);

foreach ($_POST as $id => $val)
{
	if (is_int($id) === FALSE )
	{
		console_log($id." Skip");
	}
	else
	{
		$sql2 =
		'
		UPDATE observacion_detalle
		SET ODE_VALOR = '.$val.'
		WHERE ODE_ID = '.$id.'
		';
		$mysqli->query($sql2);
		console_log("Observacion_Detalle Ingresada");
	}
}

$mysqli->close();
?>
<body>
<div class="box">

<article class="message is-success">
  <div class="message-body">
    Con fecha <strong><?php echo $obs_fecha; ?></strong> se ha actualizado correctamente la observación al funcionario <strong><?php echo get_nombre_from_funcionario($obs_fun_observado); ?>.</strong>   La nota obtenida es <strong><?php echo $nota; ?></strong>
  </div>
</article>

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