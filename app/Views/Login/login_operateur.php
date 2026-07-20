<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Connexion Opérateur<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h1>Connexion Opérateur</h1>

    <form action="/checkOperateur" method="post">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" value="<?= isset($FirstOperateur['nom']) ? $FirstOperateur['nom'] : '' ?>" placeholder="Nom d'utilisateur" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" value="<?= isset($FirstOperateur['password']) ? $FirstOperateur['password'] : '' ?>" placeholder="Mot de passe" required>
        </div>

        <button type="submit">Se connecter</button>
    </form>
</div>

<?= $this->endSection() ?>
