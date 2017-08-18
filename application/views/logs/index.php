<div class="container-fluid">
<h1><?php echo $locationName ?></h1>
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
<a href="<?php echo base_url('index.php/logs/graph') ?>">hier</a>
</div>
