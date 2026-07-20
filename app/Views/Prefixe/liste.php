<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Liste des préfixes<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h1 style="margin-bottom:0;">Liste des préfixes</h1>

        <div class="nav-links">
            <a class="btn btn-sm" href="/ajouter_prefixe">+ Ajouter un préfixe</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Opérateur</th>
                    <th>Connexion client</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prefixes as $prefixe) : ?>
                    <tr>
                        <td><?= $prefixe['code'] ?></td>
                        <td><?= $prefixe['operateur_nom'] ?></td>
                        <td>
                            <?php if ((int) $prefixe['operateur_id'] === 1) : ?>
                                <span class="badge">Autorisée</span>
                            <?php else : ?>
                                <span class="badge badge-muted">Bloquée</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-outline btn-sm" href="/modifier_prefixe/<?= $prefixe['id'] ?>">Modifier</a>
                                <a class="btn btn-danger btn-sm" href="/supprimer_prefixe/<?= $prefixe['id'] ?>">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
