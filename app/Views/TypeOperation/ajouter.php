<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Ajouter un type d'opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="nav-links">
    <a class="nav-link" href="/liste_type_operation">&larr; Liste des types d'opération</a>
</div>

<div class="card">
    <h1>Ajouter un type d'opération</h1>

    <form action="/ajouter_type_operation" method="post">
        <div class="form-group">
            <label for="libelle">Libellé</label>
            <input type="text" name="libelle" id="libelle" required>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>

<?= $this->endSection() ?>
