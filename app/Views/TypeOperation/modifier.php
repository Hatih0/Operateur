<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Modifier un type d'opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a class="back-link" href="/liste_type_operation">&larr; Retour à la liste des types d'opération</a>

<div class="card">
    <h1>Modifier le type d'opération</h1>

    <form action="/modifier_type_operation/<?= $typeOperation['id'] ?>" method="post">
        <div class="form-group">
            <label for="libelle">Libelle</label>
            <input type="text" name="libelle" id="libelle" value="<?= $typeOperation['libelle'] ?>" required>
        </div>

        <button type="submit">Modifier</button>
    </form>
</div>

<?= $this->endSection() ?>
