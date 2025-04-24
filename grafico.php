<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Gestión de Talentos</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@creativebulma/bulma-tooltip@1.2.0/dist/bulma-tooltip.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
<div class="box">
<div class="content">
<h1>Estadísticas de Observaciones</h1>
<div class="columns">
<div class="column is-half has-background-info-light has-text-black">Tabla de Resultados</br>
    <a id="10754729" onclick="changeData();" href="#">10754729</a></br>
    <a id="14207618" onclick="changeData();" href="#">14207618</a>
</div>
  <div id='grafico' class="column">
  <canvas id="myChart"></canvas>
  </div>
</div>


</body>



<script>
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'radar',
    data: {
        labels: ['Ambiente', 'Enseñanza', 'Preparación', 'Responsabilidad'],
        datasets: [{
            label: '15564292',
            data: [1.9, 1.3, 0.8, 1.6],
            fill: true,
            /*backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgb(255, 99, 132)',
            pointBackgroundColor: 'rgb(255, 99, 132)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(255, 99, 132)'*/
        }]
    },
    options: {
        scales: {
            r:{
            suggestedMin: 0,
            suggestedMax: 2
            }
        },
        elements: {
            line: {
                borderWidth: 3
            }
        }
    }
});

function changeData($rut){
    $('#myChart').remove();
    $('#grafico').append('<canvas id="myChart"></canvas>');
    canvas = document.querySelector('#myChart');
    ctx = canvas.getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Ambiente', 'Enseñanza', 'Preparación', 'Participación'],
            datasets: [{
                label: '107547290',
                data: [1, 1.3, 1.2, 1.4],
                fill: true,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgb(255, 99, 132)',
            pointBackgroundColor: 'rgb(255, 99, 132)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(255, 99, 132)'
        }]
    },
    options: {
        scales: {
            r:{
            suggestedMin: 0,
            suggestedMax: 2
            }
        },
        elements: {
            line: {
                borderWidth: 3
            }
        }
    }
});
}



function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data);
    });
    chart.update();
}

function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
        dataset.data.pop();
    });
    chart.update();
}




</script>

</html>
