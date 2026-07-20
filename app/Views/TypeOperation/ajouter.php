<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Ajouter un type d'opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a class="back-link" href="/liste_type_operation">&larr; Retour à la liste des types d'opération</a>

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
