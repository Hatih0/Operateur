<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Liste des opérateurs<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h1>Liste de tous les opérateurs</h1>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Opérateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operateurs as $operateur): ?>
                    <tr>
                        <td>
                            <?= $operateur['nom'] ?>
                            <?php if ((int) $operateur['id'] === 1): ?>
                                <span class="badge">Principal</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-outline btn-sm" href="<?= base_url('operateur/situationAutreOperateur/' . $operateur['id']) ?>">
                                Voir la situation
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
