<?php
// Initialize the session
session_start();
ob_start(); 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "config.php";
$nombre= getFullName($_SESSION["username"]);
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

if (!empty($_GET["funcionario"])) {
  //funcionario viene de link
  $full_rut = $_GET["funcionario"];
  $nombre = getNombreFromRUT($full_rut);
} else { 
  //no esta desde un link 
  
}




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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <!--DataTables-->
    <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/datatables.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/datatables.min.js"></script>
    <!--DataTables-->

    <script src="main.js"></script>

<?php
$FechayHora = date('d-m-Y H:i:s', time());  
?>
<script type="text/javascript" class="init">

$(document).ready(function() {

    var table = $('#datatable').DataTable({
      buttons: false,
      language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},    
      paging:false,
      ordering:false,
      searching:false,
      info:false,

      initComplete: function () {
      setTimeout( function () {
        //table.buttons().container().appendTo( '#datatable_wrapper .col-md-6:eq(0)' );
      }, 5 );
    },

      buttons: [
        {
          extend: 'copy',
          text: '<i class="bi bi-clipboard"></i> Copiar'
        },
        {
          extend: 'excel',
          text: '<i class="bi bi-file-earmark-spreadsheet"></i> Excel',
          filename: 'AutoEvaluación - <?php echo $nombre;?>',
          sheetName: 'AutoEvaluación',
          messageTop: 'AutoEvaluación - <?php echo $nombre;?>',
          messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',

        },
        {
          extend: 'pdf',
          text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
          pageSize: 'LETTER',
          filename: 'AutoEvaluacion - <?php echo $nombre;?>',
          messageTop: 'AutoEvaluacion - <?php echo $nombre;?>',
          messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',

        },
        {
          extend: 'print',
          text: '<i class="bi bi-printer"></i> Imprimir'
        }]
    });

    
});



</script>

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
<div class="container my-5 text-start">
  <div class="row align-items-top">   
    <h3>AutoEvaluación</h3>
    <p><?php echo $nombre;?></p>
    <?php getAutoEvaluacionDetalle($full_rut); ?>
    <div class="container my-2 text-start"></div>
    <h3>Planificación</h3>
    <p><?php echo $nombre;?></p>
    <?php getPlanificacion($full_rut, $full_rut); ?>
    
    <script>
        function imprimirPagina() {
            window.print();
        }
    </script>
    
    <div class="container my-1 text-start">  
    <a href="javascript:history.back()"><button type="button" class="btn btn-secondary"><i class="bi bi-arrow-return-left"></i> Cancelar</button></a>
    <button onclick="imprimirPagina()" type="button" class="btn btn-primary" ><i class="bi bi-printer"></i> Imprimir</button>
    <!--<a href="aeva-pdf.php?funcionario=<?php echo $full_rut;?>"><button type="button" class="btn btn-primary" ><i class="bi bi-file-earmark-pdf"></i> Generar PDF</button></a> -->
    
    <?php   
    // Establecemos la zona horaria a Chile (ajusta según tu ubicación exacta)
        date_default_timezone_set('America/Santiago');
        
    // Obtener la fecha y hora actual en un formato específico
        $fecha_actual = date("d-m-Y H:i:s"); // Año-mes-día Hora:minutos:segundos
        echo "<br><br>Fecha y hora de impresión $fecha_actual";
        ?>
    </div>    
  </div>
</div>
</body>
</html>