<?php
//TODO:  faltan NAME Y ID DE CADA CAMPO
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require_once "config.php";

$tipo_obs = 4; //PIE
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:s", time() - 3600); //Hora Actual menos 1 hora... por horario de invierno blah blah

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
$evaluador = get_rut_from_funcionario($_SESSION["username"]);
?>

<div class="box">
<div class="content">
  <h1>Pauta de Acompañamiento de Clases</h1>
  <h2>Programa de Integración Escolar</h2>
<form action="guarda-observacion.php" method="post">
  <h3>Datos Generales</h3>
<div class="field is-horizontal">
  <div class="field-label is-normal">
    <label class="label">Profesional</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
        <div class="select is-small is-fullwidth">
          <select name='fun_id' id='fun_id'>
                <?php get_pie($sucursal, NULL); ?>
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
                <?php get_materias(NULL); ?>
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
            <option value='1'>PK-A</option>
            <option value='2'>PK-B</option>
            <option value='3'>PK-C</option>
            <option value='4'>K-A</option>
            <option value='5'>K-B</option>
            <option value='6'>K-C</option>
            <option value='7'>1-A</option>
            <option value='8'>1-B</option>
            <option value='9'>2-A</option>
            <option value='10'>2-B</option>
            <option value='11'>3-A</option>
            <option value='12'>3-B</option>
            <option value='13'>4-A</option>
            <option value='14'>4-B</option>
            <option value='15'>5-A</option>
            <option value='16'>5-B</option>
            <option value='17'>6-A</option>
            <option value='18'>6-B</option>
            <option value='19'>7-A</option>
            <option value='20'>7-B</option>
            <option value='21'>8-A</option>
            <option value='22'>8-B</option>
            <option value='23'>1EM-A</option>
            <option value='24'>1EM-B</option>
            <option value='25'>2EM-A</option>
            <option value='26'>2EM-B</option>
            <option value='27'>3EM-A</option>
            <option value='28'>3EM-B</option>
            <option value='29'>4EM-A</option>
            <option value='30'>4EM-B</option>
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
              <input value="<?php echo $fecha_actual; ?>" name="date" id="date" class="input is-small" type="date">
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
              <input value="<?php echo $hora_actual; ?>" name="horainicio" id="horainicio" class="input is-small" step="1" type="time">
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
              <input value="<?php echo date('H:i:s', (strtotime('+45 minutes')-3600)); ?>" name="horatermino" id="horatermino" class="input is-small" step="1" type="time">
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
            <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="1"> Inicio
            <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="2"> Desarrollo
            <input checked type="checkbox" onclick="sumalo()" id="m-val" name="m-val" value="4"> Final
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
$query = "SELECT eje.eje_id, eje.tipo_id, eje.eje_nombre, eje.eje_corto, dimension.dim_id, dimension.dim_texto FROM eje
INNER JOIN dimension ON eje.EJE_ID = dimension.EJE_ID
WHERE TIPO_ID = 4";

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
        $field4name = $row["dim_texto"];
		$dim_id = $row["dim_id"];
        echo '<tr> 
				  <td class="is-hidden-mobile"><abbr title="'.$eje_nombre.'"><span class="tag is-rounded is-info is-light">'.$eje_corto.'</span></abbr></td>
                  <td>'.$field4name.'</td> 
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
			<textarea id="observaciones" name="observaciones" class="textarea" placeholder="Use este espacio para ingresar observaciones y acuerdos de la evaluacion..." rows="5"></textarea>
		</div>
	</div>

<input style="display: none" size='5' type="text" name="sum" />
<input style="display: none" size='5' type="text" name="can" />
<input style="display: none" size='5' type="text" name="promedio" />


<div class="field is-grouped is-grouped-center">
  <p class="control">
  <input class="button is-vcentered is-info is-outlined" type="submit" value="Enviar">
  </p>
  <p class="control">
    <a class="button is-danger is-outlined">
      Cancelar
    </a>
  </p>
</div>

<input style="display: none" size='5' type="text" name="sum" />
<input style="display: none" size='5' type="text" name="can" />
<input style="display: none" size='5' type="text" name="promedio" />
<input style="display: none" size='5' type="text" name="tipo_obs" value="4" />

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
function radio_buttons($numero)
{
$radio = '
	<div class="control">
		<label class="radio"><input class="calc" required value="2" type="radio" id="'.$numero.'" name="'.$numero.'">
		2
		</label>
		<label class="radio"><input class="calc" checked value="1" required type="radio" id="'.$numero.'" name="'.$numero.'">
		1
		</label>
		<label class="radio"><input class="calc" required value="0" type="radio" id="'.$numero.'" name="'.$numero.'">
		0
		</label>
		<label class="radio"><input class="nomecalcules" required value="9" type="radio" id="'.$numero.'" name="'.$numero.'">
		🛇
		</label>
	</div>
';

  return $radio;
}
?>
