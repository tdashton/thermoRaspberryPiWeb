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
      <a id='today' href='#'>Heute ...</a>
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
    timezoneOffset: <?php echo -$timezoneOffset ?>
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
  // series: seriesData
};

$.getJSON('<?php echo base_url('index.php/logs/history/json') ?>', function (seriesData) {
  Highcharts.chart('container', $.extend(containerConfig, {series: seriesData}));
});

var startInput = $('#datetimepicker_start').datetimepicker();
var endInput = $('#datetimepicker_end').datetimepicker();

$('#today').click(function() {
  $.getJSON('<?php echo base_url('index.php/logs/history/json') ?>',
    {start: new Date(Date.now() - (1000 * 86400 * 1)).getTime(), end: new Date(Date.now()).getTime()},
    function (seriesData) {
      Highcharts.chart('container', $.extend(containerConfig, {series: seriesData}));
    }
  );
});

$('#yesterday').click(function() {
  $.getJSON('<?php echo base_url('index.php/logs/history/json') ?>',
    {start: new Date(Date.now() - (1000 * 86400 * 2)).getTime(), end: new Date(Date.now() - (1000 * 86400 * 1)).getTime()},
    function (seriesData) {
      Highcharts.chart('container', $.extend(containerConfig, {series: seriesData}));
    }
  );
});

$('#this_day_last_week').click(function() {
  $.getJSON('<?php echo base_url('index.php/logs/history/json') ?>',
    {start: new Date(Date.now() - (1000 * 86400 * 7)).getTime(), end: new Date(Date.now() - (1000 * 86400 * 6)).getTime()},
    function (seriesData) {
      Highcharts.chart('container', $.extend(containerConfig, {series: seriesData}));
    }
  );
});

$('#last_seven_days').click(function() {
  $.getJSON('<?php echo base_url('index.php/logs/history/json') ?>',
    {start: new Date(Date.now() - (1000 * 86400 * 5)).getTime(), end: new Date(Date.now()).getTime()},
    function (seriesData) {
      Highcharts.chart('container', $.extend(containerConfig, {series: seriesData}));
    }
  );
});

$('#datechange').click(function() {
  console.log("start " + startInput.val());
  console.log("end " + endInput.val());
  $.getJSON('<?php echo base_url('index.php/logs/history/json') ?>',
    {start: new Date(startInput.val()).getTime(), end: new Date(endInput.val()).getTime()},
    function (seriesData) {
      Highcharts.chart('container', $.extend(containerConfig, {series: seriesData}));
    }
  );
   // var jqxhr = $.ajax( "<?php echo base_url('index.php/logs/history/json') ?>", 
   //   {method: "get", data: {start: start.val(), end: end.val()}})
   //   .done(function(retData) {
   //     $("#chartContainer").CanvasJSChart({ //Pass chart options
   //       data: retData.data
   //     });
   //   });
 })
</script>
</div>
