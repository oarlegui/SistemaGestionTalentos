<?php
    header('Content-Type: application/json');
    $suc = $_POST["suc"];
    $conn = mysqli_connect("localhost","delasan_talentos","talentos107547290garcia519","delasan_talentos");
    $sqlQuery = "
    SELECT sucursal.suc_id AS ID, sucursal.suc_nombre AS Sucursal, concat(funcionario.FUN_RUN,' - ',UPPER(funcionario.FUN_PATERNO), ' ', funcionario.FUN_NOMBRES) AS Evaluador, format(AVG(eva_nota),3) AS Nota FROM cir_evaluacion
INNER JOIN funcionario ON cir_evaluacion.eva_evaluado = funcionario.FUN_RUN
INNER JOIN sucursal ON funcionario.SUC_ID = sucursal.suc_id
WHERE  1 = 1
	AND IF(Month(eva_fecha) < 7, 1, 2) = IF(Month(Curdate()) < 7, 1, 2)
	AND Year(eva_fecha) = Year(Curdate()) 

GROUP BY eva_evaluado
ORDER BY Nota DESC
    ";
    $result = mysqli_query($conn,$sqlQuery);
    $data = [];
    foreach ($result as $row) {
        $data[] = $row;
    }
    mysqli_close($conn);
    echo json_encode($data);
?>