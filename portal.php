<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Gestión de Talentos</title>
    <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/notif.css">

	<script src="assets/js/notif.js" type="module"></script>

	<link rel="stylesheet" type="text/css" href="assets/slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="assets/slick/slick-theme.css"/>

	<style>
		.disabled
		{
			pointer-events: none !important;
			opacity: .65;
			cursor: not-allowed !important;
		}

		canvas {
			padding-left: 0;
			padding-right: 0;
			margin-left: auto;
			margin-right: auto;
			display: block;
		}

	</style>

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


<div class="columns">
	<div class="column">
		<div class="box">
		  <div><i class="fa-solid fa-chalkboard-user"></i> Observación de Clases<br><strong>Docentes</strong></div>

		  <button <?php esHabilitado();?> onclick="window.location='docente.php';" class="button is-success">
			<span class="icon is-small">
				<i class="fas fa-pen-to-square"></i>
			</span>
			<span>Evaluar</span>
		  </button>
		  
		  <button onclick="window.location='assets/pdf/pauta_docente.pdf';" class="button is-info">
		  <span class="icon is-small">
			  <i class="fas fa-file-arrow-down"></i>
			</span>
			<span>Formulario</span>
		  </button>		
		</div>
	</div>
	<div class="column">
		<div class="box">
		  <div><i class="fa-solid fa-chalkboard-user"></i> Observación de Clases<br><strong>Asistentes de la Educación</strong></div>
		  <button <?php esHabilitado();?> onclick="window.location='asistente.php';" class="button is-success">
			<span class="icon is-small">
			<i class="fas fa-pen-to-square"></i>
			</span>
			<span>Evaluar</span>
		  </button>
		  <button onclick="window.location='assets/pdf/pauta_asistente.pdf';" class="button is-info">
			<span class="icon is-small">
			  <i class="fas fa-file-arrow-down"></i>
			</span>
			<span>Formulario</span>
		  </button>		
		</div>
	</div>
	<div class="column">
		<div class="box">
		  <div><i class="fa-solid fa-chalkboard-user"></i> Observación de Clases<br><strong>Profesionales PIE</strong></div>
		  <button <?php esHabilitado();?> onclick="window.location='pie.php';" class="button is-success">
			<span class="icon is-small">
			<i class="fas fa-pen-to-square"></i>
			</span>
			<span>Evaluar</span>
		  </button>
		  <button onclick="window.location='assets/pdf/pauta_pie.pdf';" class="button is-info">
			<span class="icon is-small">
			  <i class="fas fa-file-arrow-down"></i>
			</span>
			<span>Formulario</span>
		  </button>		
		</div>
	</div>	
	<div class="column">
		<div class="box">
		  <div><i class="fa-solid fa-chalkboard-user"></i> Observación de Clases<br><strong>Jefaturas</strong></div>
		  <button <?php esHabilitado();?> onclick="window.location='jefatura.php';" class="button is-success">
			<span class="icon is-small">
			<i class="fas fa-pen-to-square"></i>
			</span>
			<span>Evaluar</span>
		  </button>
		  <button onclick="window.location='assets/pdf/pauta_jefatura.pdf';" class="button is-info">
			<span class="icon is-small">
			  <i class="fas fa-file-arrow-down"></i>
			</span>
			<span>Formulario</span>
		  </button>		
		</div>
	</div>
</div>

