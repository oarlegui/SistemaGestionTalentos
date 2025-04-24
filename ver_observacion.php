<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require_once "config.php";

$observacion = $_GET['id'];
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
    <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">
	
    <link rel="stylesheet" href="assets/css/notif.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bm/jq-3.6.0/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/datatables.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bm/jq-3.6.0/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@creativebulma/bulma-tooltip@1.2.0/dist/bulma-tooltip.min.css">

    <script src="assets/js/notif.js" type="module"></script>	
    <script type="text/javascript" class="init">

<?php
$FechayHora = date('d-m-Y H:i:s', time());  
?>

    $(document).ready(function() {
        var table = $('#example').DataTable( {
            searching: false,
            responsive: false,
            autoWidth: true,
            paging: false,

            //order: [[2, 'desc']],


            "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              //$(nRow).children("td").css("max-width", "250px");
              //$(nRow).children("td").css("overflow", "hidden");
              //$(nRow).children("td").css("white-space", "nowrap");
              //$(nRow).children("td").css("text-overflow", "ellipsis");
            },



            //Declaro ruta del archivo de lenguaje español
            language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},
            buttons: [
                {
                extend: 'copy',
                text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Copiar Tabla"><i class="fas fa-copy"></i></span>'},
                {
                    //TODO: Obtener datos de la observacion que sean utiles para el resumen e impresión
                    extend: 'excel',
                    filename: '<?php echo 'Observación - '.get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"])).' - '.date('Y-m-d_His')?>',
                    sheetName: 'Observación',
                    messageTop: 'Observaciones para el usuario <?php echo get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"]));?>',
                    messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Descargar Excel"><i class="fas fa-file-excel"></i></span>'},
                {
                    extend: 'pdf',
                    filename: '<?php echo 'Observación - '.get_nombre_from_funcionario(get_rut_from_funcionario(get_dato_observacion($observacion, 'OBS_FUN_OBSERVADO'))).' - '.date('Y-m-d_His')?>',
                    pageSize: 'LETTER',
                    messageTop: 'Observación a <?php echo get_nombre_from_funcionario(get_rut_from_funcionario(get_dato_observacion($observacion, 'OBS_FUN_OBSERVADO')));?>',
                    messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Descargar PDF"><i class="fas fa-file-pdf"></i></span>'},
                {
                    extend: 'colvis',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Visualizar Columnas"><i class="fas fa-eye"></i>&nbsp;</span>'},
                    ],

            //Hack feo para el lenguaje, debo crear los botones 10 ms despues de traducirlos
            initComplete: function(){
                setTimeout(function(){
                    table.buttons().container()
                    .appendTo( $('div.column.is-half', table.table().container()).eq(0) );

                }, 10);
                //Hack feo para corregir tamaño de botones, obviamente, despues de traducirlos.. por eso 20 ms.
                setTimeout(function(){
                    //$('button').addClass( "is-small" );
                    $('button').css({ "min-width": 50});
                    $('.is-light').addClass('is-link').removeClass('is-light');

                    /*$('td').css("max-width", "100px");
                    $('td').css("overflow", "hidden");
                    $('td').css("white-space", "nowrap");
                    $('td').css("text-overflow", "ellipsis");*/

                }, 20);
            }
        } );
    } );
    </script>


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

<style>
    @media print{
      .noprint{
          display:none;
      }
    }
</style>

</head>
<body class="has-navbar-fixed-top">
<?php
include "navbar.php";
?>
<?php
$sucursal = get_sucursal_from_funcionario($_SESSION["username"]);
$observado = get_nombre_from_funcionario(get_dato_observacion($observacion, 'OBS_FUN_OBSERVADO'));
$evaluador = get_nombre_from_funcionario(get_dato_observacion($observacion, 'FUN_RUN'));

$curso_obs = get_curso_nom(get_dato_observacion($observacion, 'OBS_CURSO'), NULL);
$asignatura_obs = get_asignatura(get_dato_observacion($observacion, 'OBS_ASIGNATURA'), NULL);

$fecha_obs = get_dato_observacion($observacion, 'OBS_FECHA');
$fecha_obs = date("d-m-Y", strtotime($fecha_obs)); 

$hora_obs = get_dato_observacion($observacion, 'OBS_HORA_INICIO');
$nota_obs = number_format(get_dato_observacion($observacion, 'OBS_NOTA'),2);
$momento = get_dato_observacion($observacion, 'OBS_MOMENTO');
?>


<div class="box">
<div class="content">
<div class="columns">
  <div class="column is-size-3 has-background-info-light has-text-weight-bold has-text-left">
  <i class="fa-solid fa-user"></i><?php echo " ".$observado;?>
  </div>
  <div class="column is-size-3 has-background-info has-text-white-bis has-text-weight-bold has-text-right">
  <i class="fa-solid fa-file-circle-check"></i><?php echo " ".$nota_obs;?>
  </div>
</div>

<?php
function get_momento($momento)
{
  switch ($momento) {
    case 1:
      echo 'Inicio';
      break;
    case 2:
      echo 'Desarrollo';
      break;
    case 3:
      echo 'Inicio y Desarrollo';
      break;
    case 4:
      echo 'Final';
      break;    
    case 5:
      echo 'Inicio y Final';
      break;  
    case 6:
      echo 'Desarrollo y Final';
      break;  
    case 7:
      echo 'Totalidad';
      break;  
    default:
      echo 'Totalidad';
  }
}
?>


<form action="#" method="post">
  <p>Esta observación fue realizada el <strong><?php echo $fecha_obs;?></strong> a las <strong><?php echo $hora_obs;?></strong> para la asignatura de <strong><?php echo $asignatura_obs;?></strong> durante el <strong><?php get_momento($momento);?></strong> de la clase, en el curso <strong><?php echo $curso_obs;?></strong></p>


<h5>Niveles</h5>
<div class="content is-small">
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
echo "<div class='table-container'>";
get_detalle_observacion($observacion);

echo '</table>';
echo '</div>';
echo '</div>';
?>
<div class="content">
	<div class="field">
		<label class="label"><i class="fa-solid fa-handshake"></i> Observaciones y Acuerdos</label>
		
    <div class="control">
			<!-- nl2br convierte /n en el string de la bd en <br> validos para HTML -->
			<!-- FIX: Hay errores UTF-8 en esta conversion. REVISAR! -->
      <?php echo nl2br(get_dato_observacion($observacion, 'OBS_COMENTARIO'));?>

		</div>
	</div>
  <div class="content">
	<div class="field">
		<label class="label"><i class="fa-solid fa-signature"></i> Firmas</label>
    <div class="columns">
  <div class="column">
    <hr>
  <?php echo " ".$observado;?>
  </div>
  <div class="column">
    <hr>
    <?php echo " ".$evaluador;?>
  </div>    
</div>		
    <div class="control">
			
      

		</div>
	</div>


<div class="field is-grouped is-grouped-center">
  <p class="control noprint">
  <a href="javascript:window.print()" class="button is-success is-outlined">
      Imprimir
    </a>    

  </p>
  <p class="control noprint">
    <a href="javascript:history.back()" class="button is-info is-outlined">
      Volver
    </a>
  </p>
</div>
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
