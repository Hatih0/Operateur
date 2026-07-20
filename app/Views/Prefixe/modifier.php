<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Modifier un préfixe<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="nav-links">
    <a class="nav-link" href="/liste_prefixe">&larr; Liste des préfixes</a>
</div>

<div class="card">
    <h1>Modifier un préfixe</h1>

    <form action="/modifier_prefixe/<?= $prefixe['id'] ?>" method="post">
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" value="<?= $prefixe['code'] ?>" required>
        </div>

        <button type="submit">Modifier</button>
    </form>
</div>

<?= $this->endSection() ?>
