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
require_once '../../dompdf/autoload.inc.php';


$imagen = "assets/img/ces-logo.png";
$imagenb64 = "data:image/png;base64," . base64_encode(file_get_contents($imagen));



/*$nombre= getFullName($_SESSION["username"]);
$correo = $_SESSION["username"];
$rut = get_rut_from_correo($correo);
$tipo = get_tipo_from_rut($rut);
$codigo = get_codigo_from_tipo($tipo);
$_SESSION["rut"] = $rut;
$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$full_rut = $rut.dv($rut);*/



$evaluador = $_SESSION['id'];
$funcionario = $_GET['funcionario'];
$evaluador = $evaluador.dv($evaluador);

$tipo = getTipoFromRUT($funcionario);
$codigo = getCodigoFromTipo($tipo);

$_SESSION["tipo"] = $tipo;
$_SESSION["codigo"] = $codigo;
$_SESSION["evaluador"] = $evaluador;
$_SESSION["funcionario"] = $funcionario;



$siguiente_año = (date('Y')+1);
$este_mes = date('Y-m-01');
$prox_año = date('Y-12-31', strtotime('+1 year'));

$fecha = fechaEvaluacion($funcionario);
$timestamp = strtotime($fecha);
$fecha_ae = date("d-m-Y", $timestamp);

?>
<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
            margin: 100px 25px;
            }

            header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 20px;
            color: black;
            text-align: center;
            line-height: 20px;
            }

            footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            }

            body {
            font-family: "Helvetica Neue", Helvetica, Arial;
            font-size: 11px;
            line-height: 20px;
            font-weight: 400;
            color: #3b3b3b;
            -webkit-font-smoothing: antialiased;
            font-smoothing: antialiased;
            }

            p {
            text-align: justify;
            }

            #navigation,
            form {
            display: none;
            }
            a {
            text-decoration: none;
            color: #000000;
            }
            abbr,
            acronym {
            border: 0;
            }

            abbr[title]:after,
            acronym[title]:after {
            content: " (" attr(title) ")";
            font-size: 10pt;
            }

            table {
            width: 100%;
            border-collapse: collapse;
            border: 0.75px solid gray;
            text-align: center;
            }

            thead {
            background-color: #c9ebff;
            font-weight: bolder;
            }

            #resumen {
            width: 50%;
            page-break-inside: avoid;
            }

            #resumen_eje {
            text-align: left;
            }

            #resumen_promedio {
            text-align: center;
            font-weight: bolder;
            }

            #eje {
            max-width: 5%;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 90%;
            /*white-space: nowrap;*/
            text-align: left;
            }

            #dim {
            max-width: 50%;
            overflow: hidden;
            line-height: 90%;
            text-overflow: ellipsis;
            /*white-space: nowrap;*/
            text-align: left;
            }

            #not {
            max-width: 10%;
            text-align: center;
            font-weight: bold;
            font-size: larger;
            }

            #com {
            max-width: 40%;
            overflow: hidden;
            text-overflow: ellipsis;
            /*white-space: nowrap;*/
            text-align: left;
            font-style: italic;
            }

            th,
            td {
            border: 1px solid black;
            }

            /*h1, h2, h3, h4, h5, h6
                        {
                        //page-break-after: avoid;
                        }*/

            p,
            blockquote,
            ul,
            ol,
            dl,
            pre {
            page-break-inside: avoid;
            }

            .pagebreak {
            page-break-before: always;
            } /* page-break-after works, as well */

            .observaciones {
            height: 25%;
            width: 100%;
            background-color: white;
            border: 1px solid black;
            }
        </style>
    </head>
<header>
<img align="left" alt="CES-Logo" src="<?php echo $imagenb64 ?>" width="112" height="28">
Evaluación de <?php echo getNombreFromRUT($funcionario)." (".$fecha_ae.")";?>
</header>            
    <body>
        <main>

    

    <h2><?php echo getNombreFromRUT($funcionario)." (".$fecha_ae.")";?></h2>
    <?php getEvaluacionDetalle($funcionario, $evaluador); ?>
    <div class="pagebreak"> </div>
    <h2>Planificación</h2>
    <?php getPlanificacion($funcionario, $evaluador); ?>
    

    <h2>Resumen</h2>
    <?php getResumen($funcionario, $evaluador); ?>

    <h2>Observaciones y Acuerdos</h2>
    <div class="observaciones">

    </div>


    </main>
    
<footer>
    <table style="border: 0;" id="firmas">
        <tr>
            <td style="border: 0 none #fff; border-top: 1px solid black;" id="firma"><strong>Funcionario</strong> <small><?php echo getNombreFromRUT($funcionario);?></small></td>
            <td style="border: 0;"> </td>
            <td style="border: 0 none #fff; border-top: 1px solid black;" id="firma"><strong>Evaluador</strong> <small><?php echo getNombreFromRUT($evaluador);?></small></td>
        </tr>
    </table>
<small><div style="color:darkgrey;">Generado: <?php echo date("d-m-Y @ H:m:s");?></div></small>
</footer>    
</body>
</html>
<?php

use Dompdf\Dompdf;
  $html = ob_get_clean();
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);
  $dompdf->render();
  $dompdf->stream("Evaluación - ".getNombreFromRUT($funcionario)." - ".$fecha_ae);
  header('Location: evaluaciones.php'); //Lo regreso al inicio.

?>