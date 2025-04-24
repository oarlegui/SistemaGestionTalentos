<?php
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

/*outputMySQLToHTMLTable($mysqli, 'user');*/
function outputMySQLToHTMLTable(mysqli $mysqli, string $table)
{
    // Make sure that the table exists in the current database!
    $tableNames = array_column($mysqli->query('SHOW TABLES')->fetch_all(), 0);
    if (!in_array($table, $tableNames, true)) {
        throw new UnexpectedValueException('Unknown table name provided!');
    }
    $res = $mysqli->query('SELECT * FROM '.$table);
    $data = $res->fetch_all(MYSQLI_ASSOC);

    echo '<table>';
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
        echo '<tr><td colspan="'.$res->field_count.'">No records in the table!</td></tr>';
    }
    echo '</table>';
}

function esHabilitado()
{
	$funcionario = get_rut_from_funcionario($_SESSION["username"]);
	if (esObservador($funcionario) == 1)
	{
		echo '';
	} else{
		echo 'disabled';
	}
}

function Admin()
{
	$funcionario = get_rut_from_funcionario($_SESSION["username"]);
	if (esAdmin($funcionario) == 1)
	{
		echo '';
	} else{
		echo 'disabled';
	}
}

function esAdmin($funcionario)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");  

    $qry = "
    select FUNC_ADMIN from funcionario
    where
    funcionario.FUN_RUN = '$funcionario'";

    $esAdmin = $mysqli->query($qry)->fetch_object()->observador; 
    return $esAdmin;
}

function esObservador($funcionario)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");  

    $qry = "
    select
    fun_es_evaluador as observador
    from
    funcionario
    where
    funcionario.FUN_RUN = '$funcionario'
    ";

    $esObservador = $mysqli->query($qry)->fetch_object()->observador; 
    return $esObservador;
}


