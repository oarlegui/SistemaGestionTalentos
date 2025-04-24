<?php
session_start(); // Asegura que se obtiene la sesión del usuario


/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'iswafmec_delasan_talentos');
define('DB_PASSWORD', 'talentos107547290garcia519');
define('DB_NAME', 'iswafmec_delasan_talentos');


 
/* Attempt to connect to MySQL database */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$link->set_charset("utf8mb4");

// Check connection
if($link === false){
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}

/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER2', 'localhost');
define('DB_USERNAME2', 'iswafmec_delasan_ces');
define('DB_PASSWORD2', '123ces456@@');

/*define('DB_USERNAME2', 'root');
define('DB_PASSWORD2', '');*/


define('DB_NAME2', 'iswafmec_delasan_ces');
 
/* Attempt to connect to MySQL database */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$link2 = mysqli_connect(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2);
$link2->set_charset("utf8mb4");

// Check connection
if($link2 === false){
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}

function consola($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);
  
    echo "<script>console.log('log: " . $output . "' );</script>";
  }

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}


function fechaAutoEvaluacion($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT date(funcionario.func_fecha_ae) as fecha
    FROM funcionario
    WHERE funcionario.func_rut = '$rut'
    ";
	$result->free();
}

function fechaEvaluacion($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT date(funcionario.func_fecha_ev) as fecha
    FROM funcionario
    WHERE funcionario.func_rut = '$rut'
    ";
    
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['fecha'];
        }
    }
	$result->free();
}


function esAutoEvaluado($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT funcionario.func_aevaluado
    FROM funcionario
    WHERE funcionario.func_rut = '$rut'
    ";
    
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_aevaluado'];
        }
    }
	$result->free();
}

function esComite($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT
        func_rut,
        func_nivel
    FROM
        funcionario
    WHERE
        funcionario.func_rut = '$rut';
    ";
    //console_log($query);
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_nivel'];
        }
    }
	$result->free();
}



function esEvaluador($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT
        func_rut,
        func_evaluador
    FROM
        funcionario
    WHERE
        funcionario.func_rut = '$rut';
    ";
    //console_log($query);
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_evaluador'];
        }
    }
	$result->free();
}

//Reporte de funcionarios que NO han realizado Evaluacion Circular
function reporteEvaluacionCircular()
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT
    suc_nombre,
    fun_paterno,
    fun_materno,
    fun_nombres
  FROM
    funcionario
    INNER JOIN sucursal ON funcionario.suc_id = sucursal.suc_id
  WHERE
    funcionario.fun_run NOT IN(
      SELECT
        eva_funcionario
      FROM
        cir_evaluacion
	  WHERE
		MONTH(eva_fecha) = MONTH(CURDATE())
    )
    AND funcionario.suc_id != 3
    AND fun_es_activo = 1
  ORDER BY
    sucursal.suc_id,
    fun_paterno,
    fun_materno
    ";
    ?>
    <table id="reporte" class="display table table-hover table-sm">
    <thead>
        <tr>
        <th scope="col">Sucursal</th>
        <th scope="col">Paterno</th>
        <th scope="col">Materno</th>
        <th scope="col">Nombres</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result = $mysqli->query($query))
    {
        while ($row = $result->fetch_assoc())
        {
            ?>
                <tr>
                <td><?php echo $row['suc_nombre'];?></td>
                <td><?php echo $row['fun_paterno'];?></td>
                <td><?php echo $row['fun_materno'];?></td>
                <td><?php echo $row['fun_nombres'];?></td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "Todos estan AutoEvaluados...";
    }
	$result->free();    
}

function cuantosEvaluaron($jefatura){

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $qry = "
    SELECT Count(eva_id) as resultado
    FROM   cir_evaluacion
           inner join funcionario
                   ON cir_evaluacion.eva_funcionario = funcionario.fun_run
    WHERE  1 = 1
           AND eva_evaluado = '$jefatura'
           AND IF(Month(eva_fecha) < 7, 1, 2) = IF(Month(Curdate()) < 7, 1, 2)
           AND Year(eva_fecha) = Year(Curdate()) 
    ";
    if ($result = $mysqli->query($qry))
    {    
        while ($row = $result->fetch_assoc())
        {
            return $row['resultado'];
        }
    }
    else
    {
        return '?';
    }
}


