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
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
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
            <a class="nav-link" href="index.php"><i class="bi bi-file-earmark-person-fill" style="font-size: 1.5rem;"></i> Mi AutoEvaluación</a>
            </li>
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="evaluaciones.php"><i class="bi bi-file-earmark-check-fill" style="font-size: 1.5rem;"></i> Mis Evaluaciones</a>
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

    <div class="container my-5 text-center">
  <div class="row align-items-top">    
<?php
$evaluador = $_SESSION['id'];

$funcionario = $_GET['funcionario'];
$evaluador = $evaluador.dv($evaluador);

$tipo = getTipoFromRUT($funcionario);
$codigo = getCodigoFromTipo($tipo);

$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$_SESSION["evaluador"] = $evaluador;
$_SESSION["funcionario"] = $funcionario;





?>





  <div class="col text-start"><h4>Evaluando a <?php echo getNombreFromRUT($funcionario) ;?></h4></div>
  <div class="col text-muted text-end"><h2><i class="bi bi-calculator"></i><span class="promedio" name="promedio">3.000</span></h2></div>
  </div>
</div>
<form class="needs-validation" novalidate action="eva-guardar.php" method="POST">
<?php
echo get_textos_from_codigo($codigo);
?>

    
        
        <div class="accordion-item">
            <h2 class="accordion-header" id='plan'>
            <button class="accordion-button show" aria-expanded='true' type="button" data-bs-toggle="collapse" data-bs-target="#flush-plan" aria-expanded="false" aria-controls="flush-plan">
            <i class="bi bi-calendar-check"></i><strong>&nbsp;Plan de Desarrollo</strong>
            </button>
            </h2>
            <div id="flush-plan" aria-expanded='true' class="accordion-collapse show" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">

            <table class="table table-sm text-xsmall" id="evaluacion">
            <!-- ----------------------------------------------------------- --->
            <thead>
              <tr>
              <th>Metas <i href="#" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-title="Metas y objetivos propuestos para el pr&oacute;ximo periodo (metas relativas al desarrollo de competencias y resultados del cargo)"class="bi bi-question-circle-fill"></i></th>
              <th>Importancia <i href="#" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-title="Importancia que asigna a cada meta (de 0% a 100%)"class="bi bi-question-circle-fill"></i></th>
              <th>Acciones <i href="#" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-title="Acciones para lograr las metas"class="bi bi-question-circle-fill"></i></th>
              <th>Plazos <i href="#" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-title="Plazos estimados para el logro de objetivos propuestos"class="bi bi-question-circle-fill"></i></th>                            
              </tr>
            </thead>            

          <?php
          $siguiente_año = (date('Y')+1);
          
          $este_mes = date('Y-m-01');
          $prox_año = date('Y-12-31', strtotime('+1 year'));
          
           ?>

            <tbody>
            <tr>
              
                <td><textarea required class="form-control form-control-sm" name="plan_text_meta1" type="text" rows="1" id="plan_text_meta1"></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 required class="form-control form-control-sm" name="plan_imp_01" type="number" id="plan_imp_01"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea required class="form-control form-control-sm" type="text" rows="1" name="plan_accion_1" id="plan_accion_1"></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input required class="form-control form-control-sm" max="<?php echo $prox_año;?>" min="<?php echo $este_mes;?>" type="date" name="plan_fecha_meta_01" id="plan_fecha_meta_01" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            <tr>
              
                <td><textarea required class="form-control form-control-sm" name="plan_text_meta2" type="text" rows="1" id="plan_text_meta2"></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 required class="form-control form-control-sm" name="plan_imp_02" type="number" id="plan_imp_02"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea required class="form-control form-control-sm" type="text" rows="1" name="plan_accion_2" id="plan_accion_2"></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input required class="form-control form-control-sm" max="<?php echo $prox_año;?>" min="<?php echo $este_mes;?>" type="date" name="plan_fecha_meta_02" id="plan_fecha_meta_02" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            <tr>
              
                <td><textarea class="form-control form-control-sm" name="plan_text_meta3" type="text" rows="1" id="plan_text_meta3"></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 class="form-control form-control-sm" name="plan_imp_03" type="number" id="plan_imp_03"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea class="form-control form-control-sm" type="text" rows="1" name="plan_accion_3" id="plan_accion_3"></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input class="form-control form-control-sm" max="<?php echo $prox_año;?>" min="<?php echo $este_mes;?>" type="date" name="plan_fecha_meta_03" id="plan_fecha_meta_03" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            <tr>
              
                <td><textarea class="form-control form-control-sm" name="plan_text_meta4" type="text" rows="1" id="plan_text_meta4"></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 class="form-control form-control-sm" name="plan_imp_04" type="number" id="plan_imp_04"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea class="form-control form-control-sm" type="text" rows="1" name="plan_accion_4" id="plan_accion_4"></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input class="form-control form-control-sm" max="<?php echo $prox_año;?>" min="<?php echo $este_mes;?>" type="date" name="plan_fecha_meta_04" id="plan_fecha_meta_04" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            
            </tbody>
            </table>                
            </div>
            </div>
        </div>        

      </div>        
      </div>


      <div class="container my-5">
      <a href='javascript:history.back()'><button type='button' class='btn btn-secondary'><i class='bi bi-arrow-return-left'></i> Regresar</button></a>
      <button type="submit" name="frm_submit" value="frm_submit" class="btn btn-primary"><i class="bi bi-cloud-arrow-up-fill"></i>&nbsp;Guardar</button>
      
      </div>
    </div>
    <input style="display: none" size='5' type="text" name="frm_sum" />
    <input style="display: none" size='5' type="text" name="frm_can" />
    <input value="3" style="display: none" size='5' type="text" name="frm_promedio" />

  </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="main.js"></script>

    <script type="text/javascript">

    function precise_round(num,decimals)
    {
      return Math.round(num*Math.pow(10,decimals))/Math.pow(10,decimals);
    }

    function calcscore(arg){
        var score = 0;
        $(".promedio").fadeOut(0);
        $(".promedio").fadeIn(50);

        $(".calc:checked").each(function(){
            score+=parseInt($(this).val(),10);
        });

        var promedio = score / arg;
        promedio = promedio.toFixed(3);

        $("input[name=frm_sum]").val(score)
        $("input[name=frm_can]").val(arg)
        $(".promedio").text(promedio)
        $("input[name=frm_promedio]").val(promedio)
    }

    $(document).ready(function()
    {
      $('.calc').change(function(){
      var cantidad = $('.calc:checked').length
      calcscore(parseInt(cantidad));
      });
    });
    



// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()

    
    </script>    

<script>
$(document).ready(function(){
    $('[data-bs-toggle="tooltip"]').tooltip();   
});

$(document).on("keydown", ":input:not(textarea)", function(event) {
    return event.key != "Enter";
});

$("textarea").css("overflow-x", "hidden");


$(document).ready(function() {
        $('a[data-confirm]').click(function(ev) {
            var href = $(this).attr('href');
            $('#modal').find('.modal-title').text($(this).attr('data-confirm'));
            $('#modal-btn-yes').attr('href', href);
            $('#modal').modal({show:true});
            return false;
        });
    });


</script>
</body>
</html>