function get_resultados()
{
    
    $agno_actual = date("Y");
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT
    sucursal.suc_nombre AS Colegio, 
    CONCAT (UCASE(funcionario.FUN_PATERNO), " ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Funcionario,
    FORMAT(AVG(observacion.OBS_NOTA),2) AS Puntaje
   FROM observacion
   INNER JOIN funcionario ON observacion.OBS_FUN_OBSERVADO = funcionario.FUN_RUN
   INNER JOIN funcionario AS evaluador ON observacion.FUN_RUN = evaluador.FUN_RUN
   INNER JOIN sucursal ON funcionario.SUC_ID = sucursal.suc_id
   WHERE YEAR(OBS_FECHA) = '.$agno_actual.'
   GROUP BY observacion.OBS_FUN_OBSERVADO
   ORDER BY Puntaje DESC, Funcionario ASC  
    ';

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
//TODO: Implementar que cuando no hay observaciones salga lo de esta funcion... se ve mucho mejor.



function get_ranking_detalle()
{
    
    $agno_actual = date("Y");
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT
        sucursal.suc_nombre AS Colegio,
        CONCAT (UCASE(funcionario.FUN_PATERNO),	" ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Funcionario,
        FORMAT(observacion.OBS_NOTA,2) AS Puntaje,
        CONCAT (UCASE(evaluador.FUN_PATERNO),	" ", UCASE(evaluador.FUN_MATERNO), " ", evaluador.FUN_NOMBRES) AS Evaluador,
        observacion.OBS_FECHA AS Fecha
    FROM
        observacion
    INNER JOIN funcionario ON
        observacion.OBS_FUN_OBSERVADO = funcionario.FUN_RUN
    INNER JOIN funcionario AS evaluador ON
        observacion.FUN_RUN = evaluador.FUN_RUN
    INNER JOIN sucursal ON
        funcionario.SUC_ID = sucursal.suc_id
    WHERE
        YEAR(OBS_FECHA) =  '.$agno_actual.'
    ORDER BY
        Funcionario ASC,
        Puntaje DESC    
    ';

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


function get_avance($rut)
{
    $agno_actual = date("Y");

    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $query = '
    SELECT COUNT(OBS_ID) AS avance
    FROM observacion
    WHERE FUN_RUN = '.$rut.' AND YEAR(OBS_FECHA) = '.$agno_actual.';';
    $avance = $mysqli->query($query)->fetch_object()->avance; 
    echo $avance;
}


function get_cumplimiento()
{
    $agno_actual = date("Y");
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT 
    funcionario.FUN_RUN RUN,
    CONCAT(UPPER(funcionario.FUN_PATERNO), " ", funcionario.FUN_NOMBRES) AS "Evaluador",
    sucursal.suc_nombre AS "Sucursal",
    FORMAT(AVG(OBS_NOTA),2) AS "Promedio",
    COUNT(observacion.FUN_RUN) AS "Observaciones"
    FROM funcionario
    LEFT JOIN observacion ON observacion.FUN_RUN = funcionario.FUN_RUN
    INNER JOIN sucursal ON sucursal.suc_id = funcionario.SUC_ID
    WHERE funcionario.SUC_ID !=3 AND funcionario.FUN_ES_EVALUADOR = 1 AND YEAR(OBS_FECHA) = '.$agno_actual.'
    GROUP BY funcionario.FUN_RUN
    ORDER BY Observaciones DESC, Evaluador ASC
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="example" class="table is-striped">';
        echo "<thead>";
        echo "<tr>";
        echo "<th>Evaluador</th>";
        echo "<th>Sucursal</th>";
        echo "<th>Promedio</th>";
        echo "<th>Observaciones</th>";
        echo "<th>A Realizar <span class='icon has-tooltip-up has-tooltip-arrow' data-tooltip='Según la cantidad de funcionarios a evaluar informados manualmente'><i class='fas is-small fa-circle-question'></i></span></th>";        
        echo "<th>Progreso</th>";
        echo "</tr>";
        echo "</thead>";

                
     while($rowitem = mysqli_fetch_array($results)) {
        
        $avance = $rowitem['Observaciones'];
        $total = CantidadEvaluar($rowitem['RUN']);
        if ($total == 0){$total = 1;}
        $porcentaje = round ($avance * 100 / $total);
        if ($porcentaje >= 100) {$porcentaje = 100;};
        
        echo "<tr>";
        echo "<td>" . $rowitem['Evaluador'] . "</td>";
        echo "<td>" . $rowitem['Sucursal'] . "</td>";
        echo "<td>" . $rowitem['Promedio'] . "</td>";
        echo "<td>" . $rowitem['Observaciones'] . "</td>";
        echo "<td>" . $total."</td>";
        echo "<td>
                <progress class='progress has-text-white is-link' value='$porcentaje' max='100'>
                    $porcentaje
                </progress>
             </td>";
        echo "</tr>";
    }
    echo "</table>"; //end table tag
}

// ███████ ███████ ████████  █████  ██████  ██ ███████ ████████ ██  ██████  █████  ███████ 
// ██      ██         ██    ██   ██ ██   ██ ██ ██         ██    ██ ██      ██   ██ ██      
// █████   ███████    ██    ███████ ██   ██ ██ ███████    ██    ██ ██      ███████ ███████ 
// ██           ██    ██    ██   ██ ██   ██ ██      ██    ██    ██ ██      ██   ██      ██ 
// ███████ ███████    ██    ██   ██ ██████  ██ ███████    ██    ██  ██████ ██   ██ ███████ 

function dv($r){
    $s=1;
    for($m=0;$r!=0;$r/=10)
        @$s=($s+$r%10*(9-$m++%6))%11;
    return chr($s?$s+47:75);
}

function CantidadEvaluar($rut)
{
    $mysqli = new mysqli('localhost', 'iswafmec_delasan_talentos', 'talentos107547290garcia519', 'iswafmec_delasan_talentos'); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT f.FUN_META total
    FROM funcionario f
    WHERE f.FUN_RUN  = '$rut'
    LIMIT 1
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['total'];
        }
    }
	$result->free();
}

function FuncEvalPIE($sucursal, $agno_actual)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
	    COUNT(DISTINCT(o.OBS_FUN_OBSERVADO)) total
    FROM
	    observacion o INNER JOIN funcionario f
    ON o.OBS_FUN_OBSERVADO = f.FUN_RUN 

    WHERE
	1 = 1
	AND f.CAT_ID = 7
	AND f.FUN_ES_ACTIVO = 1
	AND f.SUC_ID = '.$sucursal.'
	AND YEAR(o.OBS_FECHA) = '.$agno_actual.'';

    $results = mysqli_query($link,$query);

    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}

function FuncEvalDocente($sucursal, $agno_actual)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
	    COUNT(DISTINCT(o.OBS_FUN_OBSERVADO)) total
    FROM
	    observacion o INNER JOIN funcionario f
    ON o.OBS_FUN_OBSERVADO = f.FUN_RUN 

    WHERE
	1 = 1
	AND f.FUN_ES_DOCENTE = 1
	AND f.FUN_ES_ACTIVO = 1
	AND f.SUC_ID = '.$sucursal.'
	AND YEAR(o.OBS_FECHA) = '.$agno_actual.'';

    $results = mysqli_query($link,$query);

    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}

