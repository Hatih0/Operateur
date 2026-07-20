<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Connexion Client<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h1>Connexion Client</h1>

    <form action="/login" method="post">
        <div class="form-group">
            <label for="Numero">Numéro :</label>
            <input type="text" name="Numero" id="Numero" value="<?= isset($FirstClient['numero']) ? $FirstClient['numero'] : '' ?>" required>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <p style="margin-top: 20px;">
        <a href="/login_operateur">Se connecter en tant qu'opérateur</a>
    </p>
</div>

<?= $this->endSection() ?>
