<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Liste des types d'opération<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="nav-links">
    <a class="nav-link" href="/ajouter_type_operation">Ajouter un type d'opération</a>
</div>

<div class="card">
    <h1>Liste des types d'opération</h1>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Libelle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($typeOperations as $typeOperation) : ?>
                    <tr>
                        <td><?= $typeOperation['libelle'] ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-outline btn-sm" href="/modifier_type_operation/<?= $typeOperation['id'] ?>">Modifier</a>
                                <a class="btn btn-danger btn-sm" href="/supprimer_type_operation/<?= $typeOperation['id'] ?>">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
