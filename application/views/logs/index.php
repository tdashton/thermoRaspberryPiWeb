<div class="container-fluid">
<h1>Pfeilgasse</h1>
  <div class="row">
    <div class="col-xs-12">Letzte Messzeit: <?php echo $logs[0]['datetime'] ?></div>
  </div>
<h2>Aktuelle Werte</h2>
<?php foreach ($logs as $logs_item): ?>
  <div class="row">
    <div class="col-xs-8"><?php echo $logs_item['description'] ?></div>
    <div class="col-xs-4"><?php echo format_as_celsius($logs_item['value']) ?></div>
  </div>
<?php endforeach ?>
<h2>Durchschnitt</h2>
<?php foreach ($averages as $average): ?>
  <div class="row">
    <div class="col-xs-8"><?php echo $average['description'] ?></div>
    <div class="col-xs-4"><?php echo format_as_celsius($average['average']) ?></div>
  </div>
<?php endforeach ?>
<h2>Graph</h2>
  <div class="row">
    <div class="col-xs-10" id="chartContainer" class="row" style="height:300px;"></div>
  </div>
<script type="text/javascript">

$(function () {

  var jqxhr = $.ajax( "/~tashton/thermoRaspberryPi/index.php/logs/history/json" )
    .done(function(retData) {
      $(retData).each(function(index, value) {
        $(value.dataPoints).each(
          function(index, value) {
            value.x = new Date(value.x);
            console.log(value.x);
          });
      });
      $("#chartContainer").CanvasJSChart({ //Pass chart options
        data: retData
      });
    });
  });
</script>
</div>