function cuantosEvaluadores($jefatura){

    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $rut = $jefatura.dv($jefatura);
    $qry = "
    SELECT Count(func_rut) as resultado
    FROM   funcionario
    WHERE  1 = 1
           AND func_superior = '$rut'
    ";
    if ($result = $mysqli->query($qry))
    {    
        while ($row = $result->fetch_assoc())
        {
            return $row['resultado'];
        }
    }
    else
    {
        return '?';
    }
}


function resultadosEvaluacionCircular()
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT
    funcionario.FUN_RUN AS RUN,
    sucursal.suc_id AS ID,
    sucursal.suc_nombre AS Sucursal, concat(UPPER(funcionario.FUN_PATERNO), ' ', funcionario.FUN_NOMBRES) AS Evaluador,
	FLOOR(COUNT(cir_evaluacion.eva_id)/25) AS Evaluaciones,
    format(AVG(eva_det_valor),3) AS Promedio
    FROM cir_evaluacion
    INNER JOIN cir_eva_detalle ON cir_eva_detalle.eva_id = cir_evaluacion.eva_id
    INNER JOIN funcionario ON cir_evaluacion.eva_evaluado = funcionario.FUN_RUN
    INNER JOIN sucursal ON funcionario.SUC_ID = sucursal.suc_id
	WHERE
	1 = 1
	AND YEAR(eva_fecha) = YEAR(CURDATE())
	AND IF(MONTH(eva_fecha) < 7,1,2) = IF(MONTH(CURDATE())<7,1,2)
	AND funcionario.FUN_ES_ACTIVO = 1    
    GROUP BY eva_evaluado
    ORDER BY Promedio DESC, ID ASC
    ";
    ?>
    <table id="resultados" class="display table table-hover table-sm">
    <thead>
        <tr>
        <th class="align-middle text-center" scope="col">Rank</th>    
        <th class="align-middle text-center" scope="col">Sucursal</th>
        <th class="align-middle text-center" scope="col">Nombre Evaluador</th>
        <th class="align-middle text-center" scope="col">Cant. Eval.</th>
        <th class="align-middle text-center" scope="col">Porc. Eval.</th>
        <th class="align-middle text-center" scope="col">Promedio</th>
        <th class="align-middle text-center" scope="col"></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result = $mysqli->query($query))
    {
        $rank = 0;
        while ($row = $result->fetch_assoc())
        {
            $rank++;
            if ($row['Sucursal'] == 'San Antonio'){
                $suc_color = 'danger';
            }
            else {
                $suc_color = 'primary';
            }

            
            $evaluaron = cuantosEvaluaron($row['RUN']);
            $total = cuantosEvaluadores($row['RUN']);
            $porcentaje = floor(($evaluaron/$total)*100);

            ?>
                <tr>
                <td class="align-middle text-center"><?php echo $rank;?></td>
                <td class="align-middle text-center"><?php echo "<span class='badge rounded-pill text-bg-".$suc_color."'>".$row['Sucursal']."</span>";?></td>
                <td class="align-middle text-start"><?php echo $row['Evaluador'];?></td>
                <td class="align-middle text-center"><?php echo $evaluaron.' de '.$total;?></td>
                <td class="align-middle text-center"><div class="progress" style="height: 25px;"><div class="progress-bar bg-secondary bg-gradient" role="progressbar" style="width: <?php echo $porcentaje;?>%;" aria-valuenow="<?php echo $porcentaje;?>" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentaje;?>%</div></div></td>
                <td class="align-middle text-center"><?php echo $row['Promedio'];?></td>
                <td class="align-middle text-center"><button onclick="muestraDetalle(<?php echo $row['RUN'];?>);" type="button" id="<?php echo $row['RUN'];?>" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" class="btn btn-sm btn-secondary">Detalle</button>
</td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "No hay resultados...";
    }
	$result->free();    
}