function FuncEvalAsistente($sucursal, $agno_actual)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
	    COUNT(DISTINCT(o.OBS_FUN_OBSERVADO)) total
    FROM
	    observacion o INNER JOIN funcionario f
    ON o.OBS_FUN_OBSERVADO = f.FUN_RUN 

    WHERE
	1 = 1
	AND f.FUN_ES_ASISTENTE = 1
	AND f.FUN_ES_ACTIVO = 1
	AND f.SUC_ID = '.$sucursal.'
	AND YEAR(o.OBS_FECHA) = '.$agno_actual.'';

    $results = mysqli_query($link,$query);

    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}

function FuncEvalJefatura($sucursal, $agno_actual)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
	    COUNT(DISTINCT(o.OBS_FUN_OBSERVADO)) total
    FROM
	    observacion o INNER JOIN funcionario f
    ON o.OBS_FUN_OBSERVADO = f.FUN_RUN 

    WHERE
	1 = 1
	AND f.FUN_ES_JEFATURA = 1
	AND f.FUN_ES_ACTIVO = 1
	AND f.SUC_ID = '.$sucursal.'
	AND YEAR(o.OBS_FECHA) = '.$agno_actual.'';

    $results = mysqli_query($link,$query);

    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}


function FuncTotalPIE($sucursal)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
        COUNT(FUN_RUN) total
    FROM
        funcionario f
    WHERE
        1 = 1
        AND f.CAT_ID = 7
        AND f.FUN_ES_ACTIVO = 1
        AND f.SUC_ID = '.$sucursal.'
    ';

    $results = mysqli_query($link,$query);

    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}


function FuncTotalAsistente($sucursal)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
    	COUNT(FUN_RUN) total
    FROM
        funcionario f
    WHERE
        1 = 1
        AND f.FUN_ES_ACTIVO = 1
        AND f.FUN_ES_ASISTENTE = 1
        AND f.SUC_ID = '.$sucursal.'
    ';
    $results = mysqli_query($link,$query);
    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}


function FuncTotalDocente($sucursal)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
    	COUNT(FUN_RUN) total
    FROM
        funcionario f
    WHERE
        1 = 1
        AND f.FUN_ES_ACTIVO = 1
        AND f.FUN_ES_DOCENTE = 1
        AND f.SUC_ID = '.$sucursal.'
    ';
    $results = mysqli_query($link,$query);
    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}

function FuncTotalJefatura($sucursal)
{
    $link = $GLOBALS['link'];
    $query='
    SELECT
    	COUNT(FUN_RUN) total
    FROM
        funcionario f
    WHERE
        1 = 1
        AND f.FUN_ES_JEFATURA = 1
        AND f.FUN_ES_DOCENTE = 1
        AND f.SUC_ID = '.$sucursal.'
    ';
    $results = mysqli_query($link,$query);
    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem['total'];
    }
}

function get_dato_observacion($id_obs, $obs_dato)
{
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 

    $query='
    SELECT '.$obs_dato.' FROM observacion
    WHERE observacion.OBS_ID = '.$id_obs.'
    ';

    $results = mysqli_query($link,$query);

    while($rowitem = mysqli_fetch_array($results))
    {
        return $rowitem[$obs_dato];
    }
}

