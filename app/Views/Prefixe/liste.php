<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Liste des préfixes<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="nav-links">
    <a class="nav-link" href="/ajouter_prefixe">Ajouter un préfixe</a>
</div>

<div class="card">
    <h1>Liste des préfixes</h1>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prefixes as $prefixe) : ?>
                    <tr>
                        <td><?= $prefixe['code'] ?></td>
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