function reporteEvaluacion()
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT
    funcionario.func_rut as RUT,
	sucursal.suc_desc AS Sucursal,
	area_nombre AS Categoría,
	UPPER(funcionario.func_paterno) AS Paterno,
	UPPER(funcionario.func_materno) AS Materno,
	funcionario.func_nombres AS Nombres,
	ROUND(AVG(res_valor),3) AS Nota
    FROM funcionario
    INNER JOIN respuestas ON funcionario.func_rut = respuestas.func_rut
    INNER JOIN pregunta ON respuestas.preg_id = pregunta.preg_id
    INNER JOIN sucursal ON funcionario.suc_id = sucursal.suc_id
    INNER JOIN area ON funcionario.area_id = area.area_id
    WHERE respuestas.func_rut != respuestas.res_evaluador
    AND funcionario.suc_id != 3
    AND func_activo = 1
    GROUP BY funcionario.func_rut
    ORDER BY Nota DESC
    ";
    ?>
    <table id="reporte" class="display table-fit table table-hover table-sm">
    <thead>
        <tr>
        <th scope="col">Sucursal</th>
        <th scope="col">Categoría</th>
        <th scope="col">Paterno</th>
        <th scope="col">Materno</th>
        <th scope="col">Nombres</th>
        <th scope="col">Nota</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result = $mysqli->query($query))
    {
        while ($row = $result->fetch_assoc())
        {
            ?>
                <tr>
                <td><?php echo $row['Sucursal'];?></td>
                <td><?php echo $row['Categoría'];?></td>
                <td><?php echo $row['Paterno'];?></td>
                <td><?php echo $row['Materno'];?></td>
                <td><?php echo $row['Nombres'];?></td>
                <td><?php echo $row['Nota'];?></td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "No hay datos...";
    }
	$result->free();    
}



function reporteAutoEvaluacion()
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT sucursal.suc_desc, func_paterno, func_materno, func_nombres
    FROM funcionario
    INNER JOIN sucursal ON funcionario.suc_id = sucursal.suc_id
    WHERE 
    funcionario.func_aevaluado = 0 AND funcionario.func_activo = 1 AND funcionario.suc_id != 3
    ORDER BY suc_desc, func_paterno, func_materno
    ";
    ?>
    <table id="reporte" class="display table table-hover table-sm">
    <thead>
        <tr>
        <th scope="col">Sucursal</th>
        <th scope="col">Paterno</th>
        <th scope="col">Materno</th>
        <th scope="col">Nombres</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result = $mysqli->query($query))
    {
        while ($row = $result->fetch_assoc())
        {
            ?>
                <tr>
                <td><?php echo $row['suc_desc'];?></td>
                <td><?php echo $row['func_paterno'];?></td>
                <td><?php echo $row['func_materno'];?></td>
                <td><?php echo $row['func_nombres'];?></td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "Todos hicieron su Evaluación Circular...";
    }
	$result->free();    
}





function getResumenAutoEvaluacion($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT 
    funcionario.func_rut AS funcionario,
    DATE_FORMAT(DATE(funcionario.func_fecha_ae),'%d-%m-%Y') AS fecha,
    TIME(funcionario.func_fecha_ae) AS hora,
    ROUND(AVG(respuestas.res_valor),3) AS nota
    FROM funcionario
    INNER JOIN respuestas ON respuestas.func_rut = funcionario.func_rut
    WHERE funcionario.func_rut = '$rut' AND respuestas.res_evaluador = '$rut'";

    if ($result = $mysqli->query($query))
    {
        while ($row = $result->fetch_assoc())
        {
            ?>
            <table style="page-break-inside: avoid;" class="table">
            <thead>
                <tr>
                <th scope="col">Funcionario</th>
                <th scope="col">Fecha</th>
                <th scope="col">Hora</th>
                <th scope="col">Nota</th>
                <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th scope="row"><?php echo $row['funcionario'];?></th>
                <td><?php echo $row['fecha'];?></td>
                <td><?php echo $row['hora'];?></td>
                <td><?php echo $row['nota'];?></td>
                <td>
                <div class='text-center' role='' aria-label='acciones'>
                <a href="aeva-imprimir.php?id=<?php echo $row['funcionario']; ?>"><button type='button' class='btn btn-sm btn-outline-primary'><i class="bi bi-printer-fill"></i> Imprimir</button></a>
                
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
                        echo '<span class="btn btn-sm btn-outline-secondary disabled" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar"><i class="bi bi-pencil"></i> Editar</span>';
                    } else {
                        echo "<a href='aeva-editar.php?id={$row['funcionario']}'><button type='button' class='btn btn-sm btn-outline-primary'><i class='bi bi-pencil-fill'></i> Editar</button></a>";
                    }
                ?>
                
                    
                <a href="aeva-borrar.php?id=<?php echo $row['funcionario']; ?>"><button type='button' class='btn btn-sm btn-outline-danger'><i class="bi bi-trash-fill"></i> Eliminar</button></a>
                </div>
                </td>
                </tr>
            </tbody>
            </table>
            <?php            
        }
        
    }
    else
    {
        echo "No hay resultados...";
    }
    
	$result->free();    

}

