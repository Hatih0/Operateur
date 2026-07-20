<h2>Situation du compte client</h2>

<hr>

<h3>Informations client</h3>

<p>
    Nom :
    <?= $client['nom'] ?>
</p>

<p>
    Code :
    <?= $client['code'] ?>
</p>

<p>
    Numéro :
    <?= $client['numero'] ?>
</p>


<hr>


<h3>Solde du compte</h3>

<p>
    Solde actuel :
    <strong>
        <?= $situation['solde'] ?? 0 ?> Ar
    </strong>
</p>


<hr>


<h3>Historique des transactions</h3>

<table border="1" cellpadding="8" cellspacing="0">

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

                <td>
                    <?= $transaction['date'] ?>
                </td>

                <td>
                    <?= $transaction['type_operation'] ?>
                </td>

                <td>
                    <?= $transaction['montant'] ?> Ar
                </td>

                <td>
                    <?= $transaction['frais'] ?> Ar
                </td>

            </tr>

        <?php endforeach; ?>


    <?php else: ?>

        <tr>
            <td colspan="4">
                Aucun historique disponible
            </td>
        </tr>

    <?php endif; ?>

    </tbody>

</table>