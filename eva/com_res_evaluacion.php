<?php
// Inicializar la sesión
session_start();

/* Habilitar el reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);*/

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

// Incluir configuración de la base de datos
require_once "config.php";

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Resultados Evaluación de Desempeño</title>

    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

    <style>
        table.table-fit {
            width: 90 !important;
            table-layout: auto !important;
        }

        table.table-fit thead th,
        table.table-fit tfoot th {
            width: auto !important;
        }

        table.table-fit tbody td,
        table.table-fit tfoot td {
            width: auto !important;
        }

        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background-color: #23406c;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff !important;
        }

        .logo {
            height: 50px;
            /* Ajusta el tamaño del logo */
        }

        .header-content {
            text-align: center;
            padding: 20px 0;
            background-color: #23406c;
            /* Color de fondo del encabezado */
            color: #fff;
            /* Color del texto */
            width: 100%;
            margin-bottom: 20px;
        }

        .menu {
            margin-top: 10px;
        }

        .user-info {
            color: #fff;
            margin-top: 10px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }

        .btn-group {
            margin: 10px 0;
        }

        .btn-print {
            background-color: #6c757d;
            /* Gris */
            border-color: #6c757d;
            color: #fff;
        }

        .btn-xls {
            background-color: #28a745;
            /* Verde */
            border-color: #28a745;
            color: #fff;
        }

        .btn-pdf {
            background-color: #dc3545;
            /* Rojo */
            border-color: #dc3545;
            color: #fff;
        }
    </style>
</head>

<?php

// Conexión a la base de datos
$conexion = new mysqli("localhost", "iswafmec_delasan_talentos", "talentos107547290garcia519", "iswafmec_delasan_talentos");

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener correo del usuario desde la sesión
$correo = $_SESSION["username"];

// Preparar la consulta SQL
$sql = "SELECT CAT_ID, SUC_ID FROM funcionario WHERE FUN_CORREO = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si se encontró al funcionario
if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $func_tip_id = $row['CAT_ID'];
    $suc_id = $row['SUC_ID'];

    /* Comprobar si el tipo de funcionario es 1 o 2
    if ($func_tip_id == 1 || $func_tip_id == 2 || $func_tip_id == 4) {
        // Mostrar la página
        
    } else {
        // Usuario no autorizado
       echo "<div class='container my-5'>";
        muestraAlerta("<i class='bi bi-exclamation-circle-fill'></i> Usted <strong>no está autorizado</strong> para acceder a esta función", "danger");

        echo "<a href='javascript:history.back()'><button type='button' class='btn btn-secondary'><i class='bi bi-arrow-return-left'></i> Regresar</button></a>";
        echo "</div>";
        exit;
    }
} else {
    echo "Funcionario no encontrado.";
    exit;
    */
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>

