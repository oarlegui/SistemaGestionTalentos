<?php
session_start();
?>
<script>
function getResumen(rut) {

var modalContainer = document.getElementById('exampleModal');

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    modalContainer.querySelector(".modal-body").innerHTML = this.responseText;
  }
};
xmlhttp.open("GET","com_res_circular_tabla.php?q="+rut,true);
xmlhttp.send();
}

function muestraDetalle(rut) {
    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    var modalContainer = document.getElementById('exampleModal');
    modalContainer.querySelector(".modal-title").innerHTML = "Detalle <strong>Evaluación Circular</strong>";
    myModal.show(rut);
    getResumen(rut);
}
</script>
<?php

require_once "config.php";
$evaluado = $_SESSION["rut"];
$dataPoints = array();



try{
    $link = new \PDO(   'mysql:host=localhost;dbname=iswafmec_delasan_talentos;charset=utf8mb4',
                        'iswafmec_delasan_talentos', 
                        'talentos107547290garcia519',
                        array(
                            \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            \PDO::ATTR_PERSISTENT => false
                        )
                    );
    
    $handle = $link->prepare("
	SELECT cir_eje.eje_id as id, eje_desc AS x, AVG(eva_det_valor) AS y, if (MONTH(eva_fecha) < 7,1,2) as semestre FROM cir_eva_detalle
    INNER JOIN cir_evaluacion ON cir_evaluacion.eva_id = cir_eva_detalle.eva_id
    INNER JOIN cir_dimension ON cir_dimension.dim_id = cir_eva_detalle.dim_id
    INNER JOIN cir_eje ON cir_eje.eje_id = cir_dimension.eje_id
    WHERE eva_evaluado = '$evaluado'
    AND IF (MONTH(eva_fecha) < 7,1,2) = IF (MONTH(CURDATE())<7,1,2)
    AND YEAR(eva_fecha) = YEAR(CURDATE())
    GROUP BY cir_eje.eje_id;
    "
    ); 
    $handle->execute(); 
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
     
    

    foreach($result as $row){
        array_push($dataPoints, array("label"=> $row->x, "y"=> $row->y));
        
    }
    $link = null;
    
}
catch(\PDOException $ex){
    print($ex->getMessage());
}

function build_table($array){
    // start table
    $html = '<table id="resultados" class="display table table-hover table-sm">';
    // header row
    $html .= '<thead><tr>';
    foreach($array[0] as $key=>$value){
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
    $html .= '</tr></thead>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
            $html .= '<td>' . htmlspecialchars($value2) . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

?>
<!DOCTYPE html>
<html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>

	<!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

    <style>

    </style>

	<!-- JS -->
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>    
    <script src="https://cdn.datatables.net/plug-ins/1.13.4/api/order.neutral().js"></script>    



    <!-- Cambio el nombre de las columnas en la Tabla bajo Evaluacion Circular -->
    <script>
        $(document).ready(function() {
            var table = $('table.display').DataTable( {
                lengthChange: false,
                paging: false,
                info: false,
                order: [[1, 'desc']],
                searching: false,                
                columns: [
                { title: 'Eje' },
                { title: 'Promedio Evaluación' }
                ],
                language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},

            } );
        } );
    </script>    

<script>

window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    exportEnabled: true,
    theme: "light2",

    axisX: {
        title: "Eje",
		labelFontSize: 12,
        interval: 1,
        fontFamily: "system-ui",
        labelAngle: 0,
    },
    axisY: {
        title: "Promedio Evaluación",
        gridColor: "lightgray",
        labelFontSize: 12,
        fontFamily: "system-ui",
    },
    title:{
        text: "",
        fontFamily: "system-ui",
      },

      toolTip: {
			fontColor: "black",
			Content: "{x} : {y}"
		},

    data: [{
        //click: onClick,
        indexLabel: "{y}",
        indexLabelFontSize: 12,
        indexLabelFontWeight: "bold",
        indexLabelPlacement: "auto",
        indexLabelOrientation: "vertical",
        showInLegend: false,
        name: "2024-01",
        yValueFormatString: "0.000",
        type: "column",
        dataPoints: <?php echo json_encode($dataPoints, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); ?>
    }
    ]
});
chart.render();



function onClick(e) {
    
    var nombreFuncionario = e.dataPoint.label;
    var rutFuncionario = e.dataPoint.rut;
    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    var modalContainer = document.getElementById('exampleModal');
    modalContainer.querySelector(".modal-title").innerHTML = "Detalle - "+nombreFuncionario;
    myModal.show(rutFuncionario);
    getResumen(rutFuncionario);
    
}
 
}
</script>    
  </head>
	<body>
    <?php include_once "navbar.php";?>
		<div class="container-fluid w-75">
			<div class="row">
				<div class="col-md-12 my-5 d-flex">
					<h4>Resultados <strong>Evaluación Circular</strong> 2024-01</h4>
				</div>
                <!-- <div class="alert alert-warning" role="alert">
                <i class="bi bi-lightbulb"></i> Puede hacer click sobre las barras del gráfico para visualizar un detalle de la evaluación
                    </div> -->                
                <div class="row">
                <div class="col-md-12 my-1 d-flex justify-content-center">
                    </div>
				    <div id="chartContainer" class="col-md-12"></div>
                </div>
                <div class="row">                
                    
                    <div style="margin-top: 450px;" id="tableContainer" class="col-md-12">
                        <?php echo build_table($dataPoints); ?>
                        <?php //resultadosEvaluacionCircular();?>
                    </div>
                </div>

                    <!-- Modal -->
                    <div class="modal fade modal-md" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title fs-5" id="exampleModalLabel"></h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                        </div>
                    </div>
                    </div>                
			</div>

		</div>
    </body>
</html>
