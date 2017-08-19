<div class="container-fluid">
  <div class="row">
    <div class="col-xs-10" class="row" >
      <h1><?php echo $locationName ?></h1>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12" id="container" class="row" style="height:300px;"></div>
  </div>
  <div class="row">
    <div class="col-xs-8">
      <h3>Daten &auml;ndern</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10">
      <a id='last_twelve_hours' href='#'>letzte zwölf Stunden ...</a>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10">
      <a id='today' href='#'>letzte 24 Stunden ...</a>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10">
      <a id='yesterday' href='#'>Gestern ...</a>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10">
      <a id='this_day_last_week' href='#'>Heute vor einer Woche ...</a>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10">
      <a id='last_seven_days' href='#'>Letzte fünf Tage ...</a>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-2">
      Von:
    </div>
    <div class="col-xs-8">
       <input id="datetimepicker_start" type="text" >
    </div>
  </div>
  <div class="row">
    <div class="col-xs-2">
      bis: 
    </div>
    <div class="col-xs-8">
      <input id="datetimepicker_end" type="text" >
    </div>
  </div>
  <div class="row">
    <div class="col-xs-8 col-xs-offset-2">
      <button type="button" id="datechange">los!</button>
    </div>
  </div>
</div>
<script type="text/javascript">

Highcharts.setOptions({
  global: {
    useUTC: false
  }
});

var containerConfig = {
  chart: {zoomType: 'x'},
  title: {text: ''},
  exporting: {enabled: false},
  subtitle: {text: ''},
  xAxis: {type: 'datetime'},
  yAxis: {title: {text: ''}},
  legend: {
    layout: 'horizontal',
    align: 'center',
    verticalAlign: 'bottom'
  }
  // series: seriesData.data
};

// get default data set from the server
$.getJSON('<?php echo base_url('index.php/logs/history/json') ?>', function (seriesData) {
  Highcharts.chart('container', $.extend(containerConfig, {series: seriesData.data}));
});

function refreshChartWithData(dateStart, dateEnd) {
    $.getJSON('<?php echo base_url('index.php/logs/history/json') ?>',
    {start: dateStart.getTime(), end: dateEnd.getTime()},
    function (seriesData) {
      Highcharts.chart('container', $.extend(containerConfig, {series: seriesData.data}));
    }
  );
}

var startInput = $('#datetimepicker_start').datetimepicker();
var endInput = $('#datetimepicker_end').datetimepicker();

$('#last_twelve_hours').click(function() {
  refreshChartWithData(new Date(Date.now() - (1000 * 3600 * 12)), new Date(Date.now()));
});

$('#today').click(function() {
  refreshChartWithData(new Date(Date.now() - (1000 * 86400 * 1)), new Date(Date.now()));
});

$('#yesterday').click(function() {
  refreshChartWithData(new Date(Date.now() - (1000 * 86400 * 2)), new Date(Date.now() - (1000 * 86400 * 1)));
});

$('#this_day_last_week').click(function() {
  refreshChartWithData(new Date(Date.now() - (1000 * 86400 * 7)), new Date(Date.now() - (1000 * 86400 * 6)));
});

$('#last_seven_days').click(function() {
  refreshChartWithData(new Date(Date.now() - (1000 * 86400 * 5)), new Date(Date.now()));
});

$('#datechange').click(function() {
  console.log("start " + startInput.val());
  console.log("end " + endInput.val());
  refreshChartWithData(new Date(startInput.val()), new Date(endInput.val()));
});
</script>
</div>
