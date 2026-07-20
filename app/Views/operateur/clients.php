<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Situation des comptes clients<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Situation des comptes clients</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Numéro</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['nom'] ?></td>
                        <td><?= $client['code'] ?></td>
                        <td><?= $client['numero'] ?></td>
                        <td>
                            <a class="btn btn-outline btn-sm" href="<?= base_url('operateur/situationClient/'.$client['id']) ?>">
                                Voir situation
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