<div class="columns">
	<!-- EVALUACION DESEMPEÑO -->	
	<div class="column">
		<div class="box">
		<figure class="is-pulled-right image is-64x64">
  		<img style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="assets/img/2025-01-15.png">
		</figure>
		<figure class="is-pulled-right image is-64x64">
		<img src="assets/img/flecha-derecha.png">
		</figure>
		<figure class="is-pulled-right image is-64x64">
  		<img style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="assets/img/2024-11-11.png">
		</figure>

		<div><i class="fa-sharp fa-solid fa-user-tie"></i> Evaluadores<br><strong>Evaluación del Desempeño</strong></div>
		
		  <button id="DesempEvaluarButton" onclick="window.location='eva/evaluaciones.php';" class="button is-success">
			<span class="icon is-small">
			<i class="fas fa-pen-to-square"></i>
			</span>
			<span>Evaluar</span>
		  </button>
		  
		</div>
	</div>	
	<?php
	 require 'var_date.php';
    ?>
	<script>
	
	 // Función para obtener la fecha actual en la zona horaria de Chile
    function getChileTime() {
        return new Date(new Date().toLocaleString("en-US", { timeZone: "America/Santiago" }));
    }
    
    // Función para deshabilitar el botón según la fecha y hora
    function checkButtonAvailabilityDesempeno() {
        //const currentDate = new Date();
        //const disableDate = new Date('2025-01-15T23:59:00'); // Fecha límite
        const currentDate = getChileTime();
        const startDate = new Date("<?php echo $startDateED; ?>"); // Fecha de inicio
        const endDate = new Date("<?php echo $endDateED; ?>");   // Fecha de fin

        const button = document.getElementById('DesempEvaluarButton');
        if (currentDate >= startDate && currentDate <= endDate) {
            button.disabled = false; // Habilitar el botón
            button.classList.remove('is-danger'); // Opcional: remover estilo deshabilitado
            button.classList.add('is-success'); // Opcional: agregar estilo habilitado
            button.innerHTML = `
                <span class="icon is-small">
                    <i class="fas fa-pen-to-square"></i>
                </span>
                <span>AutoEvaluar</span>
            `;
        } else {
            button.disabled = false; // Deshabilitar el botón
            button.classList.remove('is-success'); // Opcional: remover estilo habilitado
            button.classList.add('is-warning'); // Opcional: agregar estilo deshabilitado
            button.innerHTML = `
                <span class="icon is-small">
                    <i class="fas fa-file"></i>
                </span>
                <span>Solo Descarga de Informes</span>
            `;
        }
    
    }

    // Ejecutar al cargar la página
        checkButtonAvailabilityDesempeno();
        
</script>
	<!-- AUTOEVALUACION DESEMPEÑO -->
	

	<div class="column">
		<div class="box">
		<figure class="is-pulled-right image is-64x64">
  		<img style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="assets/img/2025-01-15.png">
		</figure>
		<figure class="is-pulled-right image is-64x64">
		<img src="assets/img/flecha-derecha.png">
		</figure>
		<figure class="is-pulled-right image is-64x64">
  		<img style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="assets/img/2024-11-04.png">
		</figure>

		<div><i class="fa-sharp fa-solid fa-user"></i> Funcionarios<br><strong>AutoEvaluación del Desempeño</strong></div>
		
		  <button id="autoEvaluarButton" onclick="window.location='eva/';" class="button is-success"  >
			<span class="icon is-small">
			<i class="fas fa-pen-to-square"></i>
			</span>
			<span>AutoEvaluar</span>
		  </button>
		  
		</div>
	</div>	
	<?php
	 require 'var_date.php';
    ?>
	<script>
	
	 // Función para obtener la fecha actual en la zona horaria de Chile
    function getChileTime() {
        return new Date(new Date().toLocaleString("en-US", { timeZone: "America/Santiago" }));
    }
    
    // Función para deshabilitar el botón según la fecha y hora
    function checkButtonAvailability() {
        //const currentDate = new Date();
        //const disableDate = new Date('2025-01-15T23:59:00'); // Fecha límite
        const currentDate = getChileTime();
        const startDate = new Date("<?php echo $startDateAED; ?>"); // Fecha de inicio
        const endDate = new Date("<?php echo $endDateAED; ?>");   // Fecha de fin

        const button = document.getElementById('autoEvaluarButton');
        if (currentDate >= startDate && currentDate <= endDate) {
            button.disabled = false; // Habilitar el botón
            button.classList.remove('is-danger'); // Opcional: remover estilo deshabilitado
            button.classList.add('is-success'); // Opcional: agregar estilo habilitado
            button.innerHTML = `
                <span class="icon is-small">
                    <i class="fas fa-pen-to-square"></i>
                </span>
                <span>AutoEvaluar</span>
            `;
        } else {
            button.disabled = false; // Deshabilitar el botón
            button.classList.remove('is-success'); // Opcional: remover estilo habilitado
            button.classList.add('is-warning'); // Opcional: agregar estilo deshabilitado
            button.innerHTML = `
                <span class="icon is-small">
                    <i class="fas fa-file"></i>
                </span>
                <span>Solo Descarga de Informe</span>
            `;
        }
    }

    // Ejecutar al cargar la página
        checkButtonAvailability();
        
