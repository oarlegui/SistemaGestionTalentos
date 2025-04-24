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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>

<!--<div class="progress" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 2px">
  <div class="progress-bar bg-success" style="width: 25%"></div>
</div>-->
   
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



/*$nombre= get_nombres_from_correo($_SESSION["username"]);
$correo = $_SESSION["username"];
$rut = get_rut_from_correo($correo);  
$tipo = get_tipo_from_rut($rut);
$codigo = get_codigo_from_tipo($tipo);
$_SESSION["rut"] = $rut;
$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$full_rut = $rut.dv($rut);*/

function getPreguntasEncuestaGrupo($encuesta, $grupo)
{   
    /*$correo = $_SESSION["username"];  
    $rut = get_rut_from_correo($correo);    
    $full_rut = $rut.dv($rut); */
    
    $funcionario = $_SESSION['funcionario'];
    $evaluador = $_SESSION['evaluador'];


    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = mysqli_connect(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2);   
    $mysqli->set_charset("utf8mb4");
  
    $query = "
    SELECT preg_texto, preg_tipo, preg_id, preg_grupo 
    FROM pregunta 
    WHERE preg_encuesta = '".$encuesta."' AND preg_grupo = '".$grupo."' AND preg_tipo = 'T' 
    ORDER BY preg_grupo, preg_orden, preg_tipo";
    $rs = mysqli_query($mysqli, $query);
    
    ?>
    <table class="table table-sm">
    <thead>
    <tr>
    <th scope="col"></th>
    <th scope="col"><div title="4 puntos">SE</div></th>
    <th scope="col"><div title="3 puntos">CE</div></th>
    <th scope="col"><div title="2 puntos">DM</div></th>
    <th scope="col"><div title="1 punto">BE</div></th>
    <th scope="col"><div></div></th>
    </tr>
    </thead>  
    <tbody>

    <?php

    while ($row = mysqli_fetch_assoc($rs))
    {
        ?>
            <tbody>
            <tr>
            <td class="align-middle"><?php echo htmlspecialchars($row['preg_texto']);?></td>
            <td class="align-middle"><div class="form-check"><input <?php estaChecked($funcionario, $evaluador, $row['preg_id'], 4);?> required class="calc form-check-input" title="Sobre lo Esperado" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="4" /></div></td>
            <td class="align-middle"><div class="form-check"><input <?php estaChecked($funcionario, $evaluador, $row['preg_id'], 3);?> required class="calc form-check-input" title="Cumple lo Esperado" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="3" /></div></td>
            <td class="align-middle"><div class="form-check"><input <?php estaChecked($funcionario, $evaluador, $row['preg_id'], 2);?> required class="calc form-check-input" title="Debe Mejorar" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="2" /></div></td>
            <td class="align-middle"><div class="form-check"><input <?php estaChecked($funcionario, $evaluador, $row['preg_id'], 1);?> required class="calc form-check-input" title="Bajo lo Esperado" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="1" /></div></td>
            <td class="align-middle"><div class="form-check"><input class="calc form-control form-control-sm" placeholder="Comentario" maxlength="255" name="c_<?php echo htmlspecialchars($row['preg_id']);?>" size="15" type="text" value="<?php getComentario($funcionario, $evaluador, $row['preg_id']);?>" /></div></td>
            </tr>        
        <?php
         
    };
    mysqli_free_result($rs);
    mysqli_close($mysqli);
    ?>
    </tbody>
    </table>
    <?php
}


