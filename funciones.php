<?php

function get_pie($sucursal)
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
            echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
        }
    }
    
	$result->free();
}


function get_profesores($sucursal)
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
            echo "<option value='".$row['FUN_RUN']."'>".$normalizado."</option>\n";
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
    
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT funcionario.FUN_PATERNO,
    funcionario.FUN_MATERNO,
    funcionario.FUN_NOMBRES,
    funcionario.FUN_RUN,
    funcionario.SUC_ID
    FROM funcionario
    WHERE funcionario.fun_es_asistente = 1 AND SUC_ID = '".$sucursal."'
    ORDER BY FUN_PATERNO";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            $pat = mb_strtoupper(($row['FUN_PATERNO']),'utf-8');
            $mat = mb_strtoupper(($row['FUN_MATERNO']),'utf-8');
            $nom = $row['FUN_NOMBRES'];
            $normalizado = $pat . " " . $mat . " " . $nom;
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


function get_materias()
{
    
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");
    $query = "
    SELECT *
    FROM materias";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc())
        {
            echo "<option value='".$row['mat_id']."'>".$row['mat_nombre']."</option>\n";
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
?>
