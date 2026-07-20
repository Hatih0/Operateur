<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Connexion Client<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <div class="auth-icon">📱</div>

        <h1>Connexion Client</h1>
        <p class="auth-subtitle">Entrez votre numéro pour accéder à votre compte</p>

        <form action="/login" method="post">
            <div class="form-group">
                <label for="Numero">Numéro</label>
                <input type="text" name="Numero" id="Numero" placeholder="Votre numéro de téléphone" value="<?= isset($FirstClient['numero']) ? $FirstClient['numero'] : '' ?>" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <p class="auth-switch">
            Vous êtes opérateur ? <a href="/login_operateur">Connectez-vous ici</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
