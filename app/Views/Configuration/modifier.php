<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Modifier une configuration<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a class="back-link" href="/liste_configuration">&larr; Retour à la liste des configurations</a>

<div class="card">
    <h1>Modifier la configuration</h1>

    <form action="/modifier_configuration/<?= $configuration['id'] ?>" method="post">
        <div class="form-group">
            <label for="typeOperation_id">Type d'opération</label>
            <select name="typeOperation_id" id="typeOperation_id">
                <?php foreach ($typeOperations as $typeOperation) : ?>
                    <option value="<?= $typeOperation['id'] ?>" <?= ($typeOperation['id'] == $configuration['id_type_operation']) ? 'selected' : '' ?>>
                        <?= $typeOperation['libelle'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="min">Minimum</label>
            <input type="number" name="min" id="min" value="<?= $configuration['min'] ?>" required>
        </div>

        <div class="form-group">
            <label for="max">Maximum</label>
            <input type="number" name="max" id="max" value="<?= $configuration['max'] ?>" required>
        </div>

        <div class="form-group">
            <label for="montant">Montant</label>
            <input type="number" name="montant" id="montant" value="<?= $configuration['montant'] ?>" required>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>

<?= $this->endSection() ?>
