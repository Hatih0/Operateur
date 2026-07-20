<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Situation de l'opérateur<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a class="back-link" href="<?= base_url('liste_operateur') ?>">&larr; Retour à la liste des opérateurs</a>

<div class="card">
    <h1>Situation des gains — <?= $operateur ?></h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total gain</div>
            <div class="stat-value"><?= $totalGains ?> Ar</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Nombre total d'opérations</div>
            <div class="stat-value"><?= $totalOperation ?></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