function get_detalle_observacion($observacion)
{
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT 
    EJE_CORTO AS Eje,
    DIM_TEXTO AS Dimensión,
    
    CASE WHEN ODE_VALOR = 9 
        THEN "🛇"
        ELSE ODE_VALOR
    END AS Evaluación
    
    FROM observacion 
    LEFT JOIN observacion_detalle ON observacion.OBS_ID = observacion_detalle.OBS_ID
    LEFT JOIN dimension ON observacion_detalle.DIM_ID = dimension.DIM_ID
    LEFT JOIN eje ON dimension.EJE_ID = eje.EJE_ID
    WHERE observacion.OBS_ID = '.$observacion.'
    ORDER BY eje.EJE_ID, dimension.DIM_ID ASC
    
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="" class="table is-fullwidth is-narrow is-hoverable is-striped">'; 
        echo "<thead>";
        echo "<tr>";
        echo "<th>Eje</th>";
        echo "<th>Dimensión</th>";
        echo "<th>Evaluación</th>";
        echo "</tr>";
        echo "</thead>";
     while($rowitem = mysqli_fetch_array($results)) {
        echo "<tr>";
        echo "<td class='is-vcentered'>" . $rowitem['Eje'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Dimensión'] . "</td>";
        echo "<td class='has-text-centered is-vcentered'>" . $rowitem['Evaluación'] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; 
   
}


function bak_get_observaciones_rut($rut)
{
    //Esta funcion de respaldo es para cuando pidan datos muy viejos de otros agnos.
    $agno_actual = date("Y");

    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $query='
    SELECT
    OBS_ID AS ID,
    OBS_FUN_OBSERVADO AS RUT,
    CONCAT (UCASE(funcionario.FUN_PATERNO), " ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Funcionario,
    FORMAT((OBS_NOTA),2) AS Nota,
    OBS_FECHA AS Fecha
    FROM observacion
    INNER JOIN funcionario ON observacion.OBS_FUN_OBSERVADO = funcionario.FUN_RUN
    WHERE observacion.FUN_RUN = '.$rut.'
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="example" class="table is-striped">';
    echo "<thead>";
    echo "<tr>";
    //echo "<th>ID</th>";
    echo "<th>Funcionario</th>";
    echo "<th>Nota</th>";
    echo "<th>Fecha</th>";
    echo "<th>Acciones</th>";
    echo "</tr>";
    echo "</thead>";
    while($rowitem = mysqli_fetch_array($results)) {
        echo "<tr>";
        //echo "<td class='is-vcentered'>" . $rowitem['ID'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Funcionario'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Nota'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Fecha'] . "</td>";
        echo "<td class='is-vcentered'>
        <a class='has-background-success-light has-text-success' href='ver_observacion.php?id=".$rowitem['ID']."'><i class='fas fa-eye'></i><span class='is-hidden-mobile'> Ver</span></a>
        &nbsp;&nbsp;&nbsp;
        <!--<a class='has-background-warning-light has-text-warning' href='ed_observacion.php?id=".base64_encode($rowitem['ID'])."'><i class='fas fa-pencil'></i><span class='is-hidden-mobile'> Editar</span></a>
        &nbsp;&nbsp;&nbsp;-->
        <a class='has-background-danger-light has-text-danger' href='del_observacion.php?id=".base64_encode($rowitem['ID'])."'><i class='fas fa-trash'></i><span class='is-hidden-mobile'> Borrar</span></a>";
        ?>

        <?php
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

}


function get_observaciones_rut($rut)
{

    $agno_actual = date("Y");

    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 

    $query='
    SELECT
    OBS_ID AS ID,
    OBS_FUN_OBSERVADO AS RUT,
    CONCAT (UCASE(funcionario.FUN_PATERNO), " ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Funcionario,
    FORMAT((OBS_NOTA),2) AS Nota,
    OBS_FECHA AS Fecha
    FROM observacion
    INNER JOIN funcionario ON observacion.OBS_FUN_OBSERVADO = funcionario.FUN_RUN
    WHERE observacion.FUN_RUN = '.$rut.' AND YEAR(observacion.OBS_FECHA) = '.$agno_actual.'
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="example" class="table is-striped">';
        echo "<thead>";
        echo "<tr>";
        //echo "<th>ID</th>";
        echo "<th>Funcionario</th>";
        echo "<th>Nota</th>";
        echo "<th>Fecha</th>";
        echo "<th>Acciones</th>";
        echo "</tr>";
        echo "</thead>";
     while($rowitem = mysqli_fetch_array($results)) {
        echo "<tr>";
        //echo "<td class='is-vcentered'>" . $rowitem['ID'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Funcionario'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Nota'] . "</td>";
        echo "<td class='is-vcentered'>" . $rowitem['Fecha'] . "</td>";
        echo "<td class='is-vcentered'>
            <a class='has-background-success-light has-text-success' href='ver_observacion.php?id=".$rowitem['ID']."'><i class='fas fa-eye'></i><span class='is-hidden-mobile'> Ver</span></a>
            &nbsp;&nbsp;&nbsp;
            <!--<a class='has-background-warning-light has-text-warning' href='ed_observacion.php?id=".base64_encode($rowitem['ID'])."'><i class='fas fa-pencil'></i><span class='is-hidden-mobile'> Editar</span></a>
            &nbsp;&nbsp;&nbsp;-->
            <a class='has-background-danger-light has-text-danger' href='del_observacion.php?id=".base64_encode($rowitem['ID'])."'><i class='fas fa-trash'></i><span class='is-hidden-mobile'> Borrar</span></a>";
        ?>
        
        <?php
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>"; 
   
}


function del_observacion($id)
{
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    mysqli_query($link, "DELETE FROM observacion_detalle WHERE OBS_ID=".$id."");
	mysqli_query($link, "DELETE FROM observacion WHERE OBS_ID=".$id."");
    
}


function get_cumplimiento_rut($rut)
{
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT
    OBS_FUN_OBSERVADO AS RUT,
    CONCAT (UCASE(funcionario.FUN_PATERNO), " ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Funcionario,
    FORMAT(AVG(OBS_NOTA),2) AS Promedio,
    COUNT(OBS_FUN_OBSERVADO) AS Observaciones
    FROM observacion
    INNER JOIN funcionario ON observacion.OBS_FUN_OBSERVADO = funcionario.FUN_RUN
    WHERE observacion.FUN_RUN = '.$rut.'
    GROUP BY RUT
    ORDER BY Observaciones DESC
    
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="example" class="table is-striped">';
        echo "<thead>";
        echo "<tr>";
        //echo "<th>RUT</th>";
        echo "<th>Funcionario</th>";
        echo "<th>Promedio</th>";
        echo "<th>Observaciones</th>";
        echo "</tr>";
        echo "</thead>";
     while($rowitem = mysqli_fetch_array($results)) {
        echo "<tr>";
        //echo "<td>" . $rowitem['RUT'] . "</td>";
        echo "<td><a class='modal-button' href='detalle.php?uid=".$rowitem['RUT']."' data-target='modal-id' data-toggle='modal-id' data-id='".$rowitem['RUT']."'>".$rowitem['Funcionario']."</a></td>";

        //echo "<td><span class='modal-button' data-id='".$rowitem['RUT']."' data-target='modal-id'><a href=#?".$rowitem['RUT'].">".$rowitem['Funcionario']."</a></span></td>";
        echo "<td>" . $rowitem['Promedio'] . "</td>";
        echo "<td>" . $rowitem['Observaciones'] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; //end table tag

    

}

function get_ejes_rut($rut)
{

    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query="
    SELECT
    OBS_FUN_OBSERVADO AS Funcionario,
    UPPER(SUBSTRING(EJE_CORTO, 1, 3)) AS Abr,
    EJE_CORTO AS Eje,
    ROUND(AVG(ODE_VALOR),1) AS Promedio
    
    FROM observacion
    
    INNER JOIN observacion_detalle ON observacion.OBS_ID = observacion_detalle.OBS_ID
    INNER JOIN dimension ON observacion_detalle.DIM_ID = dimension.DIM_ID
    INNER JOIN eje ON dimension.EJE_ID = eje.EJE_ID
    
    WHERE observacion.OBS_FUN_OBSERVADO = ''.$rut.'' AND ODE_VALOR != 9
    GROUP BY eje_corto
    ";

    $results = mysqli_query($link,$query);

    echo "[";
     while($rowitem = mysqli_fetch_array($results)) {
        echo "'" . $rowitem['Eje'] . "'";
    }
    echo "]"; //end tag
}

function get_promedio_ejes_rut($rut)
{
    echo "[1.9, 1.3, 0.8, 1.6],";
}


function get_stats_rut($rut)
{
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT 
    OBS_FUN_OBSERVADO AS RUT,
    CONCAT (UCASE(funcionario.FUN_PATERNO), " ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Funcionario,
    FORMAT(OBS_NOTA,2) AS Promedio,
    observacion.OBS_FECHA AS Fecha
   FROM observacion
   INNER JOIN funcionario ON observacion.OBS_FUN_OBSERVADO = funcionario.FUN_RUN
   WHERE observacion.FUN_RUN = '.$rut.'
   ORDER BY Fecha DESC
    
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="example" class="table is-size-7 is-striped">';
        echo "<thead>";
        echo "<tr>";
        echo "<th>RUT</th>";
        echo "<th>Funcionario</th>";
        echo "<th>Promedio</th>";
        echo "<th>Fecha</th>";
        echo "</tr>";
        echo "</thead>";
     while($rowitem = mysqli_fetch_array($results)) {
        echo "<tr>";
        echo "<td>" . $rowitem['RUT'] . "</td>";
        echo "<td>" . $rowitem['Funcionario'] . "</td>";
        //echo "<td><a class='modal-button' href='detalle.php?uid=".$rowitem['RUT']."' data-target='modal-id' data-toggle='modal-id' data-id='".$rowitem['RUT']."'>".$rowitem['Funcionario']."</a></td>";

        //echo "<td><span class='modal-button' data-id='".$rowitem['RUT']."' data-target='modal-id'><a href=#?".$rowitem['RUT'].">".$rowitem['Funcionario']."</a></span></td>";
        echo "<td>" . $rowitem['Promedio'] . "</td>";
        echo "<td>" . $rowitem['Fecha'] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; //end table tag

    

}


function get_detalle($rut)
{
    $link = $GLOBALS['link'];
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query='
    SELECT OBS_ID AS ID,
    CONCAT (UCASE(funcionario.FUN_PATERNO), " ", UCASE(funcionario.FUN_MATERNO), " ", funcionario.FUN_NOMBRES) AS Evaluador,
    OBS_FECHA AS Fecha,
    format(OBS_NOTA,2) AS Nota
    FROM observacion
    INNER JOIN funcionario ON observacion.FUN_RUN = funcionario.FUN_RUN
    WHERE observacion.OBS_FUN_OBSERVADO = '.$rut.'
    ORDER BY Fecha
    ';

    $results = mysqli_query($link,$query);

    echo '<table id="example" class="table is-striped">';
        echo "<thead>";
        echo "<tr>";
        //echo "<th>RUT</th>";
        echo "<th>Evaluador</th>";
        echo "<th>Fecha</th>";
        echo "<th>Nota</th>";
        echo "</tr>";
        echo "</thead>";
     while($rowitem = mysqli_fetch_array($results)) {
        echo "<tr>";
        echo "<td>" . $rowitem['Evaluador'] . "</td>";
        echo "<td>" . $rowitem['Fecha'] . "</td>";
        echo "<td>" . $rowitem['Nota'] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; //end table tag

    

}

function get_pauta_from_obs($id)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT
    suc_id,
    cat_id,
    funcionario.fun_run,
    fun_es_activo,
    fun_es_docente,
    fun_es_asistente,
    fun_es_jefatura
    FROM observacion
    INNER JOIN funcionario ON observacion.obs_fun_observado = funcionario.FUN_RUN
    WHERE obs_id = '".$id."'";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            if ($row['cat_id'] == 7)
            {
                return 4;
                $result->free();
                exit;
            }
            if ($row['fun_es_jefatura'] == 1)
            {
                return 3;
                $result->free();
                exit;
            }
            if ($row['fun_es_docente'] == 1)
            {
                return 2;
                $result->free();
                exit;
            }
            if ($row['fun_es_asistente'] == 1)
            {
                return 1;
                $result->free();
                exit;
            }
        return null;            
        }
    }
	$result->free();
}


function get_tipo_from_obs($id)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT
    suc_id,
    cat_id,
    funcionario.fun_run,
    fun_es_activo,
    fun_es_docente,
    fun_es_asistente,
    fun_es_jefatura
    FROM observacion
    INNER JOIN funcionario ON observacion.obs_fun_observado = funcionario.FUN_RUN
    WHERE obs_id = '".$id."'";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            if ($row['cat_id'] == 7)
            {
                return 'PIE';
                $result->free();
                exit;
            }
            if ($row['fun_es_jefatura'] == 1)
            {
                return 'Jefatura';
                $result->free();
                exit;
            }
            if ($row['fun_es_docente'] == 1)
            {
                return 'Docente';
                $result->free();
                exit;
            }
            if ($row['fun_es_asistente'] == 1)
            {
                return 'Asistente';
                $result->free();
                exit;
            }
        return null;            
        }
    }
	$result->free();
}



function get_pie($sucursal, $selected)
{
    
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    if ($sucursal == 3)
    {
        $query = "
        SELECT funcionario.FUN_PATERNO,
        funcionario.FUN_MATERNO,
        funcionario.FUN_NOMBRES,
        funcionario.FUN_RUN,
        funcionario.SUC_ID
        FROM funcionario
        WHERE cat_id = 7 AND funcionario.FUN_ES_ACTIVO = 1
        ORDER BY FUN_PATERNO";
    }
    


    else{

        $query = "
    SELECT funcionario.FUN_PATERNO,
    funcionario.FUN_MATERNO,
    funcionario.FUN_NOMBRES,
    funcionario.FUN_RUN,
    funcionario.SUC_ID
    FROM funcionario
    WHERE cat_id = 7 AND funcionario.FUN_ES_ACTIVO = 1 AND SUC_ID = '".$sucursal."'
    ORDER BY FUN_PATERNO";
    }
    

    
    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            $pat = mb_strtoupper(($row['FUN_PATERNO']),'utf-8');
            $mat = mb_strtoupper(($row['FUN_MATERNO']),'utf-8');
            $nom = $row['FUN_NOMBRES'];
            $normalizado = $pat . " " . $mat . " " . $nom;
            if ($selected == $row['FUN_RUN'])
            {
                echo "<option selected value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }
            else
            {
                echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }
        }
    }
    
	$result->free();
}


function get_profesores($sucursal, $selected)
{
    if ($sucursal == 3) {
        $sucursal = "1' OR '2";
    }

    else { $sucursal = $sucursal; }


    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT funcionario.FUN_PATERNO,
    funcionario.FUN_MATERNO,
    funcionario.FUN_NOMBRES,
    funcionario.FUN_RUN,
    funcionario.SUC_ID
    FROM funcionario
    WHERE funcionario.fun_es_docente = 1 AND (SUC_ID = '".$sucursal."')
    ORDER BY SUC_ID, FUN_PATERNO";

    echo $query;

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            switch ($row['SUC_ID'])
            {
                case 1:
                    $suc = "[TH] ";
                    break;
                case 2:
                    $suc = "[SA] ";
                    break;
                case 3:
                    $suc = "[CZ] ";
                    break;
            }

            $pat = mb_strtoupper(($row['FUN_PATERNO']),'utf-8');
            $mat = mb_strtoupper(($row['FUN_MATERNO']),'utf-8');
            $nom = $row['FUN_NOMBRES'];
            $normalizado = $suc . $pat . " " . $mat . " " . $nom;
            if ($selected == $row['FUN_RUN'])
            {
                echo "<option selected value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }
            else
            {
                echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }
        }
    }
	$result->free();
}

function get_jefaturas($sucursal, $selected)
{
    if ($sucursal == 3) {
        $sucursal = "1 OR 2";
    }

    else { $sucursal = $sucursal; }

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT funcionario.FUN_PATERNO,
    funcionario.FUN_MATERNO,
    funcionario.FUN_NOMBRES,
    funcionario.FUN_RUN,
    funcionario.SUC_ID
    FROM funcionario
    WHERE funcionario.fun_es_jefatura = 1 AND (SUC_ID = ".$sucursal.")
    ORDER BY SUC_ID, FUN_PATERNO";



    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            switch ($row['SUC_ID'])
            {
                case 1:
                    $suc = "[TH] ";
                    break;
                case 2:
                    $suc = "[SA] ";
                    break;
                case 3:
                    $suc = "[CZ] ";
                    break;
            }

            $pat = mb_strtoupper(($row['FUN_PATERNO']),'utf-8');
            $mat = mb_strtoupper(($row['FUN_MATERNO']),'utf-8');
            $nom = $row['FUN_NOMBRES'];
            $normalizado = $suc . $pat . " " . $mat . " " . $nom;
            
            if ($selected == $row['FUN_RUN'])
            {
                echo "<option selected value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }
            else
            {
                echo $row['FUN_RUN'];
                echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }

        }
    }
	$result->free();
}

function get_asistentes($sucursal)
{
    if ($sucursal == 3) {
        $sucursal = "1 OR 2";
    }

    else { $sucursal = $sucursal; }

    //console_log($sucursal);
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT funcionario.FUN_PATERNO,
    funcionario.FUN_MATERNO,
    funcionario.FUN_NOMBRES,
    funcionario.FUN_RUN,
    funcionario.SUC_ID
    FROM funcionario
    WHERE funcionario.fun_es_asistente = 1 AND (SUC_ID = ".$sucursal.")
    ORDER BY SUC_ID, FUN_PATERNO";

    //console_log($query);

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            switch ($row['SUC_ID'])
            {
                case 1:
                    $suc = "[TH] ";
                    break;
                case 2:
                    $suc = "[SA] ";
                    break;
                case 3:
                    $suc = "[CZ] ";
                    break;
            }

            $pat = mb_strtoupper(($row['FUN_PATERNO']),'utf-8');
            $mat = mb_strtoupper(($row['FUN_MATERNO']),'utf-8');
            $nom = $row['FUN_NOMBRES'];
            $normalizado = $suc . $pat . " " . $mat . " " . $nom;
            echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
        }
    }
	$result->free();
}