function getEvaluacionVacia($tipo)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry = "
    SELECT
        CONCAT(SUBSTRING(eje.preg_texto, 1, 3),'…') AS Eje,
        pregunta.preg_texto AS Dimension,
        NULL AS Nota,
        NULL AS Comentario
    FROM
        pregunta
    INNER JOIN pregunta p ON
        p.preg_id = pregunta.preg_id
    INNER JOIN pregunta AS eje ON
        pregunta.preg_padre = eje.preg_id
    where
        pregunta.preg_encuesta = '$tipo'
    order by
        pregunta.preg_grupo,
        pregunta.preg_orden
    ";
        
    if ($result = $mysqli->query($qry))
    {
        
        ?><table id='datatable' class="fs-6 table table-responsive table-hover table-striped table-sm">
        <thead>
            <tr>
            <th scope="col">Eje</th>
            <th scope="col">Dimensión</th>
            <th scope="col">Nota</th>
            <th scope="col">Comentario</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result->fetch_assoc())
        {
            ?>

                <tr>
                <td id='eje'><?php echo $row['Eje'];?></td>
                <td id='dim'><?php echo $row['Dimension'];?></td>
                <td id='not'><?php echo $row['Nota'];?></td>
                <td id='com'><?php echo $row['Comentario'];?></td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "No hay resultados...";
    }

}



function getEvaluacionDetalle($funcionario, $evaluador)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry = "
    SELECT eje.preg_texto AS Eje, pregunta.preg_texto AS Dimension, res_valor AS Nota, respuestas.res_comentario AS Comentario
    FROM respuestas
    INNER JOIN pregunta ON respuestas.preg_id = pregunta.preg_id
    LEFT JOIN pregunta AS eje ON pregunta.preg_padre = eje.preg_id
    WHERE respuestas.func_rut = '$funcionario' AND respuestas.res_evaluador = '$evaluador';
    ";



    if ($result = $mysqli->query($qry))
    {
        
        ?><table id='datatable' class="fs-6 table table-responsive table-hover table-striped table-sm">
        <thead>
            <tr>
            <th scope="col">Eje</th>
            <th scope="col">Dimensión</th>
            <th scope="col">Nota</th>
            <th scope="col">Comentario</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result->fetch_assoc())
        {
            ?>

                <tr>
                <td id='eje'><?php echo $row['Eje'];?></td>
                <td id='dim'><?php echo $row['Dimension'];?></td>
                <td id='not'><?php echo $row['Nota'];?></td>
                <td id='com'><?php echo $row['Comentario'];?></td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "No hay resultados...";
    }

}



function getAutoEvaluacionDetalle($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry = "
    SELECT eje.preg_texto AS Eje, pregunta.preg_texto AS Dimension, res_valor AS Nota, respuestas.res_comentario AS Comentario
    FROM respuestas
    INNER JOIN pregunta ON respuestas.preg_id = pregunta.preg_id
    LEFT JOIN pregunta AS eje ON pregunta.preg_padre = eje.preg_id
    WHERE respuestas.func_rut = '$rut' AND respuestas.res_evaluador = '$rut';
    ";

    
    if ($result = $mysqli->query($qry))
    {
        
        ?><table id='datatable' class="fs-6 table table-responsive table-hover table-striped table-sm">
        <thead>
            <tr>
            <th scope="col">Eje</th>
            <th scope="col">Dimensión</th>
            <th scope="col">Nota</th>
            <th scope="col">Comentario</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result->fetch_assoc())
        {
            ?>

                <tr>
                <td id='eje'><?php echo $row['Eje'];?></td>
                <td id='dim'><?php echo $row['Dimension'];?></td>
                <td id='not'><?php echo $row['Nota'];?></td>
                <td id='com'><?php echo $row['Comentario'];?></td>
                </tr>
            <?php            
        }
        ?>
        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "No hay resultados...";
    }

}

