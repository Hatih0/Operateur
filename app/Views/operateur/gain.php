<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Situation des gains<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h1>Situation des gains</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total gain</div>
            <div class="stat-value"><?= $totalGain ?> Ar</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Nombre total d'opérations</div>
            <div class="stat-value"><?= $totalOperation ?></div>
        </div>
    </div>

    <?php foreach($situation as $type => $val): ?>

        <h4><span class="badge"><?= $type ?></span></h4>

        <div class="info-list">
            <p>Nombre : <strong><?= $val['nombre'] ?></strong></p>
            <p>Gain : <strong><?= $val['gain'] ?> Ar</strong></p>
        </div>

    <?php endforeach ?>
</div>

<?= $this->endSection() ?>
