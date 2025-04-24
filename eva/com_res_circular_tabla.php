<?php
session_start();
require_once "config.php";
$rut = intval($_GET['q']);

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); 
    $mysqli->set_charset("utf8mb4");

    $query = "
    SELECT eje_desc AS Eje, FORMAT(ROUND(AVG(cir_eva_detalle.eva_det_valor),3),3) AS Promedio
    FROM cir_eva_detalle
    INNER JOIN cir_evaluacion ON cir_evaluacion.eva_id = cir_eva_detalle.eva_id
    INNER JOIN cir_dimension ON cir_dimension.dim_id = cir_eva_detalle.dim_id
    INNER JOIN cir_eje ON cir_eje.eje_id = cir_dimension.eje_id
    WHERE eva_evaluado = '$rut'
	AND IF(Month(eva_fecha) < 7, 1, 2) = IF(Month(Curdate()) < 7, 1, 2)
	AND Year(eva_fecha) = Year(Curdate()) 
    GROUP BY Eje WITH ROLLUP;
    "
    ?>
    <table id="resumen" class="display table table-hover table-sm">
    <thead>
        <tr>
        <th scope="col">Eje</th>
        <th scope="col">Promedio</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($result = $mysqli->query($query))
    {
        while ($row = $result->fetch_assoc())
        {
            
            if ($row['Eje'] != NULL)
            {
            ?>
                <tr>
                <td><?php echo $row['Eje'];?></td>
                <td><?php echo $row['Promedio'];?></td>
                </tr>

            <?php            
            }
            else{
                ?>
                <tr>
                <th scope="row">Promedio General</th>
                <th scope="row"><?php echo $row['Promedio'];?></th>
            </tr>
            <?php
            }
        }
        ?>
        


        </tbody>
        </table>
        <?php
        
    }
    else
    {
        echo "Ocurrió un error, reintente...";
    }
	$result->free();    

?>