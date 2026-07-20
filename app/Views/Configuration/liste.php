<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Liste des configurations<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h1 style="margin-bottom:0;">Liste des configurations</h1>

        <div class="nav-links">
            <a class="btn btn-outline btn-sm" href="/ajouter_prefixe">+ Ajouter un préfixe</a>
            <a class="btn btn-sm" href="/ajouter_configuration">+ Ajouter une configuration</a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Type Operation</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($configurations as $configuration) : ?>
                    <tr>
                        <td><?= $configuration['id_type_operation'] ?></td>
                        <td><?= $configuration['min'] ?></td>
                        <td><?= $configuration['max'] ?></td>
                        <td><?= $configuration['montant'] ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-outline btn-sm" href="/modifier_configuration/<?= $configuration['id'] ?>">Modifier</a>
                                <a class="btn btn-danger btn-sm" href="/supprimer_configuration/<?= $configuration['id'] ?>">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