function getPromedio($funcionario, $evaluador)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry =
    "
    SELECT FORMAT(AVG(res_valor),3) as promedio
    FROM respuestas
    WHERE respuestas.func_rut = '$funcionario' AND respuestas.res_evaluador = '$evaluador'
    ";
    if ($result = $mysqli->query($qry))
    {    
        while ($row = $result->fetch_assoc())
        {
            return $row['promedio'];
        }
    }
    else
    {
        return '3.000';
    }
}

function getResumen($funcionario, $evaluador)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $qry ="
    SELECT ejes.preg_texto AS Eje, FORMAT(AVG(respuestas.res_valor), 3) AS Promedio
    FROM respuestas
    INNER JOIN pregunta ON respuestas.preg_id = pregunta.preg_id
    INNER JOIN pregunta AS ejes ON pregunta.preg_padre = ejes.preg_id
    WHERE respuestas.func_rut = '$funcionario' AND respuestas.res_evaluador = '$evaluador'
    GROUP BY ejes.preg_texto WITH ROLLUP
    ";


    if ($result = $mysqli->query($qry)) {

        ?>
        <table id='resumen' class="table">
        <thead>
          <tr>
            <th scope="col">Eje</th>
            <th scope="col">Promedio</th>
          </tr>
        </thead>
        <tbody>
<?php
        while ($row = $result->fetch_assoc())
        {
?>
        <tr>
            <td id='resumen_eje'>
            <?php if ($row['Eje'] != NULL)
            {
                echo $row['Eje'];
            }
            else { echo "<strong>Promedio General</strong>"; }
            ?></td>
            <td id='resumen_promedio'><?php echo $row['Promedio'];?></td>
        </tr>
<?php
        }
        ?>
        </tbody>
        </table>
        <?php
    }
	$result->free();


}



function getPlanificacion($funcionario, $evaluador)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $qry = "
    SELECT plan_meta, plan_importancia, plan_acciones, DATE_FORMAT(DATE(plan_plazo),'%d-%m-%Y') AS plan_plazo
    FROM metas
    INNER JOIN planificacion ON metas.plan_id = planificacion.plan_id
    WHERE func_rut = '$funcionario' AND meta_evaluador = '$evaluador'
    ";

    if ($result = $mysqli->query($qry)) {

        ?>
        <table class="table">
        <thead>
          <tr>
            <th scope="col">Meta</th>
            <th scope="col">Importancia</th>
            <th scope="col">Acciones</th>
            <th scope="col">Plazo</th>
          </tr>
        </thead>
        <tbody>
<?php
        while ($row = $result->fetch_assoc())
        {
?>
        <tr>
            <td><?php echo $row['plan_meta'];?></td>
            <td><?php echo $row['plan_importancia']."%";?></td>
            <td><?php echo $row['plan_acciones'];?></td>
            <td><?php echo $row['plan_plazo'];?></td>
        </tr>
<?php
        }
        ?>
        </tbody>
        </table>
        <?php
    }
	$result->free();

}


function getFechaAutoEvaluacion($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT 
    DATE_FORMAT(DATE(funcionario.func_fecha_ae),'%d-%m-%Y') AS fecha
    FROM funcionario
    WHERE funcionario.func_rut = '$rut'
    ";

    
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['fecha'];
        }
    }
	$result->free();
}

function getUltimoID($columna_id, $tabla)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query = "SELECT MAX(".$columna_id.") AS id FROM ".$tabla.";";
    
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['id'];
        }
    }
	$result->free();
}

function dv($r)
{
    $s=1;
    //for($m=0;$r!=0;$r/=10) CHATGPT dijo que estaba feo
    for ($m = 0; $r != 0; $r = intval($r / 10))
        $s=($s+$r%10*(9-$m++%6))%11;
    return chr($s?$s+47:75);
}

function get_username_from_funcionario($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT SUBSTRING(FUN_CORREO, 1, (POSITION('@' IN FUN_CORREO)-1)) AS CORTITO
    FROM funcionario
    WHERE FUN_CORREO = '".$correo."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['CORTITO'];
        }
    }
	$result->free();
}


