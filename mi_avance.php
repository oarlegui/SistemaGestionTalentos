<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require_once "config.php";

$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:s", time() - 3600); //Hora Actual menos 1 hora... por horario de invierno blah blah, se va al carajo en localhost
$agno_actual = date("Y");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Gestión de Talentos</title>
    <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
    <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bm/jq-3.6.0/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/datatables.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bm/jq-3.6.0/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@creativebulma/bulma-tooltip@1.2.0/dist/bulma-tooltip.min.css">


    <!-- All of BulmaJS -->
    <script src="assets/js/bulma.js"></script>
    


    <!-- Ventanas Modales con FX -->
    <!--<link rel="stylesheet" href="https://unpkg.com/bulma-modal-fx/dist/css/modal-fx.min.css" />-->




    <script type="text/javascript" class="init">
<?php
$FechayHora = date('d-m-Y H:i:s', time());  
?>

    $(document).ready(function() {
        var table = $('#example').DataTable( {
            responsive: false,
            autoWidth: true,
            paging: true,

            order: [[2, 'desc']],


            "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).children("td").css("max-width", "50px");
              $(nRow).children("td").css("overflow", "hidden");
              $(nRow).children("td").css("white-space", "nowrap");
              $(nRow).children("td").css("text-overflow", "ellipsis");
            },



            //Declaro ruta del archivo de lenguaje español
            language: {url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'},
            buttons: [
                {
                extend: 'copy',
                text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Copiar Tabla"><i class="fas fa-copy"></i></span>'},
                {
                    extend: 'excel',
                    filename: '<?php echo 'Avance - '.get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"])).' - '.date('Y-m-d_His')?>',
                    sheetName: 'Avance',
                    messageTop: 'Avance de Observaciones para el usuario <?php echo get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"]));?>',
                    messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Descargar Excel"><i class="fas fa-file-excel"></i></span>'},
                {
                    extend: 'pdf',
                    filename: '<?php echo 'Avance - '.get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"])).' - '.date('Y-m-d_His')?>',
                    pageSize: 'LETTER',
                    messageTop: 'Avance de Observaciones para el usuario <?php echo get_nombre_from_funcionario(get_rut_from_funcionario($_SESSION["username"]));?>',
                    messageBottom: '<?php echo '\n\n'. 'Generado a las: '.$FechayHora; ?>',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Descargar PDF"><i class="fas fa-file-pdf"></i></span>'},
                {
                    extend: 'colvis',
                    text: '<span class="icon has-tooltip-right has-tooltip-arrow" data-tooltip="Visualizar Columnas"><i class="fas fa-eye"></i>&nbsp;</span>'},
                    ],

            //Hack feo para el lenguaje, debo crear los botones 10 ms despues de traducirlos
            initComplete: function(){
                setTimeout(function(){
                    table.buttons().container()
                    .appendTo( $('div.column.is-half', table.table().container()).eq(0) );

                }, 10);
                //Hack feo para corregir tamaño de botones, obviamente, despues de traducirlos.. por eso 20 ms.
                setTimeout(function(){
                    //$('button').addClass( "is-small" );
                    $('button').css({ "min-width": 50});
                    $('.is-light').addClass('is-link').removeClass('is-light');

                    /*$('td').css("max-width", "100px");
                    $('td').css("overflow", "hidden");
                    $('td').css("white-space", "nowrap");
                    $('td').css("text-overflow", "ellipsis");*/

                }, 20);
            }
        } );
    } );
    </script>
</head>

<script>
    document.addEventListener('DOMContentLoaded', () => {

    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

        // Add a click event on each of them
        $navbarBurgers.forEach( el => {
        el.addEventListener('click', () => {

            // Get the target from the "data-target" attribute
            const target = el.dataset.target;
            const $target = document.getElementById(target);

            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            el.classList.toggle('is-active');
            $target.classList.toggle('is-active');

        });
        });
    }
    });
</script>
</head>
<body class="has-navbar-fixed-top">
<?php
include "navbar.php";
?>
<?php
$rut = get_rut_from_funcionario($_SESSION["username"]);
?>

<div class="box">
<div class="content">
  <h1>Avance de Observaciones</h1>
  
  <?php get_observaciones_rut($rut); ?>
  </div>
  </div>
</body>
</html>

<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', () => {

  // Get all "navbar-burger" elements
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Check if there are any navbar burgers
  if ($navbarBurgers.length > 0) {

    // Add a click event on each of them
    $navbarBurgers.forEach( el => {
      el.addEventListener('click', () => {

        // Get the target from the "data-target" attribute
        const target = el.dataset.target;
        const $target = document.getElementById(target);

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        el.classList.toggle('is-active');
        $target.classList.toggle('is-active');

      });
    });
  }
});



</script>