</script>
	<!-- EVALUACION CIRCULAR -->
	<div class="column">
		<div class="box">
		<figure class="is-pulled-right image is-64x64">
  		<img style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="assets/img/2025-01-15.png">
		</figure>
		<figure class="is-pulled-right image is-64x64">
		<img src="assets/img/flecha-derecha.png">
		</figure>
		<figure class="is-pulled-right image is-64x64">
  		<img style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" src="assets/img/2024-11-04.png">
		</figure>



		<div><i class="fa-sharp fa-solid fa-user"></i> Funcionarios<br><strong>Evaluación Circular</strong></div>
		<script src="https://unpkg.com/@glidejs/glide@3.6.2/dist/glide.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/@glidejs/glide@3.6.2/dist/css/glide.core.min.css">
		<link rel="stylesheet" href="https://unpkg.com/@glidejs/glide@3.6.2/dist/css/glide.theme.min.css">

		<button id="EvaluarCircularButton" onclick="window.location='circular/';" class="button is-success">
			<span class="icon is-small">
			<i class="fas fa-pen-to-square"></i>
			</span>
			<span>Evaluar</span>
		  </button>
		</div>
	</div>
	    <?php
	        require 'var_date.php';
        ?>
	<script>
	
	 // Función para obtener la fecha actual en la zona horaria de Chile
    function getChileTime() {
        return new Date(new Date().toLocaleString("en-US", { timeZone: "America/Santiago" }));
    }
    
  
    // Función para deshabilitar el botón según la fecha y hora
    function checkButtonAvailabilityCircular() {
        //const currentDate = new Date();
        //const disableDate = new Date('2025-01-15T23:59:00'); // Fecha límite
        const currentDate = getChileTime();
        const startDate = new Date("<?php echo $startDateEC; ?>"); // Fecha de inicio
        const endDate = new Date("<?php echo $endDateEC; ?>");   // Fecha de fin

        const button = document.getElementById('EvaluarCircularButton');
        if (currentDate >= startDate && currentDate <= endDate) {
            button.disabled = false; // Habilitar el botón
            button.classList.remove('is-danger'); // Opcional: remover estilo deshabilitado
            button.classList.add('is-success'); // Opcional: agregar estilo habilitado
            button.innerHTML = `
                <span class="icon is-small">
                    <i class="fas fa-pen-to-square"></i>
                </span>
                <span>AutoEvaluar</span>
            `;
        } else {
            button.disabled = false; // Deshabilitar el botón
            button.classList.remove('is-success'); // Opcional: remover estilo habilitado
            button.classList.add('is-warning'); // Opcional: agregar estilo deshabilitado
            button.innerHTML = `
                <span class="icon is-small">
                    <i class="fas fa-file"></i>
                </span>
                <span>Descarga de Informe, solo si realizo la Evaluación</span>
                
            `;
        }
    }

    // Ejecutar al cargar la página
        checkButtonAvailabilityCircular();
        
</script>
	
	
</div>

<?php
//////////////////////////////////// ESTADISTICAS EN PORTAL.PHP
$evaluadosTH = getEvaluados(1);
$evaluadosSA = getEvaluados(2);
$autoevaluadosTH = getAutoEvaluados(1);
$autoevaluadosSA = getAutoEvaluados(2);
$totalTH = getTotalFuncionarios(1);
$totalSA = getTotalFuncionarios(2);
$noevaluadosTH = $totalTH - $evaluadosTH;
$noevaluadosSA = $totalSA - $evaluadosSA;
$noautoevaluadosTH = $totalTH - $autoevaluadosTH;
$noautoevaluadosSA = $totalSA - $autoevaluadosSA;


?>
<!--

<div class="column">
	<section class="info-tiles">
	<div class="tile is-ancestor has-text-centered">

		<!--Talcahuano--
		<div class="thno tile is-parent">
			<article class="tile is-child box">
			<div class="columns is-vcentered">
				<div class="column is-centered">
				<p class="title"><?php echo $evaluadosTH;?> de <?php echo $totalTH;?></p>
				<p class="subtitle">Evaluados en Talcahuano</p>
				</div>
				<div class="column is-centered" style="height: 300px">
				<canvas id="thChart"></canvas>
				</div>
			</div>
			</article>
		</div>

		<!--San Antonio--
		<div class="tile is-parent">
			<article class="sa tile is-child box">
			<div class="columns is-vcentered">
			<div class="column is-centered">
			<p class="title"><?php echo $evaluadosSA;?> de <?php echo $totalSA;?></p>
			<p class="subtitle">Evaluados en San Antonio</p>
			</div>
			<div class="column is-centered" style="height: 300px">
			<canvas id="saChart"></canvas>
			</div>
			</div>
			</article>
		</div>
	</div>
