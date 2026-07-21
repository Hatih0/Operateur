<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Ma situation<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <a href="/insert-epargne"> Inserer Epargne </a>

    <h2>Informations client</h2>

    <div class="info-list">
        <p><strong>Nom :</strong> <?= $informations['nom'] ?></p>
        <p><strong>Code :</strong> <?= $informations['code'] ?></p>
        <p><strong>Numéro :</strong> <?= $informations['numero'] ?></p>
    </div>
</div>

<div class="card">
    <h2>Solde</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Solde actuel</div>
            <div class="stat-value"><?= $situation['solde'] ?> Ar</div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Actions</h2>

    <div class="actions">
        <a class="btn" href="<?= base_url('client/formulaire/depot') ?>">
            Faire un dépôt
        </a>

        <a class="btn" href="<?= base_url('client/formulaire/retrait') ?>">
            Faire un retrait
        </a>

        <a class="btn" href="<?= base_url('client/formulaire/transfert') ?>">
            Transférer argent
        </a>

        <a class="btn" href="<?= base_url('client/insertionmultiple') ?>">
            Insertion multiple (même opérateur)
        </a>
    </div>
</div>

<div class="card">
    <h2>Historique des transactions</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($historique as $h): ?>
                    <tr>
                        <td><?= $h['date'] ?></td>
                        <td><?= $h['type_operation'] ?></td>
                        <td><?= $h['montant'] ?> Ar</td>
                        <td><?= $h['frais'] ?> Ar</td>
                        <td><?= $h['commission'] ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
