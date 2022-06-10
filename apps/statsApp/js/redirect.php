<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>График неисправностей</title>
</head>
<body>

<div style="position: relative;
  left: -86px;" class="faults-graph-wrap">
    <div id="g-chart"></div>
    <!-- <canvas id="bar-chart"></canvas> -->
  </div>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
<!-- <script type="text/javascript" src="js/application.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
  
<script>
  // alert(localStorage.getItem('faultGraphStats'));
  // console.log(JSON.parse(localStorage.getItem('faultGraphStats')));

function drawChart (faultLabels, faultCount, stats) { //Отрисовка графика

function getRandomColor() {
  var letters = '0123456789ABCDEF'.split('');
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

let colorScheme = [];

for (let i = 0; i < stats.arr.length; i++) {
  stats.arr[i].push(getRandomColor());
  colorScheme.push(getRandomColor());
}

stats.faultLabels.unshift('');
stats.faultCount.unshift('');
// stats.arr.unshift(["Element", "Неисправностей", { role: "style" }]);

// app.clearChart();

google.charts.load("current", { packages: ['corechart'] });
google.charts.setOnLoadCallback(drawGoogleChart);

let test = stats.arr.join(',');
console.log(test);

function drawGoogleChart() {
  var data = google.visualization.arrayToDataTable(
    stats.arr
  );

  var view = new google.visualization.DataView(data);
  view.setColumns([0, 1,
    {
      calc: "stringify",
      sourceColumn: 1,
      type: "string",
      role: "annotation"
    },
    2]);

  let barWidth = stats.arr.length == 2 ? { groupWidth: "25%" } : { groupWidth: "75%" };

  let chartWidth = document.documentElement.clientWidth*1.15;
  let chartHeight = document.documentElement.clientWidth*0.4;

  var options = {
    title: "Количество неисправностей",
    width: chartWidth,
    height: chartHeight,
    bar: barWidth,
    legend: { position: "none" },
    vAxis: { format: '0' }
  };
  var chart = new google.visualization.ColumnChart(document.getElementById("g-chart"));
  chart.draw(view, options);
}




}


const raw = localStorage.getItem('faultGraphStats');
let stats = JSON.parse(raw);


console.log(stats);
console.log(localStorage.getItem('faultGraphStats'));

drawChart(null,null,stats);
</script>
</body>
</html>