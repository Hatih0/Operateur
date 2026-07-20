<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Connexion Opérateur<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <div class="auth-icon">🛠️</div>

        <h1>Connexion Opérateur</h1>
        <p class="auth-subtitle">Accédez à votre espace de gestion</p>

        <form action="/checkOperateur" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" value="<?= isset($FirstOperateur['nom']) ? $FirstOperateur['nom'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" value="<?= isset($FirstOperateur['password']) ? $FirstOperateur['password'] : '1234' ?>" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <p class="auth-switch">
            Vous êtes client ? <a href="/login_client">Connectez-vous ici</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
