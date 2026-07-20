<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Ajouter une configuration<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="nav-links">
    <a class="nav-link" href="/liste_configuration">&larr; Liste des configurations</a>
</div>

<div class="card">
    <h1>Ajouter une configuration</h1>

    <form action="/ajouter_configuration" method="post">
        <div class="form-group">
            <label for="typeOperation_id">Type d'opération</label>
            <select name="typeOperation_id" id="typeOperation_id" required>
                <option value="">Sélectionner un type d'operation</option>
                <?php foreach ($typeOperations as $typeOperation) : ?>
                    <option value="<?= $typeOperation['id'] ?>"><?= $typeOperation['libelle'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="min">Min</label>
            <input type="number" name="min" id="min" placeholder="Min" required>
        </div>

        <div class="form-group">
            <label for="max">Max</label>
            <input type="number" name="max" id="max" placeholder="Max" required>
        </div>

        <div class="form-group">
            <label for="montant">Montant</label>
            <input type="number" name="montant" id="montant" placeholder="Montant" required>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>

<?= $this->endSection() ?>