function get_rut_from_correo($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT FUN_RUN
    FROM funcionario
    WHERE funcionario.FUN_CORREO = '".$correo."';
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['FUN_RUN'];
        }
    }
	$result->free();
}

function getNombreFromRUT($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    
    $query = "
    select
    CONCAT(UCASE(funcionario.func_PATERNO),' ',UCASE(funcionario.func_MATERNO),' ',funcionario.func_NOMBRES) AS func_nombre
    from
        funcionario
    where
        funcionario.func_rut = '$rut'
        ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_nombre'];
        }
    }
	$result->free();
}

function getCorreoFromRUT($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    
    $query = "
    select
    funcionario.func_mail
    from
        funcionario
    where
        funcionario.func_rut = '$rut'
        ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_mail'];
        }
    }
	$result->free();
}

function get_nombres_from_correo($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT FUN_NOMBRES
    FROM funcionario
    WHERE funcionario.FUN_CORREO = '".$correo."';
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['FUN_NOMBRES'];
        }
    }
	$result->free();
}

function estaAutoEvaluado($funcionario)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry = "select funcionario.func_aevaluado from funcionario where funcionario.func_rut = '$funcionario'";

    if ($result = $mysqli->query($qry)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_aevaluado'];
        }
    }
	$result->free();
}

function estaEvaluado($funcionario)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry = "select funcionario.func_evaluado from funcionario where funcionario.func_rut = '$funcionario'";

    if ($result = $mysqli->query($qry)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_evaluado'];
        }
    }
	$result->free();
}


function getNotaEva($funcionario)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $qry = "
    SELECT 
    funcionario.func_rut AS RUT,
    ROUND(AVG(respuestas.res_valor),3) AS Nota
    FROM funcionario
    INNER JOIN respuestas ON funcionario.func_rut = respuestas.func_rut
    WHERE funcionario.func_rut = '$funcionario' AND respuestas.res_evaluador = funcionario.func_superior
    ";

    if ($result = $mysqli->query($qry)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['Nota'];
        }
    }
	$result->free();
}

