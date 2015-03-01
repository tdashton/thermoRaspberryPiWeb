<div class="container-fluid">
<h1>Pfeilgasse</h1>
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