function get_evaluadores($sucursal, $selected)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    if ($sucursal == 3)
    {
        $query = "
        SELECT funcionario.FUN_PATERNO,
        funcionario.FUN_MATERNO,
        funcionario.FUN_NOMBRES,
        funcionario.FUN_RUN,
        funcionario.SUC_ID
        FROM funcionario
        WHERE funcionario.fun_es_evaluador = 1
        ORDER BY FUN_PATERNO";        
    }
    else
    {

    $query = "
    SELECT funcionario.FUN_PATERNO,
    funcionario.FUN_MATERNO,
    funcionario.FUN_NOMBRES,
    funcionario.FUN_RUN,
    funcionario.SUC_ID
    FROM funcionario
    WHERE funcionario.fun_es_evaluador = 1 AND SUC_ID = '".$sucursal."'
    ORDER BY FUN_PATERNO";
    }


    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            $pat = mb_strtoupper(($row['FUN_PATERNO']),'utf-8');
            $mat = mb_strtoupper(($row['FUN_MATERNO']),'utf-8');
            $nom = $row['FUN_NOMBRES'];
            $normalizado = $pat . " " . $mat . " " . $nom;
            
            if ($selected == $row['FUN_RUN'])
            {
                echo "<option selected value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }
            else
            {
                echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
            }


        }
    }
	$result->free();
}


