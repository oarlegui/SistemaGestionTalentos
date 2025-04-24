<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require_once "config.php";

$funcionario = $_SESSION["funcionario"];
$evaluador = $_SESSION["evaluador"];
//$rut = $rut.dv($rut);

function InsertaDB($consulta)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query = $consulta;
    $result = $mysqli->query($query);
    return $id = mysqli_insert_id($mysqli);
    $result->free();
}

//GUARDA RESPUESTAS
foreach ($_POST as $clave => $valor)
{
    if (startsWith($clave, "frm") != TRUE AND startsWith($clave, "plan") != TRUE) //Solo Respuestas!
    {
        if (substr($clave,0,2) == "c_")
        {
            $id2 = substr($clave,2);
            
            $qry2 = "UPDATE respuestas SET res_comentario = '$valor' WHERE preg_id = '$id2' AND func_rut ='$funcionario' AND res_evaluador='$evaluador'";
            InsertaDB($qry2);
            continue;
        }
        $qry1 = "INSERT INTO respuestas VALUES (NULL, '$funcionario', '$clave', '$valor', NULL, '$evaluador')";
        console_log("resp: ".InsertaDB($qry1)); //En consola el ultimo ID de la tabla
    }
}

//GUARDA PLANIFICACION Y METAS
$contador = 0;
foreach ($_POST as $clave => $valor)
{
    if (startsWith($clave, "plan_text_meta") == TRUE) //Solo Planificacion
    {
        $contador = $contador + 1;

        if ($contador == 1)
        {
            if ($_POST['plan_text_meta1'] != '')
            {
                /*$mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
                $mysqli->set_charset("utf8mb4");*/

                $qry = "INSERT INTO planificacion VALUES (NULL, '$_POST[plan_text_meta1]', '$_POST[plan_imp_01]', '$_POST[plan_accion_1]', '$_POST[plan_fecha_meta_01]')";
                $id = console_log("plan: ".InsertaDB($qry));
                $last_id = getUltimoID('plan_id','planificacion');
                $qry2 = "INSERT INTO metas VALUES (NULL, '$last_id', '$funcionario', '$evaluador')";
                console_log("meta: ".InsertaDB($qry2));

            }
            if ($_POST['plan_text_meta2'] != '')
            {
                $qry = "INSERT INTO planificacion VALUES (NULL, '$_POST[plan_text_meta2]', '$_POST[plan_imp_02]', '$_POST[plan_accion_2]', '$_POST[plan_fecha_meta_02]')";
                $id = console_log("plan: ".InsertaDB($qry));
                $last_id = getUltimoID('plan_id','planificacion');
                $qry2 = "INSERT INTO metas VALUES (NULL, '$last_id', '$funcionario', '$evaluador')";
                console_log("meta: ".InsertaDB($qry2));

            }
            if ($_POST['plan_text_meta3'] != '')
            {
                $qry = "INSERT INTO planificacion VALUES (NULL, '$_POST[plan_text_meta3]', '$_POST[plan_imp_03]', '$_POST[plan_accion_3]', '$_POST[plan_fecha_meta_03]')";
                $id = console_log("plan: ".InsertaDB($qry));
                $last_id = getUltimoID('plan_id','planificacion');
                $qry2 = "INSERT INTO metas VALUES (NULL, '$last_id', '$funcionario', '$evaluador')";
                console_log("meta: ".InsertaDB($qry2));
            }
            if ($_POST['plan_text_meta4'] != '')
            {
                $qry = "INSERT INTO planificacion VALUES (NULL, '$_POST[plan_text_meta4]', '$_POST[plan_imp_04]', '$_POST[plan_accion_4]', '$_POST[plan_fecha_meta_04]')";
                $id = console_log("plan: ".InsertaDB($qry));
                $last_id = getUltimoID('plan_id','planificacion');
                $qry2 = "INSERT INTO metas VALUES (NULL, '$last_id', '$funcionario', '$evaluador')";
                console_log("meta: ".InsertaDB($qry2));
            }             
        }
    }
}
//MARCAR AL FUNCIONARIO COMO AUTOEVALUADO Y GUARDAR FECHA DE AUTOEVALUACION
$date = date('Y-m-d H:i:s');
$qry = "UPDATE funcionario SET func_evaluado = '1', func_fecha_ev = '$date' WHERE func_rut = '$funcionario'";
console_log("QRY: ".$qry);
$mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
$mysqli->set_charset("utf8mb4");
$result = $mysqli->query($qry);
$id = mysqli_insert_id($mysqli);
echo'<script type="text/javascript">alert("Evaluación Guardada");
            window.location.href="evaluaciones.php";
    </script>';
//header('Location: evaluaciones.php');
exit;

?>
