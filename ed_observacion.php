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

}

$id_obs = $id_decoded;

$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:s", time()); //Hora Actual menos 1 hora... por horario de invierno blah blah

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
<body class="has-navbar-fixed-top">
<?php
include "navbar.php";
?>
<?php
$sucursal = get_sucursal_from_funcionario($_SESSION["username"]);
$tipo_pauta = get_tipo_from_obs($id_decoded);
$observado = get_dato_observacion($id_decoded, 'OBS_FUN_OBSERVADO');
$nota = get_dato_observacion($id_decoded, 'OBS_NOTA');
$materia = get_dato_observacion($id_decoded, 'OBS_ASIGNATURA');
$curso = get_dato_observacion($id_decoded, 'OBS_CURSO');
$fecha = get_dato_observacion($id_decoded, 'OBS_FECHA');
$hora_inicio = get_dato_observacion($id_decoded, 'OBS_HORA_INICIO');
$hora_fin = get_dato_observacion($id_decoded, 'OBS_HORA_FIN');
$evaluador = get_dato_observacion($id_decoded, 'FUN_RUN');
$comentario = get_dato_observacion($id_decoded, 'OBS_COMENTARIO');
$momento = get_dato_observacion($id_decoded, 'OBS_MOMENTO');
$num_pauta = get_pauta_from_obs($id_decoded);


?>

<div class="box">
<div class="content">
  <h1>Pauta de Acompañamiento de Clases</h1>
  <h2><?php echo $tipo_pauta;?></h2>
<form action="actualiza-observacion.php" method="post">
  <h3>Datos Generales</h3>
<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label"><?php echo $tipo_pauta;?></label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="select is-small is-fullwidth">
          <select name='fun_id' id='fun_id'>
          <?php
            if ($tipo_pauta == "Docente")
            {
              get_profesores($sucursal, $observado);
            } 
            elseif ($tipo_pauta == "PIE")
            {
              get_pie($sucursal, $observado);
            }
            elseif ($tipo_pauta == "Jefatura")
            {
              get_jefaturas($sucursal, $observado);
            }
            elseif ($tipo_pauta == "Asistente")
            {
              get_asistentes($sucursal, $observado);
            }   
          ?>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Asignatura</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="select is-small is-fullwidth">
        <select name='mat_id' id='mat_id'>
                <?php get_materias($materia); ?>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Curso</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="select is-small is-fullwidth">
          <select name="cur_id" id="cur_id">
            <?php get_curso($curso);?>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Fecha</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="field">
            <div class="control is-small">
              <input value="<?php echo $fecha; ?>" name="date" id="date" class="input is-small" type="date">
            </div>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Hora Inicio</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="field">
            <div class="control is-small">
              <input value="<?php echo $hora_inicio; ?>" name="horainicio" id="horainicio" class="input is-small" step="1" type="time">
            </div>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Hora Fin</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="field">
            <div class="control is-small">
              <input value="<?php echo $hora_fin; ?>" name="horatermino" id="horatermino" class="input is-small" step="1" type="time">
            </div>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Acompaña</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="select is-small is-fullwidth">
          <select name='eva_id' id='eva_id'>
            <?php get_evaluadores($sucursal, $evaluador);?>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Momento de la Clase 2023-04-17 Modifica a CheckBox-->
<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Momento</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="field is-grouped">
          <label class="is-size-6 checkbox">

          <?php
switch ($momento) {
  case 1:
    echo '
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;
  case 2:
    echo '
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;
  case 3:
    echo '
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;
  case 4:
    echo '
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;    
  case 5:
    echo '
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;  
  case 6:
    echo '
    <input type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;  
  case 7:
    echo '
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
    break;  
  default:
    echo '
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
    <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
    ';
}
?>
          </label>
          <input hidden value="7" readonly="readonly" type="text" id="mom_id" name="mom_id" />
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function sumalo() {
  var input = document.getElementsByName("m-val");
  var total = 0;
  for (var i = 0; i < input.length; i++) {
    if (input[i].checked) {
      total += parseFloat(input[i].value);
    }
  }
  document.getElementsByName("mom_id")[0].value = "" + total;
}
</script>

<h3>Niveles</h3>
  <table class='table is-fullwidth is-narrow is-hoverable'>
    <thead>
      <tr class='has-background-info-light'>
        <th>Indicador</th>
        <th>Descripción</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>🛇 No Aplica</td>
        <td>El indicador no es evaluado en el momento observado de la clase.</td>
      </tr>
      <tr>
        <td>(0) No Logrado</td>
        <td>El indicador no se ejecuta durante la clase.</td>
      </tr>
      <tr>
        <td>(1) Necesita Mejorar</td>
        <td>El indicador presenta aspectos a mejorar en su desempeño.</td>
      </tr>
      <tr>
        <td>(2) Logrado</td>
        <td>El indicador presenta un nivel de desempeño esperado.</td>
      </tr>
    </tbody>
  </table>
</div>

<?php
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
$mysqli->set_charset("utf8mb4");

$query = "
SELECT eje.eje_id, eje.tipo_id, eje.eje_nombre, eje.eje_corto, dimension.dim_id, dimension.dim_texto FROM eje
INNER JOIN dimension ON eje.EJE_ID = dimension.EJE_ID
WHERE TIPO_ID = '".$num_pauta."'";


