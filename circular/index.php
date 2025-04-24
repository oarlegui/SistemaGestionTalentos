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
            <a class="nav-link" href="index.php"><i class="bi bi-file-earmark-person-fill" style="font-size: 1.5rem;"></i> Evaluaci贸n Circular</a>
            </li>
          </ul>

          <div class="d-flex nav-item">
            <a class="nav-link text-primary" href="/talentos/portal.php"><i class="bi bi-house-up" style="font-size: 1.5rem;"></i> <strong>Portal Talentos</strong></a>
          </div>
        </div>
      </div>
    </nav>

<?php
$funcionario = $_SESSION['id'];
$funcionario = $funcionario.dv($funcionario);
$evaluador = getEvaluador($funcionario);


?>
    <div class="container my-5">
      <h4>Evaluaci贸n Circular</h4>
      <p>Evaluando a <strong><?php echo getFullNameFromRUT($evaluador);?></strong></p>
      <p><em><small>
      <strong>SE:</strong> Sobre lo Esperado
      <strong>CE:</strong> Cumple lo Esperado
      <strong>DM:</strong> Debe Mejorar
      <strong>BE:</strong> Bajo lo Esperado
      <strong>NE:</strong> No Evaluable
      </small></em></p>
      <div class="col-lg-0 px-0">

<?php
if (tieneCircular($funcionario, $evaluador) != NULL)
{
  $fecha = tieneCircular($funcionario, $evaluador);
  $fecha = date('d-m-Y',strtotime($fecha));
  muestraAlerta("<i class='bi bi-check2-circle'></i> Evaluaci贸n Circular ya realizada el $fecha.", "success");

  ?>
        <div class="container my-5">
        <a href='javascript:history.back()'><button type='button' class='btn btn-secondary'><i class='bi bi-arrow-return-left'></i> Regresar</button></a>
        <?php echo "<a class='btn btn-primary btn-success' href='cir-pdf.php?funcionario=$funcionario&evaluador=$evaluador' role='button'><i class='bi bi-printer-fill'></i> Imprimir Evaluaci贸n Circular</a>";?>       
      </div>
  <?php

  exit;
} else {
  
}
?>


      <!--Tabla de AutoEvaluados y NoAutoEvaluados-->
      <form action="cir-guardar.php?funcionario=<?php echo $funcionario;?>&evaluador=<?php echo $evaluador;?>" method="POST">

      <small>
        
      <?php getPreguntasCircular();?>
      </small>
      </div>
      
      <div class="container my-5">
           <?php
            require '../var_date.php'; // Incluye el archivo var_date.php
                
                    // Definir las fechas de inicio y fin
                    //Vienen desde el archivo var_date.php
                    
                    // Obtener la fecha actual
                    $fecha_actual = date('Y-m-d');
                    
                    // Verificar si la fecha actual está dentro del rango
                    $bloquear_enlace = ($fecha_actual >= $fecha_inicio && $fecha_actual <= $fecha_fin);
                    
                    // Si el enlace debe estar bloqueado, se deshabilita
                    if ($bloquear_enlace) {
                        echo '<button class="btn btn-primary" type="submit" disabled><i class="bi bi-send"></i>  Guardar</button>';
                    } else {
                        echo '<button class="btn btn-primary" type="submit"><i class="bi bi-send"></i>  Guardar</button>';
                    }
                ?>
      
      
      <input style="display: none" size='5' type="text" name="sum" />
      <input style="display: none" size='5' type="text" name="can" />
      <input style="display: none" size='5' value="3" type="text" name="promedio" />
      

      </div>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/r-2.4.1/rg-1.3.1/sb-1.4.2/sp-2.1.2/datatables.min.css" rel="stylesheet"/>
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/r-2.4.1/rg-1.3.1/sb-1.4.2/sp-2.1.2/datatables.min.js"></script>

    
    <script>
      /*Enable ToolTips*/
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

<script type="text/javascript">

function calcscore(arg){
  var score = 0;
  $(".promedio").fadeOut(250);
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


  </body>
</html>