<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Modifier un préfixe<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a class="back-link" href="/liste_prefixe">&larr; Retour à la liste des préfixes</a>

<div class="card">
    <h1>Modifier un préfixe</h1>

    <form action="/modifier_prefixe/<?= $prefixe['id'] ?>" method="post">
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" value="<?= $prefixe['code'] ?>" required>
        </div>

        <div class="form-group">
            <label for="operateur_id">Opérateur</label>
            <select name="operateur_id" id="operateur_id" required>
                <?php foreach ($operateurs as $operateur) : ?>
                    <option value="<?= $operateur['id'] ?>" <?= ($operateur['id'] == $prefixe['operateur_id']) ? 'selected' : '' ?>>
                        <?= $operateur['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Modifier</button>
    </form>
</div>

<?= $this->endSection() ?>