function getTextosEditar($codigo)
{
    
    //$mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = mysqli_connect(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2);   
    
    $mysqli->set_charset("utf8mb4");
    
    $query="
    SELECT preg_encuesta, preg_texto, preg_tipo, preg_id, preg_grupo
    FROM pregunta
    WHERE preg_encuesta = '".$codigo."'
    ORDER BY preg_grupo, preg_orden, preg_tipo
    ";

    /*$res = $mysqli->query($query);
    $data = $res->fetch_all(MYSQLI_ASSOC);*/

    $rs_autoevaluacion = mysqli_query($mysqli, $query);
    $row_rs_autoevaluacion = mysqli_fetch_assoc($rs_autoevaluacion);

    echo "
    <div class='container my-5'>
    <div class='col-lg-0 px-0'>
    <!--Inicio Acordion-->
    <div class='accordion' id='autoevaluacion'>
    ";
    $contador = 0;
    do
    {
        if ($row_rs_autoevaluacion['preg_tipo'] == "F")
        {
            $contador = $contador + 1;
            $preg_id = $row_rs_autoevaluacion['preg_id'];
            $preg_texto = $row_rs_autoevaluacion['preg_texto'];

            $encuesta = $row_rs_autoevaluacion['preg_encuesta'];
            $grupo = $row_rs_autoevaluacion['preg_grupo'];

            if ($contador == 1)
            {
                echo "
                <!--Encabezado-->
                <div class='accordion-item'>
                <h2 class='accordion-header' id='head-flush-".$preg_id."'>
                <button class='accordion-button show' type='button' data-bs-toggle='collapse' data-bs-target='#flush-".$preg_id."' aria-expanded='true' aria-controls='flush-".$preg_id."'>
                <i class='bi bi-ui-checks'></i><strong>&nbsp;".$preg_texto."</strong></button></h2>
                ";
                echo "<div id='flush-".$preg_id."' class='accordion-collapse collapse show' data-bs-parent='#autoevaluacion'>";
                echo "<!--BodyItem-->";
                echo "<div class='accordion-body'>";
                getPreguntasEncuestaGrupo($encuesta, $grupo);
                echo "</div>";
                echo "</div>";
                //echo "</div>";
        
            }
            else
            {
            echo "
            <!--Encabezado-->
            <div class='accordion-item'>
            <h2 class='accordion-header' id='head-flush-".$preg_id."'>
            <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#flush-".$preg_id."' aria-expanded='false' aria-controls='flush-".$preg_id."'>
            <i class='bi bi-ui-checks'></i><strong>&nbsp;".$preg_texto."</strong></button></h2>
            ";
            echo "<div id='flush-".$preg_id."' class='accordion-collapse collapse' data-bs-parent='#autoevaluacion'>";
            echo "<!--BodyItem-->";
            echo "<div class='accordion-body'>";
            getPreguntasEncuestaGrupo($encuesta, $grupo);
            echo "</div>";
            echo "</div>";
            echo "</div>";
            }        

        }
    else
    {
        
        /*$preg_id = $row_rs_autoevaluacion['preg_id'];
        $preg_texto = $row_rs_autoevaluacion['preg_texto'];

        echo "<!--TextoItem-->";
        echo htmlspecialchars($preg_texto);*/
        
    }   
    } 
    while ($row_rs_autoevaluacion = mysqli_fetch_assoc($rs_autoevaluacion));
    echo "</div>";
}



/**
 * estaChecked Devuelve CHECKED si la opcion es igual a la almacenada en dim_id
 *
 * @param  mixed $funcionario RUT del Funcionario
 * @param  mixed $evaluador RUT del Evaluador
 * @param  mixed $dim_id ID de la dimensión (pregunta)
 * @param  mixed $opcion Opción a comparar (1 a 4)
 * @return void
 */
function estaChecked($funcionario, $evaluador, $dim_id, $opcion)
{
  $link = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
  $link->set_charset("utf8mb4");


  $query= "
  SELECT respuestas.res_valor, respuestas.preg_id FROM respuestas  
  INNER JOIN pregunta ON respuestas.preg_id = pregunta.preg_id
  WHERE respuestas.func_rut = '".$funcionario."' AND respuestas.res_evaluador = '".$evaluador."'
  AND respuestas.preg_id = '".$dim_id."';
  ";



  $result = mysqli_query($link, $query);
  $result_array = mysqli_fetch_array($result);
  $valor = $result_array['res_valor'];
  
  if ($opcion == $valor)
  {
    echo " CHECKED";
  }
  else
  {
    echo " ";
  }
  
}

/**
 * getPlan
 * Devuelve un elemento de un array con el Plan de Desarrollo de un Funcionario/Evaluador
 *
 * @param  string $evaluador
 * @param  string $funcionario
 * @param  int $fila
 * @param  string $col
 * @return string
 */
function getPlan($evaluador, $funcionario, $fila, $col)
{
  $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
  $mysqli->set_charset("utf8mb4");

  $query = "
  SELECT
    metas.plan_id AS iden,
    plan_meta AS meta,
    plan_importancia AS impo,
    plan_acciones AS acci,
    plan_plazo AS plaz
  FROM
    metas
    INNER JOIN planificacion ON metas.plan_id = planificacion.plan_id
  WHERE
    func_rut = '$evaluador'
    AND meta_evaluador = '$funcionario'
  ";
  
  $result = mysqli_query($mysqli, $query);
  $json = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
  if(isset($json[($fila-1)][$col]))  //Si existe el array definido (cuando son menos de 4 metas)
  {
    $salida = $json[($fila-1)][$col]; //Existe, lo retorno
    return $salida;
  }
  else
  {
    $salida = "";  //No Existe, lo convierto en NULL, evito un error NOTICE:
    return $salida; //Lo Retorno
  }
  $result->free();    
}

/**
 * getComentario
 *
 * @param  string $funcionario
 * @param  string $evaluador
 * @param  integer $dim_id
 * @return void
 */