function getEvaluados($evaluador)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");


    $query = "
    select
    func_rut,
    CONCAT(UCASE(funcionario.func_PATERNO),' ',UCASE(funcionario.FUNc_MATERNO),' ',funcionario.func_NOMBRES) AS func_nombre
    from
    funcionario
    where
    funcionario.func_superior = '$evaluador' and funcionario.func_activo = 1 
    order by
    func_nombre ASC
        ";

    if ($result = $mysqli->query($query)) {
        
        ?>
        <table class="table table-sm">
        <thead>
          <tr>
            <th class='text-center' scope="col">RUT</th>
            <th class='text-center' scope="col">Funcionario</th>
            <th class='text-center' scope="col">AutoEvaluado?</th>
            <th class='text-center' scope="col">Evaluado?</th>
            <th class='text-center' scope="col">Nota</th>
            <th class='text-center' scope="col">Acciones</th>
            

          </tr>
        </thead>
        <tbody>
            <?php

        while ($row = $result->fetch_assoc())
        {
            $func_rut = $row['func_rut'];
            $func_nombre = $row['func_nombre'];

            $positivo = "<i class='text-center text-success bi bi-check-circle-fill'></i>";
            $negativo = "<i class='text-center text-danger bi bi-x-circle-fill'></i>";
            
            if (estaAutoEvaluado($func_rut) == 0)
            {
                $indicadorAE = $negativo;
            }
            else
            {
                $indicadorAE = $positivo;
            }

            if (estaEvaluado($func_rut) == 0)
            {
                //No esta Evaluado
                $indicadorEV = $negativo;
            }
            else
            {
                //Esta Evaluado
                $indicadorEV = $positivo;

            
            }
            ?>
            <tr>
                <td class='text-center'><?php echo $func_rut; ?></td>
                <td class='text-start'><?php echo $func_nombre;?></td>
                <td class='text-center'><?php echo $indicadorAE;?></td>
                <td class='text-center'><?php echo $indicadorEV;?></td>
                <td class='text-center'><?php echo getNotaEva($func_rut);?></td>
                <td class='text-end'><div class="btn-group" role="group" aria-label="">
                
                <?php
                if (estaEvaluado($func_rut) == 0)
                {
                    if (estaAutoEvaluado($func_rut) == 1)
                    {
                        ?>
                        <a id="ver_ae" href="aeva-imprimir.php?funcionario=<?php echo $func_rut;?>" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver AutoEvaluación" role="button"><i class="bi bi-eye-fill"></i>  Ver AE</a>
                        <?php                        
                    }
                    //Si no ha sido Evaluado... Muestro EVALUAR solamente
                                        ?>
                    <a id="imprimir" href="eva-pdf-vacio.php?funcionario=<?php echo $func_rut;?>" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Formulario Vacío" role="button"><i class="bi bi-file-text"></i> Formulario</a>
                    <a id="evaluar" href="eva-evaluar.php?funcionario=<?php echo $func_rut;?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Evaluar" role="button"><i class="bi bi-person-check-fill"></i>  Evaluar</a>
                    <?php
                }
                else
                {
                    if (estaAutoEvaluado($func_rut) == 1)
                    {
                        ?>
                        <a id="ver_ae" href="aeva-imprimir.php?funcionario=<?php echo $func_rut;?>" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver AutoEvaluación" role="button"><i class="bi bi-eye-fill"></i>  Ver AE</a>
                        <?php                        
                    }
                    //Si ya fue Evaluado... Muestro EDITAR, PDF y BORRAR
                    ?>
                    <a id="imprimir" href="eva-pdf.php?funcionario=<?php echo $func_rut;?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Imprimir" role="button" target="_blank"><i class="bi bi-printer"></i></a>
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
                            echo '<span class="btn btn-sm btn-outline-secondary disabled" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar"><i class="bi bi-pencil"></i></span>';
                        } else {
                            echo '<a id="editar" href="eva-editar.php?funcionario=' . $func_rut . '" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar" role="button"><i class="bi bi-pencil"></i></a>';
                        }
                    ?>
                    <a id="borrar" href="eva-borrar.php?funcionario=<?php echo $func_rut;?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Borrar" role="button"><i class="bi bi-trash-fill"></i></a>
                    <?php

                }
                
                ?>

                
                
        </div></td>

            </tr>
    <?php
    
    /*if (estaEvaluado($func_rut) == 0)
    {
        //No esta Evaluado
        $indicadorEV = $negativo;
        ?>
        <script>
        $('a#imprimir').addClass("disabled");
        $('a#editar').addClass("disabled");
        $('a#borrar').addClass("disabled");
        </script>
        <?php
        

    }
    else
    {
        //Esta Evaluado
        $indicadorEV = $positivo;

    
    }   */ 


        }
        ?>
        </tbody>
        </table>
        <?php        

    }
	$result->free();    
}


function getFullName($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT CONCAT (UCASE(funcionario.FUN_PATERNO), ' ', UCASE(funcionario.FUN_MATERNO), ' ', funcionario.FUN_NOMBRES) AS Funcionario
    FROM funcionario
    WHERE funcionario.FUN_CORREO = '".$correo."';
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['Funcionario'];
        }
    }
	$result->free();
}

function muestraAlerta($mensaje, $tipo)
{
    echo "<div class='alert alert-".$tipo."' role='alert'>".$mensaje."</div>";
}

function getTipoFromRUT($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT func_tip_id, suc_id, area_id, func_evaluador
    FROM funcionario
    WHERE funcionario.func_rut = '".$rut."';
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_tip_id'];
        }
    }
	$result->free();
}

function get_tipo_from_rut($rut)
{
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");

    $rut = $rut.dv($rut);

    $query = "
    SELECT func_tip_id, suc_id, area_id, func_evaluador
    FROM funcionario
    WHERE funcionario.func_rut = '".$rut."';
    ";
    console_log($query);
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['func_tip_id'];
        }
    }
	$result->free();
}

function getCodigoFromTipo($tipo)
{
    switch ($tipo) {
        case '1':
            return 17758;
            break;
        case '2':
            return 39812;
            break;
        case '3':
            return 69755;
            break;
        case '4':
            return 51372;
            break;
        case '5':
            return 96131;
            break;
        case '6':
            return 66666;
            break;
        case '7':
            return 77778;
            break;
        case '8':
            return 88889;
            break;                        
        default:
            echo '<strong>ERROR:</strong> Tipo Evaluacion No Asignado o No Corresponde';
            break;
    }
}