echo "<div class='table-container'>";
echo "<table class='table is-fullwidth is-narrow is-hoverable'> 
        <thead>
            <tr class='has-background-info-dark has-text-white'> 
                <th class='is-hidden-mobile has-text-white has-text-centered'>Eje</th> 
                <th class='has-text-white has-text-centered'>Dimensión</th> 
				<th style='width:50%' class='has-text-white has-text-centered'>Evaluación</th> 
            </tr>
        </thead>";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {

    $eje_nombre = $row["eje_nombre"];
		$eje_corto = strtoupper(substr($row["eje_corto"],0,3));
    $dim_texto = $row["dim_texto"];
		$dim_id = $row["dim_id"];
        echo '<tr> 
				  <td class="is-hidden-mobile"><abbr title="'.$eje_nombre.'"><span class="tag is-rounded is-info is-light">'.$eje_corto.'</span></abbr></td>
                  <td>'.$dim_texto.'</td> 
				  <td class="has-text-centered">'.radio_buttons($dim_id).'</td> 
              </tr>';
    }
	$result->free();
	echo '</table>';
	echo '</div>';
	echo '</div>';
} 
?>
<div class="box">
	<div class="field">
		<label class="label">Observaciones y Acuerdos</label>
		<div class="control">
			<textarea id="observaciones" name="observaciones" class="textarea" rows="5"><?php echo $comentario;?></textarea>
		</div>
	</div>

<!--<input style="display: none" size='5' type="text" name="sum" />
<input style="display: none" size='5' type="text" name="can" />
<input style="display: none" size='5' type="text" name="promedio" />-->


<div class="field is-grouped is-grouped-center">
  <p class="control">
  <input class="button is-vcentered is-info is-outlined" type="submit" value="Actualizar">
  </p>
  <p class="control">
    <a class="button is-danger is-outlined">
      Cancelar
    </a>
  </p>
</div>

<input style="display: none" size='5' type="text" name="id"  value="<?php echo $id_obs;?>" />
<input style="display: none" size='5' type="text" name="sum" />
<input style="display: none" size='5' type="text" name="can" />

<input style="display: none" size='5' type="text" name="promedio" value="<?php echo $nota;?>"/>


<input style="display: none" size='5' type="text" name="tipo_obs" value="<?php echo $num_pauta;?>" />

</div>



</body>
</html>
<script type='text/javascript'>
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

<script type="text/javascript">

function precise_round(num,decimals)
{
  return Math.round(num*Math.pow(10,decimals))/Math.pow(10,decimals);
}

function calcscore(arg){
  var score = 0;
  $(".promedio").fadeOut(50);
  $(".promedio").fadeIn(250);

  $(".calc:checked").each(function(){
      score+=parseInt($(this).val(),10);
  });

  var promedio = score / arg;
  promedio = promedio.toFixed(2);

  $("input[name=sum]").val(score)
  $("input[name=can]").val(arg)
  $(".promedio").text(promedio)
  $("input[name=promedio]").val(promedio)
}

$().ready(function()
{
//Al elegir como respuesta 0, volver a calcular sin considerar el 0
$('.nomecalcules').change(function(){
  var cantidad = $('.calc:checked').length
  calcscore(parseInt(cantidad));
});

//Calcular promedio segun la cantidad de radios 'calc'
$('.calc').change(function(){
var cantidad = $('.calc:checked').length
calcscore(parseInt(cantidad));
});
});
</script>

<?php 

/**
 * Obtiene valor de observacion y genera correspondiente radio button
 * @param  int  $obs_id Valor Observado
 * @param  int  $dim_id Dim Observada
 * @param  int  $opcion Opcion Marcada
 * @return boolean         True/False para input checked
 */
function isChecked($obs_id, $dim_id, $opcion)
{

  $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
  $mysqli->set_charset("utf8mb4");
  $query = "
  SELECT ode_valor, ode_id
  FROM observacion_detalle
  INNER JOIN observacion ON observacion.OBS_ID = observacion_detalle.OBS_ID
  WHERE observacion.OBS_ID = ".$obs_id." AND DIM_ID = ".$dim_id."
  ";
  $result = mysqli_query($mysqli, $query);
  $result_array = mysqli_fetch_array($result, MYSQLI_ASSOC);

  if ($opcion == $result_array['ode_valor'])
  {
    return "checked = 'checked' name = '".$result_array['ode_id']."'";
  }
  else
  {
    return "                    name = '".$result_array['ode_id']."'";
  }
}

function radio_buttons($numero)
{


  if (isset($_GET['id'])) {

    $id_coded = $_GET['id'];
    $id_decoded = base64_decode($id_coded);
  
  }

$radio = '
	<div class="control">
		<label class="radio"><input class="calc" '.isChecked($id_decoded, $numero, 2).' required value="2" type="radio" id="'.$numero.'" name="'.$numero.'">
    2
    </label>
		<label class="radio"><input class="calc"  '.isChecked($id_decoded, $numero, 1).' required value="1"  type="radio" id="'.$numero.'" name="'.$numero.'">
		1
		</label>
		<label class="radio"><input class="calc"  '.isChecked($id_decoded, $numero, 0).' required value="0" type="radio" id="'.$numero.'" name="'.$numero.'">
		0
		</label>
		<label class="radio"><input class="nomecalcules"  '.isChecked($id_decoded, $numero, 9).' required value="9" type="radio" id="'.$numero.'" name="'.$numero.'">
		🛇
		</label>
	</div>
';

  return $radio;
}
?>