function get_materias($selected)
{

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT *
    FROM materias WHERE activo='0' ORDER BY mat_nombre ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            
            if ($selected == $row['mat_id'])
            {
                echo "<option selected value='".$row['mat_id']."'>".$row['mat_nombre']."</option>\n";
            }
            else
            {
                echo "<option value='".$row['mat_id']."'>".$row['mat_nombre']."</option>\n";
            }
            
            
        }
    }
	$result->free();
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

function get_curso($selected)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT cur_id, CONCAT(cur_nivel, '-', cur_letra) AS nom_curso
    FROM curso;";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            
            if ($selected == $row['cur_id'])
            {
                echo "<option selected value='".$row['cur_id']."'>".$row['nom_curso']."</option>\n";
            }
            else
            {
                echo "<option value='".$row['cur_id']."'>".$row['nom_curso']."</option>\n";
            }
        }
    }
	$result->free();
}


/*function get_cursos($selected)
{

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 

    $query = "
    SELECT *
    FROM curso";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            
            if ($selected == $row['cur_id'])
            {
                echo "<option selected value='".$row['cur_id']."'>".$row['cur_nivel']."</option>\n";
            }
            else
            {
                echo "<option value='".$row['cur_id']."'>".$row['cur_nivel']."</option>\n";
            }
            
            
        }
    }
	$result->free();
}*/


