<?php
// Initialize the session
session_start();
require_once "config.php";
require_once "../var_date.php";
if (file_exists("../var_date.php")) {
    include "../var_date.php";
} else {
    echo "Error: El archivo var_date.php no existe.";
}

$nombre= get_nombres_from_correo($_SESSION["username"]);
$correo = $_SESSION["username"];
$rut = get_rut_from_correo($correo);
$tipo = get_tipo_from_rut($rut);
$codigo = get_codigo_from_tipo($tipo);
$_SESSION["rut"] = $rut;
$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$_SESSION["full_rut"] = $rut.dv($rut);
console_log($correo);
console_log($rut);
console_log($tipo);
console_log($codigo);

$full_rut = $rut.dv($rut);
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </head>
  <body>

<!--<div class="progress" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 2px">
  <div class="progress-bar bg-success" style="width: 25%"></div>
</div>-->
   
<?php include_once "navbar.php";?>

    <div class="container my-5 text-center">
  <div class="row align-items-top">    
<?php

if (esAutoEvaluado($full_rut) == 1) 
{
  muestraAlerta("<i class='bi bi-info-circle-fill'></i> Usted ya realizó su Autoevaluación con fecha ".getFechaAutoEvaluacion($full_rut).".", "success");
  
  echo "
  <div class='col text-start';><h3>Bienvenido(a) $nombre</h3></div>
  <div class='col text-muted text-end'></div>
  <div class='text-start'>Usted puede realizar las siguientes acciones con su AutoEvaluación:</div>
  <div>&nbsp;</div>
  
  </div>
  ";

  getResumenAutoEvaluacion($full_rut);
  exit;

}
else
{
  muestraAlerta("<i class='bi bi-exclamation-circle-fill'></i> Usted <strong>no ha realizado</strong> su Autoevaluación", "danger");
}

?>





  <div class="col text-start"><h3>Bienvenido(a) <?php echo $nombre;?></h3></div>
  <div class="col text-muted text-end"><h2><i class="bi bi-calculator"></i><span class="promedio" name="promedio">3.000</span></h2></div>
  </div>
</div>
<div class="container my-5">
<p><em><small>
      <strong>SE:</strong> Sobre lo Esperado
      <strong>CE:</strong> Cumple lo Esperado
      <strong>DM:</strong> Debe Mejorar
      <strong>BE:</strong> Bajo lo Esperado
      </small></em></p>
</div>
<form class="needs-validation" novalidate action="aeva-guardar.php" method="POST">
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
              <th>Importancia <i href="#" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-title="Importancia que asigna a cada meta (de 1% a 100%)"class="bi bi-question-circle-fill"></i></th>
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-cloud-arrow-up-fill"></i>&nbsp;Guardar</button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmar Correo Electrónico</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                Por favor confirme su correo electrónico</br>
                <input type="email" class="form-control" id="frm_correo" name="frm_correo" placeholder="usuario@espiritusanto.cl">

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Cancelar</button>
                <button type="submit" name="frm_submit" value="frm_submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="bi bi-send"></i>&nbsp;Enviar</button>
              </div>
            </div>
          </div>
        </div>        

      </div>
    </div>
    <input style="display: none" size='5' type="text" name="frm_sum" />
    <input style="display: none" size='5' type="text" name="frm_can" />
    <input value="3" style="display: none" size='5' type="text" name="frm_promedio" />

  </form>

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
