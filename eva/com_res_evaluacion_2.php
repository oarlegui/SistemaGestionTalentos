<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
require_once "config.php";

// Obtener el nombre del usuario
$nombre = getFullname($_SESSION["username"]);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Talentos</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #0d6efd;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: #fff !important;
        }
        .logo {
            height: 50px; /* Ajusta el tamaño del logo */
        }
        .header-content {
            text-align: center;
            padding: 20px 0;
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
    </style>
</head>
<body>
    <!-- Cabecera personalizada -->
    <div class="header-content">
        <!-- Logo centrado -->
        <img src="assets/img/logo.png" alt="Logo" class="logo">

        <!-- Menú centrado -->
        <div class="menu">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-house-door"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-person"></i> Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-gear"></i> Configuración</a>
                </li>
            </ul>
        </div>

        <!-- Nombre del usuario -->
        <div class="user-info">
            <span><i class="bi bi-person-circle"></i> <?php echo $nombre; ?></span>
        </div>
    </div>

    <?php
    $correo = $_SESSION["username"];
    $rut = get_rut_from_correo($correo);
    $tipo = get_tipo_from_rut($rut);
    $codigo = get_codigo_from_tipo($tipo);
    $_SESSION["rut"] = $rut;
    $_SESSION["tipo"] = $tipo;
    $_SESSION["codigo"] = $codigo;
    $full_rut = $rut . dv($rut);

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
        <h4 class="text-primary"><i class="bi bi-bar-chart-line-fill"></i> Resultados de <strong>Evaluación de Desempeño</strong></h4>
        <p class="text-muted">Estos son los resultados finales del proceso de Evaluación del Desempeño. Puede filtrar usando la caja de búsqueda.</p>

        <!-- Botones de filtrado -->
        <div class="btn-group mb-3" role="group" aria-label="Sucursal">
            <button type="button" id="btn_th" class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel-fill"></i> Talcahuano</button>
            <button type="button" id="btn_sa" class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel-fill"></i> San Antonio</button>
        </div>
        <div class="btn-group mb-3" role="group" aria-label="Categorias">
            <button type="button" id="btn_cat_1" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> 1° Ciclo</button>
            <button type="button" id="btn_cat_2" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> 2° Ciclo</button>
            <button type="button" id="btn_cat_3" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> 3° Ciclo</button>
            <button type="button" id="btn_cat_4" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> Directivos</button>
            <button type="button" id="btn_cat_5" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> Profesionales</button>
            <button type="button" id="btn_cat_6" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> Administrativos</button>
            <button type="button" id="btn_cat_7" class="btn btn-outline-dark btn-sm"><i class="bi bi-funnel-fill"></i> Auxiliares</button>
        </div>

        <button type="button" id="btn_borra_filtros" class="btn btn-outline-danger btn-sm mb-3"><i class="bi bi-eraser-fill"></i> Borrar Filtros</button>

        <hr>

        <!-- Tabla de Evaluación -->
        <div class="col-lg-12 px-0">
            <table id="reporte" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sucursal</th>
                        <th>Categoría</th>
                        <th>Nombre</th>
                        <th>Puntaje</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php reporteEvaluacion(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#reporte').DataTable({
                lengthChange: true,
                pageLength: 10, // Número de filas por página
                paging: true, // Habilitar paginación
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-spreadsheet"></i> Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Imprimir',
                        className: 'btn btn-secondary'
                    }
                ]
            });

            // Botones de filtrado
            $('#btn_th').on('click', function() {
                table.column(0).search('Talcahuano').draw();
            });

            $('#btn_sa').on('click', function() {
                table.column(0).search('San Antonio').draw();
            });

            $('#btn_cat_1').on('click', function() {
                table.column(1).search('1° Ciclo').draw();
            });

            $('#btn_cat_2').on('click', function() {
                table.column(1).search('2° Ciclo').draw();
            });

            $('#btn_cat_3').on('click', function() {
                table.column(1).search('3° Ciclo').draw();
            });

            $('#btn_cat_4').on('click', function() {
                table.column(1).search('Directivos').draw();
            });

            $('#btn_cat_5').on('click', function() {
                table.column(1).search('Profesionales').draw();
            });

            $('#btn_cat_6').on('click', function() {
                table.column(1).search('Administrativos').draw();
            });

            $('#btn_cat_7').on('click', function() {
                table.column(1).search('Auxiliares').draw();
            });

            $('#btn_borra_filtros').on('click', function() {
                table.search('').columns().search('').draw();
            });
        });
    </script>
</body>
</html>