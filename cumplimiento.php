<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require_once "config.php";

$agno_actual = date('Y');
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:s", time() - 3600); //Hora Actual menos 1 hora... por horario de invierno blah blah, se va al carajo en localhost
$FechayHora = date('d-m-Y H:i:s', time());

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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bm/jq-3.6.0/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/datatables.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bm/jq-3.6.0/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@creativebulma/bulma-tooltip@1.2.0/dist/bulma-tooltip.min.css">

    <!-- Ventanas Modales con FX -->
    <!--<link rel="stylesheet" href="https://unpkg.com/bulma-modal-fx/dist/css/modal-fx.min.css" />-->

    <script type="text/javascript" class="init">

    $(document).ready(function() {
        var table = $('#example').DataTable( {
            responsive: false,
            autoWidth: true,
            paging: false,

            order: [[3, 'desc']],


            "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).children("td").css("max-width", "50px");
              $(nRow).children("td").css("overflow", "hidden");
              $(nRow).children("td").css("white-space", "nowrap");
              $(nRow).children("td").css("text-overflow", "ellipsis");
            },



            //Declaro ruta del archivo de lenguaje español
            language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},
            buttons: [
                {
                extend: 'copy',
                text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Copiar Tabla"><i class="fas fa-copy"></i></span>'},
                {
                    extend: 'excel',
                    filename: '<?php echo 'Cumplimiento - '.get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"])).' - '.date('Y-m-d_His')?>',
                    sheetName: 'Cumplimiento',
                    messageTop: 'Cumplimiento de Observaciones para el usuario <?php echo get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"]));?>',
                    messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Descargar Excel"><i class="fas fa-file-excel"></i></span>'},
                {
                    extend: 'pdf',
                    filename: '<?php echo 'Cumplimiento - '.get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"])).' - '.date('Y-m-d_His')?>',
                    pageSize: 'LETTER',
                    messageTop: 'Cumplimiento de Observaciones para el usuario <?php echo get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"]));?>',
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
</head>

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
$rut = get_rut_from_funcionario($_SESSION["username"]);
?>
<div class="box">
	<div class="content">
		<h1 class="title">Avance - 2025</h1>
    <h2 class="subtitle has-text-weight-light">%Total de ambos Colegios</h2>
		<div class="columns">
			<div class="column">
      <div id="chart1" style="height: 370px; width: 100%;"></div>
      </div>
			<div class="column">
      <div id="chart2" style="height: 370px; width: 100%;"></div>
      </div>
			<div class="column">
      <div id="chart3" style="height: 370px; width: 100%;"></div>
      </div>
			<div class="column">
      <div id="chart4" style="height: 370px; width: 100%;"></div>
      </div>
		</div>
	</div>
</div>
<div class="box">
<div class="content">
  <h1 class="title">Cumplimiento de Observaciones - 2025</h1>
  <h2 class="subtitle has-text-weight-light"></h2>
  
<!-- TABLA DE CUMPLIMIENTO -->
<!-- TABLA DE CUMPLIMIENTO -->
<!-- TABLA DE CUMPLIMIENTO -->

  <?php get_cumplimiento($rut); ?>
  </div>
  </div>



</body>
</html>
<?php
$evaluadosPIE = FuncEvalPIE(1, $agno_actual) + FuncEvalPIE(2, $agno_actual);
$funcionariosPIE = FuncTotalPIE(1) + FuncTotalPIE(2);

$evaluadosDocente = FuncEvalDocente(1, $agno_actual) + FuncEvalDocente(2, $agno_actual);
$funcionariosDocente = FuncTotalDocente(1) + FuncTotalDocente(2);

$evaluadosAsistente = FuncEvalAsistente(1, $agno_actual) + FuncEvalAsistente(2, $agno_actual);
$funcionariosAsistente = FuncTotalAsistente(1) + FuncTotalAsistente(2);

$evaluadosJefatura = FuncEvalJefatura(1, $agno_actual) + FuncEvalJefatura(2, $agno_actual);
$funcionariosJefatura = FuncTotalJefatura(1) + FuncTotalJefatura(2);


?>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<script>
window.onload = function() {


var chart1 = new CanvasJS.Chart("chart1", {
	animationEnabled: true,
	title: {
		text: "Docentes",
    fontFamily: "BlinkMacSystemFont,-apple-system,Roboto,Oxygen,Ubuntu,Cantarell,Helvetica,Arial,sans-serif"
    
	},
	data: [{
		type: "doughnut",
    innerRadius: "50%",
		startAngle: 200,
		yValueFormatString: "##0.0\"%\"",
		indexLabel: "{label} {y}",
		dataPoints: [
			{y: <?php echo (($evaluadosDocente*100)/$funcionariosDocente); ?>, label: "Evaluados", color: "#485FC7"},
			{y: <?php echo 100-(($evaluadosDocente*100)/$funcionariosDocente); ?>, label: "No Evaluados", color: "#EFEDFF"}
		]
	}]
});

var chart2 = new CanvasJS.Chart("chart2", {
	animationEnabled: true,
	title: {
		text: "Asistentes",
    fontFamily: "BlinkMacSystemFont,-apple-system,Roboto,Oxygen,Ubuntu,Cantarell,Helvetica,Arial,sans-serif"
	},
	data: [{
		type: "doughnut",
    innerRadius: "50%",
		startAngle: 200,
		yValueFormatString: "##0.0\"%\"",
		indexLabel: "{label} {y}",
		dataPoints: [
			{y: <?php echo (($evaluadosAsistente*100)/$funcionariosAsistente); ?>, label: "Evaluados", color: "#485FC7"},
			{y: <?php echo 100-(($evaluadosAsistente*100)/$funcionariosAsistente); ?>, label: "No Evaluados", color: "#EFEDFF"}
		]
	}]
});


var chart3 = new CanvasJS.Chart("chart3", {
	animationEnabled: true,
	title: {
		text: "PIE",
    fontFamily: "BlinkMacSystemFont,-apple-system,Roboto,Oxygen,Ubuntu,Cantarell,Helvetica,Arial,sans-serif"
	},
	data: [{
		type: "doughnut",
    innerRadius: "50%",
		startAngle: 200,
		yValueFormatString: "##0.0\"%\"",
		indexLabel: "{label} {y}",
		dataPoints: [
			{y: <?php echo (($evaluadosPIE*100)/$funcionariosPIE); ?>, label: "Evaluados", color: "#485FC7"},
			{y: <?php echo 100-(($evaluadosPIE*100)/$funcionariosPIE); ?>, label: "No Evaluados", color: "#EFEDFF"}
		]
	}]
});

var chart4 = new CanvasJS.Chart("chart4", {
	animationEnabled: true,
	title: {
		text: "Jefaturas",
    fontFamily: "BlinkMacSystemFont,-apple-system,Roboto,Oxygen,Ubuntu,Cantarell,Helvetica,Arial,sans-serif"
	},
	data: [{
		type: "doughnut",
    innerRadius: "50%",
		startAngle: 200,
		yValueFormatString: "##0.0\"%\"",
		indexLabel: "{label} {y}",
		dataPoints: [
			{y: <?php echo (($evaluadosJefatura*100)/$funcionariosJefatura); ?>, label: "Evaluados", color: "#485FC7"},
			{y: <?php echo 100-(($evaluadosJefatura*100)/$funcionariosJefatura); ?>, label: "No Evaluados", color: "#EFEDFF"}
		]
	}]
});


chart1.render();
chart2.render();
chart3.render();
chart4.render();
}
</script>

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

/*
$(document).on("click", ".modal-button", function () {
     var funcionarioRUT = $(this).data('id');
     $(".modal-content #funcionarioRUT").text(funcionarioRUT);

});
*/

</script>
