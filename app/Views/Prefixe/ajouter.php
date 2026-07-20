<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Ajouter un préfixe<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h1>Ajouter un préfixe</h1>

    <form action="/ajouter_prefixe" method="post">
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" required>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>

<?= $this->endSection() ?>
