<?php
    
$dataPoints = array();
$coloresBarras = array();
$rojo = "#dc3545";
$azul = "#0d6efd";

//Best practice is to create a separate file for handling connection to database
try{
        // Creating a new connection.
    // Replace your-hostname, your-db, your-username, your-password according to your database
    $link = new \PDO(   'mysql:host=localhost;dbname=delasan_talentos;charset=utf8mb4', //'mysql:host=localhost;dbname=canvasjs_db;charset=utf8mb4',
                        'delasan_talentos', //'root',
                        'talentos107547290garcia519', //'',
                        array(
                            \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            \PDO::ATTR_PERSISTENT => false
                        )
                    );
    
    $handle = $link->prepare("
    SELECT
    sucursal.suc_id AS ID,
    sucursal.suc_nombre AS Sucursal, concat(UPPER(funcionario.FUN_PATERNO), ' ', funcionario.FUN_NOMBRES) AS x,
    format(AVG(eva_nota),3) AS y
    FROM cir_evaluacion
    INNER JOIN funcionario ON cir_evaluacion.eva_evaluado = funcionario.FUN_RUN
    INNER JOIN sucursal ON funcionario.SUC_ID = sucursal.suc_id
    GROUP BY eva_evaluado
    ORDER BY y DESC"
    ); 
    $handle->execute(); 
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
     
    

    foreach($result as $row){
        if ($row->ID == 1)
        {
            $row->ID = $azul;
            
        } else{
            $row->ID = $rojo;
            
        }

        array_push($dataPoints, array("label"=> $row->x, "y"=> $row->y, "color"=>$row->ID));
        
    }
    $link = null;
    
}
catch(\PDOException $ex){
    print($ex->getMessage());
}
    
?>
<!DOCTYPE HTML>
<html>
<head>  
<script>

window.onload = function () {

    var $rojo = "#dc3545";
    var $azul = "#0d6efd";

    //CanvasJS.addColorSet("CES",<?php echo json_encode($coloresBarras, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); ?>);
    
    
var chart = new CanvasJS.Chart("chartContainer", {
    //colorSet: "CES",
    animationEnabled: true,
    exportEnabled: true,
    axisX: {
        labelFontSize: 14,
        interval: 1,
        fontFamily: "system-ui",
        labelAngle: 0,

    },
    axisY: {
        minimum: 0,
        labelFontSize: 14,
        fontFamily: "system-ui",
    },
    //theme: "light1", // "light1", "light2", "dark1", "dark2"
    title:{
        text: "Resultados Evaluación Circular",
        fontFamily: "system-ui",
      },
    data: [{
        indexLabel: "{y}",
        showInLegend: false,
        name: "2023-01",
        yValueFormatString: "0.000",
        type: "column", //change type to bar, line, area, pie, etc  
        dataPoints: <?php echo json_encode($dataPoints, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); ?>
    },
    {   
        name: "2022-02",
        //axisYType: "secondary",
        color: "lightgray",
        
        showInLegend: true,
        type: "column", //change type to bar, line, area, pie, etc  
        dataPoints: [{"label":"RAMIREZ Mireya Carolina","y":3.987},{"label":"DOMÍNGUEZ Marcela Alicia","y":3.16},{"label":"CARRASCO Paulina Jesus","y":3.595},{"label":"ALEGRÍA Katherine Beatriz","y":0},{"label":"CASTRO Viviana Andrea","y":3.563},{"label":"REYES Carla Hailin","y":3.152},{"label":"VIVEROS María José","y":3.68},{"label":"LAGOS Denisse","y":3.488},{"label":"BONILLA Viviana Patricia","y":3.339},{"label":"VENEGAS Angela Francisca","y":3.532},{"label":"MUÑOZ Camila Leandra","y":0},{"label":"MUÑOZ Jaime Manuel","y":3.271},{"label":"ROGEL Laura Inés","y":0},{"label":"PIZARRO Ana Maria","y":3.313},{"label":"MEDEL Valentina Soledad","y":3.196},{"label":"SEPÚLVEDA María Magdalena","y":3.536},{"label":"ARAVENA Juan Eduardo","y":3.187},{"label":"LEIVA Verónica Arlette","y":3.347},{"label":"NARANJO Narcisa Margarita","y":3.037}]

    }
    ]
});
chart.render();
    
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 50%; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>                              