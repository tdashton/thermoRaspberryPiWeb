<div class="container-fluid">
<h1>Pfeilgasse</h1>
<h2>Graph</h2>
  <div class="row">
    <div class="col-xs-10" id="chartContainer" class="row" style="height:300px;"></div>
  </div>
  <div class="row">
    <div class="col-xs-10" id="chartContainer" class="row">
      <input id="datetimepicker_start" type="text" >
      <input id="datetimepicker_end" type="text" >
      <button type="button" id="datechange">Daten ändern</button>
    </div>
  </div>
<script type="text/javascript">

var start = $('#datetimepicker_start').datetimepicker();
var end = $('#datetimepicker_end').datetimepicker();
$('#datechange').click(function() {
  console.log("start " + start.val());
  console.log("end " + end.val());
  var jqxhr = $.ajax( "/~tashton/thermoRaspberryPi/index.php/logs/history/json", 
    {method: "post", data: {start: start.val(), end: end.val()}})
    .done(function(retData) {
      $("#chartContainer").CanvasJSChart({ //Pass chart options
        data: retData
      });
    });
})

$(function () {

  var jqxhr = $.ajax( "/~tashton/thermoRaspberryPi/index.php/logs/history/json")
    .done(function(retData) {
      $("#chartContainer").CanvasJSChart({ //Pass chart options
        data: retData
      });
    });
  });
</script>
</div>