function getComentario($funcionario, $evaluador, $dim_id)
{
  $link = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
  $link->set_charset("utf8mb4");
  $query= "
  SELECT respuestas.res_comentario FROM respuestas  
  INNER JOIN pregunta ON respuestas.preg_id = pregunta.preg_id
  WHERE respuestas.func_rut = '".$funcionario."' AND respuestas.res_evaluador = '".$evaluador."'
  AND respuestas.preg_id = '".$dim_id."';
  ";
  $result = mysqli_query($link, $query);
  $result_array = mysqli_fetch_array($result);
  $valor = $result_array['res_comentario'];
  echo $valor;
}

?>
  <div class="col text-start"><h4>Evaluando a <?php echo getNombreFromRUT($funcionario) ;?></h4></div>
  <div class="col text-muted text-end"><h2><i class="bi bi-calculator"></i><span class="promedio" name="promedio"><?php echo getPromedio($funcionario, $evaluador);?></span></h2></div>
  </div>
</div>
<form class="needs-validation" novalidate action="eva-modificar.php?funcionario=<?php echo $funcionario;?>" method="POST">

<?php
echo getTextosEditar($codigo);
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
                <!-- ********* META 01 ******** -->
                <td><textarea required class="form-control form-control-sm" name="plan_text_meta1" type="text" rows="1" id="plan_text_meta1"><?php echo getPlan($funcionario, $evaluador, 1, 'meta');?></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 required class="form-control form-control-sm" value="<?php echo getPlan($funcionario, $evaluador, 1, 'impo');?>" name="plan_imp_01" type="number" id="plan_imp_01"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea required class="form-control form-control-sm" type="text" rows="1" name="plan_accion_1" id="plan_accion_1"><?php echo getPlan($funcionario, $evaluador, 1, 'acci');?></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input required class="form-control form-control-sm" max="<?php echo $prox_año;?>" value="<?php echo getPlan($funcionario, $evaluador, 1, 'plaz');?>" type="date" name="plan_fecha_meta_01" id="plan_fecha_meta_01" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            <tr>
                <!-- ********* META 02 ******** -->
                <td><textarea required class="form-control form-control-sm" name="plan_text_meta2" type="text" rows="1" id="plan_text_meta2"><?php echo getPlan($funcionario, $evaluador, 2, 'meta');?></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 required class="form-control form-control-sm" value="<?php echo getPlan($funcionario, $evaluador, 2, 'impo');?>" name="plan_imp_02" type="number" id="plan_imp_02"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea required class="form-control form-control-sm" type="text" rows="1" name="plan_accion_2" id="plan_accion_2"><?php echo getPlan($funcionario, $evaluador, 2, 'acci');?></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input required class="form-control form-control-sm" value="<?php echo getPlan($funcionario, $evaluador, 2, 'plaz');?>" max="<?php echo $prox_año;?>" type="date" name="plan_fecha_meta_02" id="plan_fecha_meta_02" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            <tr>
                <!-- ********* META 03 ******** -->
                <td><textarea class="form-control form-control-sm" name="plan_text_meta3" type="text" rows="1" id="plan_text_meta3"><?php echo getPlan($funcionario, $evaluador, 3, 'meta');?></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 class="form-control form-control-sm" value="<?php echo getPlan($funcionario, $evaluador, 3, 'impo');?>" name="plan_imp_03" type="number" id="plan_imp_03"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea class="form-control form-control-sm" type="text" rows="1" name="plan_accion_3" id="plan_accion_3"><?php echo getPlan($funcionario, $evaluador, 3, 'acci');?></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input class="form-control form-control-sm" value="<?php echo getPlan($funcionario, $evaluador, 3, 'plaz');?>" max="<?php echo $prox_año;?>" type="date" name="plan_fecha_meta_03" id="plan_fecha_meta_03" />
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
            </tr>

            <tr>
                <!-- ********* META 04 ******** -->
                <td><textarea class="form-control form-control-sm" name="plan_text_meta4" type="text" rows="1" id="plan_text_meta4"><?php echo getPlan($funcionario, $evaluador, 4, 'meta');?></textarea>
                <div class="invalid-feedback">Debe completar al menos 2 Metas</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input max=100 min=1 class="form-control form-control-sm" value="<?php echo getPlan($funcionario, $evaluador, 4, 'impo');?>" name="plan_imp_04" type="number" id="plan_imp_04"/>
                <div class="invalid-feedback">1 a 100%</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><textarea class="form-control form-control-sm" type="text" rows="1" name="plan_accion_4" id="plan_accion_4"><?php echo getPlan($funcionario, $evaluador, 4, 'acci');?></textarea>
                <div class="invalid-feedback">Complete este campo</div>
                <div class="valid-feedback">OK!</div>
              </td>
                <td><input class="form-control form-control-sm" max="<?php echo $prox_año;?>" value="<?php echo getPlan($funcionario, $evaluador, 4, 'plaz');?>" type="date" name="plan_fecha_meta_04" id="plan_fecha_meta_04" />
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