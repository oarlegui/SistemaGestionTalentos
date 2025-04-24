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
    
    
    console.log(rut);
    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    var modalContainer = document.getElementById('exampleModal');
    modalContainer.querySelector(".modal-title").innerHTML = "Detalle <strong>Evaluación Circular</strong>";
    myModal.show(rut);
    getResumen(rut);
    
}
</script>
<?php

require_once "config.php";

$dataPoints = array();
$coloresBarras = array();
$rojo = "#dc3545";
$azul = "#0d6efd";


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
SELECT
	funcionario.FUN_RUN AS RUN,
	sucursal.suc_id AS ID,
	sucursal.suc_nombre AS Sucursal,
	concat(UPPER(funcionario.FUN_PATERNO), ' ', funcionario.FUN_NOMBRES) AS x,
	format(AVG(eva_nota), 3) AS z,
	format(AVG(eva_det_valor), 3) AS y
FROM
	cir_evaluacion
INNER JOIN cir_eva_detalle ON
	cir_eva_detalle.eva_id = cir_evaluacion.eva_id
INNER JOIN funcionario ON
	cir_evaluacion.eva_evaluado = funcionario.FUN_RUN
INNER JOIN sucursal ON
	funcionario.SUC_ID = sucursal.suc_id
WHERE
	1 = 1
	AND YEAR(eva_fecha) = YEAR(CURDATE())
	AND IF(MONTH(eva_fecha) < 7,1,2) = IF(MONTH(CURDATE())<7,1,2)
	AND funcionario.FUN_ES_ACTIVO = 1
GROUP BY
	eva_evaluado
ORDER BY
	y DESC,
	ID ASC"
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

        array_push($dataPoints, array("label"=> $row->x, "y"=> $row->y, "color"=>$row->ID, "rut"=>$row->RUN));
        
    }
    $link = null;
    
}
catch(\PDOException $ex){
    print($ex->getMessage());
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

    <script>
        $(document).ready(function() {
            var table = $('table.display').DataTable( {
                lengthChange: false,
                paging: false, 
                order: [[5, 'desc']],
                language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},
                dom: "<'row'<'col-sm-12 col-md-3'B><'col-sm-12 col-md-5'l><'col-sm-12 col-md-4'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                columnDefs:
                [
                    {
                    target: 0,
                    //visible: false,
                    searchable: true,
                    },
                ],
                buttons: [
                {
                extend: 'excel',
                sheetName: 'EVA Circular - 2024-01',
                filename: '* - Resultados Evaluación Circular',
                text: '<i class="bi bi-file-earmark-spreadsheet"></i> XLS',
                },
                {
                extend: 'pdf',
                filename: '* - Resultados Evaluación Circular',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                pageSize: 'LETTER',

                }]
            } );
        } );
    </script>    

<script>

window.onload = function () {

    var $rojo = "#dc3545";
    var $azul = "#0d6efd";

var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    exportEnabled: true,
    axisX: {
        labelFontSize: 10,
        interval: 1,
        fontFamily: "system-ui",
        labelAngle: 0,
    },
    axisY: {
        gridColor: "lightgray",
        maximum: 4,
        minimum: 0,
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
        click: onClick,
        indexLabel: "{y}",
        indexLabelFontSize: 10,
        indexLabelFontWeight: "bold",
        indexLabelPlacement: "auto",
        indexLabelOrientation: "vertical",
        showInLegend: false,
        name: "2024-01",
        yValueFormatString: "0.000",
        type: "column",
        dataPoints: <?php echo json_encode($dataPoints, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK); ?>
    },
    {   
        name: "2023-02",
        color: "lightgray",
        yValueFormatString: "0.000",
        showInLegend: false,
        type: "column", 
        dataPoints: [
            {"x":0,"label":"RAMIREZ Fabian Antonio","y":3.733},
            {"x":1,"label":"REYES Carla Hailin","y":3.448},
            {"x":2,"label":"CARRASCO Paulina Jesus","y":3.330},
            {"x":3,"label":"CASTRO Viviana Andrea","y":3.515},
            {"x":4,"label":"MUÑOZ Camila Leandra","y":3.431},
            {"x":5,"label":"ALEGRÍA Katherine Beatriz","y":3.536},
            {"x":6,"label":"MUÑOZ Jaime Manuel","y":3.416},
            {"x":7,"label":"DOMÍNGUEZ Marcela Alicia","y":3.542},
            {"x":8,"label":"ARAVENA Juan Eduardo","y":3.233},
            {"x":9,"label":"MEDEL Valentina Soledad","y":3.324},
            {"x":10,"label":"PEREZ Eliana Del Carmen","y":0},
            {"x":11,"label":"LAGOS Denisse","y":3.267},
            {"x":12,"label":"ROGEL Laura Inés","y":3.348},
            {"x":13,"label":"YAÑEZ Mariela Del Rosario","y":0},
            {"x":14,"label":"VIVEROS María José","y":3.368},
            {"x":15,"label":"PIZARRO Ana Maria","y":3.196},
            {"x":16,"label":"SEPÚLVEDA María Magdalena","y":3.280},
            {"x":17,"label":"LEIVA Verónica Arlette","y":3.087},
            {"x":18,"label":"FARÍAS Maria Isabel","y":0}]

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
                <div class="alert alert-warning" role="alert">
                <i class="bi bi-lightbulb"></i> Puede hacer click sobre las barras del gráfico para visualizar un detalle de la evaluación
                    </div>                
                <div class="row">
                <div class="col-md-12 my-1 d-flex justify-content-center">
                        <p><small><i class="bi bi-square-fill text-primary"></i> Talcahuano <i class="bi bi-square-fill text-danger"></i> San Antonio <i style="color: lightgray;" class="bi bi-square-fill"></i> 2023-02</small></p>
                    </div>
				    <div id="chartContainer" class="col-md-12"></div>
                </div>
                <div class="row">                
                    
                    <div style="margin-top: 450px;" id="tableContainer" class="col-md-12">
                        <?php resultadosEvaluacionCircular();?>
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