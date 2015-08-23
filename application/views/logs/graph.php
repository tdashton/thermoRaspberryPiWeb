<div class="container-fluid">
  <div class="row">
    <div class="col-xs-10" class="row" >
      <h1>Pfeilgasse</h1>
      <h2>Graph</h2>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-10" id="chartContainer" class="row" style="height:300px;"></div>
  </div>
  <div class="row">
    <div class="col-xs-8">
      <h3>Daten &auml;ndern</h3>
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

var start = $('#datetimepicker_start').datetimepicker();
var end = $('#datetimepicker_end').datetimepicker();
$('#datechange').click(function() {
  console.log("start " + start.val());
  console.log("end " + end.val());
  var jqxhr = $.ajax( "/~tashton/thermoRaspberryPi/index.php/logs/history/json", 
    {method: "post", data: {start: start.val(), end: end.val()}})
    .done(function(retData) {
      $("#chartContainer").CanvasJSChart({ //Pass chart options
        data: retData.data
      });
    });
})

$(function () {

  var jqxhr = $.ajax( "/~tashton/thermoRaspberryPi/index.php/logs/history/json")
    .done(function(retData) {
      $("#chartContainer").CanvasJSChart({ //Pass chart options
        data: retData.data
      });
    });
  });
</script>
</div>
