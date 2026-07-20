<?= $this->extend('templates/layout') ?>

<?= $this->section('title') ?>Situation du compte client<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Informations client</h2>

    <div class="info-list">
        <p><strong>Nom :</strong> <?= $client['nom'] ?></p>
        <p><strong>Code :</strong> <?= $client['code'] ?></p>
        <p><strong>Numéro :</strong> <?= $client['numero'] ?></p>
    </div>
</div>

<div class="card">
    <h3>Solde du compte</h3>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Solde actuel</div>
            <div class="stat-value"><?= $situation['solde'] ?? 0 ?> Ar</div>
        </div>
    </div>
</div>

<div class="card">
    <h3>Historique des transactions</h3>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type opération</th>
                    <th>Montant</th>
                    <th>Frais</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historique)): ?>
                    <?php foreach ($historique as $transaction): ?>
                        <tr>
                            <td><?= $transaction['date'] ?></td>
                            <td><?= $transaction['type_operation'] ?></td>
                            <td><?= $transaction['montant'] ?> Ar</td>
                            <td><?= $transaction['frais'] ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucun historique disponible</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
