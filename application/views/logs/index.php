<div class="container-fluid">
<h1>Pfeilgasse</h1>
  <div class="row">
    <div class="col-xs-12">Letzte Messzeit: <?php echo $logs[0]['datetime'] ?></div>
  </div>
<h2>Aktuelle Werte</h2>
<?php foreach ($logs as $logs_item): ?>
  <div class="row">
    <div class="col-xs-8"><?php echo $logs_item['description'] ?></div>
    <div class="col-xs-4"><?php echo format_as_celsius($logs_item['value'] / 1000) ?></div>
  </div>
<?php endforeach ?>
<h2>Durchschnitt</h2>
<?php foreach ($averages as $average): ?>
  <div class="row">
    <div class="col-xs-8"><?php echo $average['description'] ?></div>
    <div class="col-xs-4"><?php echo format_as_celsius($average['average'] / 1000) ?></div>
  </div>
<?php endforeach ?>
</div>