function get_curso_nom($id, $selected)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT cur_id, CONCAT(cur_nivel, '-', cur_letra) AS nom_curso
    FROM curso
    WHERE cur_id = '".$id."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['nom_curso'];
        }
    }
	$result->free();
}



function get_asignatura($id, $selected)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT mat_nombre
    FROM materias
    WHERE mat_id = '".$id."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['mat_nombre'];
        }
    }
	$result->free();
}



function get_nombre_from_funcionario($RUT)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT CONCAT(FUN_NOMBRES, ' ', FUN_PATERNO, ' ', FUN_MATERNO) AS NombreCompleto
    FROM funcionario
    WHERE FUN_RUN = '".$RUT."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['NombreCompleto'];
        }
    }
	$result->free();
}

function get_rut_from_funcionario($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT FUN_RUN
    FROM funcionario
    WHERE FUN_CORREO = '".$correo."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['FUN_RUN'];
        }
    }
	$result->free();
}

/*function get_rut_from_funcionario($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT FUN_RUN
    FROM funcionario
    WHERE FUN_CORREO = '".$correo."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['FUN_RUN'];
        }
    }
	$result->free();
}
*/

function get_sucursal_from_funcionario($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT SUC_ID
    FROM funcionario
    WHERE FUN_CORREO = '".$correo."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['SUC_ID'];
        }
    }
	$result->free();
}