<body>
    
    <!-- Cabecera personalizada -->
    <div class="header-content">
        <!-- Logo centrado -->
        <!-- <img src="assets/img/logo.png" alt="Logo" class="logo"> -->

        <!-- Menú centrado -->
        <div class="menu">
            <ul class="nav justify-content-center">
                <?php include_once "navbar.php"; ?>
            </ul>
        </div>

        <?php
        $nombre = getFullname($_SESSION["username"]);
        $correo = $_SESSION["username"];
        $rut = get_rut_from_correo($correo);
        $tipo = get_tipo_from_rut($rut);
        $codigo = get_codigo_from_tipo($tipo);
        $_SESSION["rut"] = $rut;
        $_SESSION["tipo"] = $tipo;
        $_SESSION["codigo"] = $codigo;
        $full_rut = $rut . dv($rut);
        ?>
        <!-- Nombre del usuario -->
        <div class="user-info1">
            <span><i class="bi bi-person-circle1"></i>Bienvenid@, <?php echo $nombre  ?></span>
        </div>
    </div>

    <?php
    // Verificar que es evaluador y además es nivel 1
    if (esComite($full_rut) >= 1) {
        // Autorizado
    } else {
        echo "<div class='container my-5'>";
        muestraAlerta("<i class='bi bi-exclamation-circle-fill'></i> Usted <strong>no está autorizado</strong> para acceder a esta función", "danger");

        echo "<a href='javascript:history.back()'><button type='button' class='btn btn-secondary'><i class='bi bi-arrow-return-left'></i> Regresar</button></a>";
        echo "</div>";
        exit;
    }
    ?>

    <div class="container my-5">
        <h4>Resultados de <strong>Evaluación de Desempeño</strong></h4>
        <p>Estos son los resultados finales del proceso de Evaluación del Desempeño. Puede filtrar usando la caja de búsqueda</p>

        <div class="center-container">
            <div class="btn-group" role="group" aria-label="Sucursal">
            
            <!-- Bloqueo de Botones de Sucursales -->   
            <?php     
               if ($suc_id == '1') {
                echo ' <button data-bs-toggle="button" type="button" id="btn_th" class="btn btn-outline-primary btn-sm"><i class="bi bi-building"></i> Talcahuano</button>
                <button data-bs-toggle="button" type="button" id="btn_sa" class="btn btn-outline-primary btn-sm" disabled><i class="bi bi-building"></i> San Antonio</button>';
                } else if ($suc_id == '2') {
                echo ' <button data-bs-toggle="button" type="button" id="btn_th" class="btn btn-outline-primary btn-sm" disabled><i class="bi bi-building"></i> Talcahuano</button>
                <button data-bs-toggle="button" type="button" id="btn_sa" class="btn btn-outline-primary btn-sm"><i class="bi bi-building"></i> San Antonio</button>';
                } else if ($suc_id == '3') {
                echo ' <button data-bs-toggle="button" type="button" id="btn_th" class="btn btn-outline-primary btn-sm" ><i class="bi bi-building"></i> Talcahuano</button>
                <button data-bs-toggle="button" type="button" id="btn_sa" class="btn btn-outline-primary btn-sm"><i class="bi bi-building"></i> San Antonio</button>';
                }
            ?>
               
            
           <!-- Fuerza a el click automatico segun suc_id --> 
            <script>
                // Obtiene el suc_id de PHP en JavaScript
                var suc_id = <?php echo json_encode($suc_id); ?>;
            
                window.onload = function() {
                    if (suc_id == 1) {
                        document.getElementById("btn_th").click();
                    } else if (suc_id == 2) {
                        document.getElementById("btn_sa").click();
                    }
                };
            
            </script>

                
            </div>
            <div class="btn-group" role="group" aria-label="Categorias">
                <button data-bs-toggle="button" type="button" id="btn_cat_1" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> 1° Ciclo</button>
                <button data-bs-toggle="button" type="button" id="btn_cat_2" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> 2° Ciclo</button>
                <button data-bs-toggle="button" type="button" id="btn_cat_3" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> 3° Ciclo</button>
                <button data-bs-toggle="button" type="button" id="btn_cat_4" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> Directivos</button>
                <button data-bs-toggle="button" type="button" id="btn_cat_5" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> Profesionales</button>
                <button data-bs-toggle="button" type="button" id="btn_cat_6" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> Administrativos</button>
                <button data-bs-toggle="button" type="button" id="btn_cat_7" class="btn btn-outline-dark btn-sm"><i class="bi bi-binoculars-fill"></i> Auxiliares</button>
            </div>

            <button data-bs-toggle="button" type="button" id="btn_borra_filtros" class="btn btn-outline-danger btn-sm"><i class="bi bi-eraser-fill"></i> Borra Filtros</button>
        </div>

        <hr>
        <div class="col-lg-0 px-0">
           
           <?php 
            //Tabla de Evaluación
            reporteEvaluacion(); 
            
            ;?>
            
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
            var table = $('table.display').DataTable({
                lengthChange: false,
                pageLength: 25,

                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
                },
                dom: "<'row'<'col-sm-12 col-md-3'B><'col-sm-12 col-md-5'l><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                columnDefs: [{
                    target: 0,
                    visible: false,
                    searchable: true,
                }, ],
                rowGroup: {
                    dataSrc: 0
                },

                order: [
                    [0, 1, 2, 'asc']
                ],

                buttons: [{
                        extend: 'excel',
                        sheetName: 'Evaluación de Desempeño - 2024',
                        filename: '* - Resultados Evaluación',
                        text: '<i class="bi bi-file-earmark-spreadsheet"></i> XLS',
                        className: 'btn-xls'
                    },
                    {
                        extend: 'pdf',
                        filename: '* - Resultados Evaluación',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        pageSize: 'LETTER',
                        className: 'btn-pdf'
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Imprimir',
                        className: 'btn-print'
                    }
                ]
            });
        });
    </script>

    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
    $(document).ready(function() {

        function borraFiltros() {
            table.search('').columns().search('').draw();
            table.order.neutral().draw();
            table.order([[0, 1, 2, 'asc']]).draw();
            $('[id^="btn_"]').removeClass("active"); // Quitar la clase "active" de todos los botones
        }

        function filtraSucursal(value) {
            table.column(0).search(value).draw();
            $('[id^="btn_th"], [id^="btn_sa"]').removeClass("active"); // Desactivar otros botones de sucursal
            $(`#btn_${value.toLowerCase().replace(/ /g, '_')}`).addClass("active"); // Activar el botón seleccionado
        }

        function filtraCategoria(value) {
            table.column(1).search(value).draw();
            $('[id^="btn_cat_"]').removeClass("active"); // Desactivar otros botones de categoría
            $(`#btn_cat_${value.charAt(0)}`).addClass("active"); // Activar el botón seleccionado
        }

        var table = $('#reporte').DataTable();

        $('#btn_borra_filtros').on('click', function() {
            borraFiltros();
        });

        $('#btn_th').on('click', function() {
            filtraSucursal('Talcahuano');
        });

        $('#btn_sa').on('click', function() {
            filtraSucursal('San Antonio');
        });

        $('#btn_cat_1').on('click', function() {
            filtraCategoria('Primer Ciclo');
        });

        $('#btn_cat_2').on('click', function() {
            filtraCategoria('Segundo Ciclo');
        });

        $('#btn_cat_3').on('click', function() {
            filtraCategoria('Tercer Ciclo');
        });

        $('#btn_cat_4').on('click', function() {
            filtraCategoria('Directivo');
        });

        $('#btn_cat_5').on('click', function() {
            filtraCategoria('Profesional de Apoyo');
        });

        $('#btn_cat_6').on('click', function() {
            filtraCategoria('Administrativo');
        });

        $('#btn_cat_7').on('click', function() {
            filtraCategoria('Auxiliar');
        });
    });
</script>
</body>

</html>