</div>

<div class="column">
<section class="info-tiles">
<div class="tile is-ancestor has-text-centered">

<!--Talcahuano--
<div class="thno tile is-parent">
<article class="tile is-child box">
<div class="columns is-vcentered">
<div class="column is-centered">
<p class="title"><?php echo $autoevaluadosTH;?> de <?php echo $totalTH;?></p>
<p class="subtitle">AutoEvaluados en Talcahuano</p>
</div>
<div class="column is-centered" style="height: 300px">
<canvas id="thChart2"></canvas>
</div>
</div>
</article>
</div>

<!--San Antonio--
<div class="tile is-parent">
<article class="sa tile is-child box">
<div class="columns is-vcentered">
<div class="column is-centered">
<p class="title"><?php echo $autoevaluadosSA;?> de <?php echo $totalSA;?></p>
<p class="subtitle">AutoEvaluados en San Antonio</p>
</div>
<div class="column is-centered" style="height: 300px">
<canvas id="saChart2"></canvas>
</div>
</div>
</article>
</div>
</div>
</div>
-->


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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('thChart');
new Chart(ctx, {
	type: 'doughnut',
	options:{cutout: '70%', responsive:true, maintainAspectRatio:true, aspectRatio:1},
	data: {
		labels: [
			'Evaluados',
			'No Evaluados'],
		datasets: [{

				data: [<?php echo $evaluadosTH;?>, <?php echo $noevaluadosTH;?>],
			backgroundColor: [
				'rgb(62, 142, 208)',
				'rgb(161, 209, 232)'
			],
			hoverOffset: 1
		}]
	},
});

const ctx2 = document.getElementById('saChart');
new Chart(ctx2, {
	type: 'doughnut',
	options:{cutout: '70%'},
	data: {
		labels: [
			'Evaluados',
			'No Evaluados'],
			datasets: [{

				data: [<?php echo $evaluadosSA;?>, <?php echo $noevaluadosSA;?>],
				backgroundColor: [
					'rgb(62, 142, 208)',
					'rgb(161, 209, 232)'
				],
				hoverOffset: 1
			}]
	},
});

const ctx3 = document.getElementById('thChart2');
new Chart(ctx3, {
	type: 'doughnut',
	options:{cutout: '70%', responsive:true, maintainAspectRatio:true, aspectRatio:1},
	data: {
		labels: [
			'Evaluados',
			'No Evaluados'],
			datasets: [{

				data: [<?php echo $autoevaluadosTH;?>, <?php echo $noautoevaluadosTH;?>],
				backgroundColor: [
					'rgb(72, 199, 142)',
					'rgb(214, 245, 227)'
				],
				hoverOffset: 1
			}]
	},
});

const ctx4 = document.getElementById('saChart2');
new Chart(ctx4, {
	type: 'doughnut',
	options:{cutout: '70%'},
	data: {
		labels: [
			'Evaluados',
			'No Evaluados'],
			datasets: [{

				data: [<?php echo $autoevaluadosSA;?>, <?php echo $noautoevaluadosSA;?>],
				backgroundColor: [
					'rgb(72, 199, 142)',
					'rgb(214, 245, 227)'
				],
				hoverOffset: 1
			}]
	},
});




</script>

<!--<div class="your-class">
	<div><img src="https://placehold.co/300x300/orange/white"></div>
	<div><img src="https://placehold.co/300x300/yellow/white"></div>
	<div><img src="https://placehold.co/300x300/red/white"></div>
</div>-->

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="assets/slick/slick.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('.your-class').slick({
		autoplay: true,
		centerMode: true,
		dots: true,
		infinite: true,
		pauseOnFocus: true,
		pauseOnHover: true,
		pauseOnDotsHover: true
	});
});
</script>
</body>
</html>
