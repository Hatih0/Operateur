<h3>Total gain : <?= $totalGain ?></h3>

<h3>Nombre total opérations : <?= $totalOperation ?></h3>


<?php foreach($situation as $type => $val): ?>

    <h4><?= $type ?></h4>

    Nombre : <?= $val['nombre'] ?><br>

    Gain : <?= $val['gain'] ?><br>

<?php endforeach ?>