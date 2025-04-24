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
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>

    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
  
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
$full_rut = $rut.dv($rut);



//verificar que es evaluador y ademas es nivel 1

if(esComite($full_rut) >= 1)
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
      <h4>Reporte de <strong>AutoEvaluación</strong></h4>
      <p>Los siguientes funcionarios no han realizado su AutoEvaluación.</p>
      <div class="col-lg-0 px-0">
      <!--Tabla de AutoEvaluados y NoAutoEvaluados-->

        <?php reporteAutoEvaluacion();?>

      </div>
    </div>
    <div class="container my-5">
      <h4>Reporte de <strong>Evaluación Circular</strong></h4>
      <p>Los siguientes funcionarios no han realizado su Evaluación Circular</p>
      <div class="col-lg-0 px-0">
      <!--Tabla de AutoEvaluados y NoAutoEvaluados-->

        <?php reporteEvaluacionCircular();?>

      </div>
    </div>    
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>    

    <script>
        $(document).ready(function() {
            var table = $('table.display').DataTable( {
                lengthChange: false,
                
                language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},
                dom: "<'row'<'col-sm-12 col-md-3'B><'col-sm-12 col-md-5'l><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                columnDefs:
                [
                    {
                    target: 0,
                    visible: false,
                    searchable: true,
                    },
                ],
                rowGroup: {dataSrc: 0},

                order: [[0,1,2, 'asc']],

                buttons: [
                {
                extend: 'excel',
                filename: '* - Reporte Comite',
                text: '<i class="bi bi-file-earmark-spreadsheet"></i> XLS',
                },
                {
                extend: 'pdf',
                filename: '* - Reporte Comite',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                pageSize: 'LETTER',

                }]
            } );
        } );
    </script>    

    <script>
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        //Hack HORRIBLE para pintar botones del color primario, debo esperar a que 'existan'
        setTimeout(
            function() 
            {
                $( ":button" ).removeClass( "btn-secondary" ).addClass( "btn-primary" );
            }, 400);
    </script>

  </body>
</html>