function get_codigo_from_tipo($tipo)
{
    switch ($tipo) {
        case '1':
            return 17758;
            break;
        case '2':
            return 39812;
            break;
        case '3':
            return 69755;
            break;
        case '4':
            return 51372;
            break;
        case '5':
            return 96131;
            break;
        case '6':
            return 66666;
            break;
        case '7':
            return 77778;
            break;
        case '8':
            return 88889;
            break;                        
        default:
            echo 'ERROR: Tipo Evaluacion No Asignado o No Corresponde';
            break;
    }
}

function get_preguntas_from_encuesta_grupo($encuesta, $grupo)
{   
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
    <th scope="col"><div>SE</div></th>
    <th scope="col"><div>CE</div></th>
    <th scope="col"><div>DM</div></th>
    <th scope="col"><div>BE</div></th>
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
            <td class="align-middle"><div class="form-check"><input required class="calc form-check-input" title="Sobre lo Esperado" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="4" /></div></td>
            <td class="align-middle"><div class="form-check"><input required class="calc form-check-input" title="Cumple lo Esperado" checked="checked" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="3" /></div></td>
            <td class="align-middle"><div class="form-check"><input required class="calc form-check-input" title="Debe Mejorar" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="2" /></div></td>
            <td class="align-middle"><div class="form-check"><input required class="calc form-check-input" title="Bajo lo Esperado" name="<?php echo htmlspecialchars($row['preg_id']);?>" type="radio" value="1" /></div></td>
            <td class="align-middle"><div class="form-check"><input class="calc form-control form-control-sm" placeholder="Comentario" maxlength="255" name="c_<?php echo htmlspecialchars($row['preg_id']);?>" size="15" type="text" value="" /></div></td>
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


function get_textos_from_codigo($codigo)
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
            get_preguntas_from_encuesta_grupo($encuesta, $grupo);
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
        get_preguntas_from_encuesta_grupo($encuesta, $grupo);
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


/*
    // If there is data then display each row
    if ($data) {
        foreach ($data as $row) {
            
            if ($row['preg_tipo'] == "F") //Si es un encabezado...
            {
                $preg_id = $row['preg_id'];
                $preg_texto = $row['preg_texto'];

                echo "
                <div class='accordion-item'>
                <h2 class='accordion-header'>
                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#flush-".$preg_id.".' aria-expanded='false' aria-controls='flush-".$preg_id."'>
                <i class='bi bi-ui-checks'></i><strong>&nbsp;".$preg_texto."</strong></button></h2>
                <div id='flush-".$preg_id."' class='accordion-collapse collapse' data-bs-parent='#accordionFlushExample'>
                ";
                echo "<div class='accordion-body'>";
            }            
            elseif ($row['preg_tipo'] == "T") //Si es un texto...
            {
                echo htmlspecialchars($row['preg_texto']);
                //echo "< /br>";
            }
            echo "</div>";
        }
        
    } else {
        echo '⛔ ERROR!';
    }
    echo "</div></div></div></div>";
*/
}

function old_get_textos_from_codigo($codigo)
{
    
    $mysqli = new mysqli(DB_SERVER2, DB_USERNAME2, DB_PASSWORD2, DB_NAME2); 
    $mysqli->set_charset("utf8mb4");
    $query="
    SELECT preg_texto, preg_tipo, preg_id, preg_grupo
    FROM pregunta
    WHERE preg_encuesta = '".$codigo."'
    ORDER BY preg_grupo, preg_orden, preg_tipo
    ";

    $res = $mysqli->query($query);
    $data = $res->fetch_all(MYSQLI_ASSOC);

    echo '<table id="example" class="table is-striped">';
    // Display table header
    echo '<thead>';
    echo '<tr>';
    foreach ($res->fetch_fields() as $column) {
        echo '<th>'.htmlspecialchars($column->name).'</th>';
    }
    echo '</tr>';
    echo '</thead>';
    // If there is data then display each row
    if ($data) {
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>'.htmlspecialchars($cell).'</td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="'.$res->field_count.'">⛔ No hay Observaciones!</td></tr>';
    }
    echo '</table>';
}