function get_admin_from_funcionario($correo)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT FUNC_ADMIN
    FROM funcionario
    WHERE FUN_CORREO = '".$correo."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['FUNC_ADMIN'];
        }
    }
	$result->free();
}


function get_first_dim($tipo_obs)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query="
    SELECT DIM_ID FROM eje
    INNER JOIN dimension ON eje.EJE_ID = dimension.EJE_ID
    WHERE eje.TIPO_ID = '".$tipo_obs."' LIMIT 1;";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['DIM_ID'];
        }
    }
	$result->free();    
}

function get_count_dim($tipo_obs)
{
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query="
    SELECT COUNT(DIM_ID) as CONTEO FROM eje
    INNER JOIN dimension ON eje.EJE_ID = dimension.EJE_ID
    WHERE eje.TIPO_ID = '".$tipo_obs."';";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['CONTEO'];
        }
    }
	$result->free();    
}


//ESTADISTICAS EN PORTAL.PHP 2024-11-06

function getEvaluados($sucursal)
{
    $mysqli = new mysqli('localhost', 'iswafmec_delasan_ces', '123ces456@@', 'iswafmec_delasan_ces');
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT COUNT(func_rut) AS evaluados
    FROM funcionario
    WHERE funcionario.func_evaluado = 1 AND
    funcionario.func_activo = 1 AND
    funcionario.suc_id = '$sucursal'
    LIMIT 1
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['evaluados'];
        }
    }
    $result->free();
}

function getAutoEvaluados($sucursal)
{
    $mysqli = new mysqli('localhost', 'iswafmec_delasan_ces', '123ces456@@', 'iswafmec_delasan_ces');
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT COUNT(func_rut) AS aevaluados
    FROM funcionario
    WHERE funcionario.func_aevaluado = 1 AND
    funcionario.func_activo = 1 AND
    funcionario.suc_id = '$sucursal'
    LIMIT 1
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['aevaluados'];
        }
    }
    $result->free();
}

function getTotalFuncionarios($sucursal)
{
    $mysqli = new mysqli('localhost', 'iswafmec_delasan_ces', '123ces456@@', 'iswafmec_delasan_ces');
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT COUNT(func_rut) AS total
    FROM funcionario
    WHERE funcionario.func_activo = 1 AND
    funcionario.suc_id = '$sucursal'
    LIMIT 1
    ";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            return $row['total'];
        }
    }
    $result->free();
}


?>
