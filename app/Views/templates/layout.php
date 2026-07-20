<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?: 'Opérateur' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

    <?php $currentPath = trim(uri_string(), '/'); ?>

    <header class="navbar">
        <div class="navbar-inner">
            <a href="<?= base_url('/') ?>" class="brand">Opérateur</a>

            <?php if (session()->get('OperateurLoggedIn')) : ?>

                <nav class="navbar-links">
                    <a href="<?= base_url('liste_configuration') ?>" class="<?= str_starts_with($currentPath, 'liste_configuration') || str_starts_with($currentPath, 'ajouter_configuration') || str_starts_with($currentPath, 'modifier_configuration') ? 'active' : '' ?>">Configurations</a>
                    <a href="<?= base_url('liste_prefixe') ?>" class="<?= str_starts_with($currentPath, 'liste_prefixe') || str_starts_with($currentPath, 'ajouter_prefixe') || str_starts_with($currentPath, 'modifier_prefixe') ? 'active' : '' ?>">Préfixes</a>
                    <a href="<?= base_url('liste_type_operation') ?>" class="<?= str_starts_with($currentPath, 'liste_type_operation') || str_starts_with($currentPath, 'ajouter_type_operation') || str_starts_with($currentPath, 'modifier_type_operation') ? 'active' : '' ?>">Types d'opération</a>
                    <a href="<?= base_url('operateur/clients') ?>" class="<?= str_starts_with($currentPath, 'operateur/clients') || str_starts_with($currentPath, 'operateur/situationClient') ? 'active' : '' ?>">Clients</a>
                    <a href="<?= base_url('operateur/gain') ?>" class="<?= str_starts_with($currentPath, 'operateur/gain') ? 'active' : '' ?>">Gains</a>
                    <a href="<?= base_url('liste_operateur') ?>" class="<?= str_starts_with($currentPath, 'liste_operateur') || str_starts_with($currentPath, 'operateur/situationAutreOperateur') ? 'active' : '' ?>">Opérateurs</a>
                </nav>

                <a href="<?= base_url('logout') ?>" class="navbar-logout">Déconnexion</a>

            <?php elseif (session()->get('ClientLoggedIn')) : ?>

                <nav class="navbar-links">
                    <a href="<?= base_url('client/situation') ?>" class="<?= str_starts_with($currentPath, 'client/situation') || str_starts_with($currentPath, 'client/formulaire') ? 'active' : '' ?>">Ma situation</a>
                </nav>

                <a href="<?= base_url('logout') ?>" class="navbar-logout">Déconnexion</a>

            <?php endif; ?>
        </div>
    </header>

    <main class="container">

        <?php if (session()->get('success')) { ?>
            <div class="alert alert-success">
                <?= session()->get('success') ?>
            </div>
        <?php } elseif (session()->get('error')) { ?>
            <div class="alert alert-danger">
                <?= session()->get('error') ?>
            </div>
        <?php } ?>

        <?= $this->renderSection('content') ?>

    </main>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> Opérateur</p>
    </footer>

</body>
</html>
