<h2>Informations client</h2>

Nom : <?= $informations['nom'] ?><br>
Code : <?= $informations['code'] ?><br>
Numéro : <?= $informations['numero'] ?>


<hr>


<h2>Solde</h2>

Solde actuel :
<?= $situation['solde'] ?> Ar


<hr>


<h2>Historique des transactions</h2>

<table border="1">

<tr>
    <th>Date</th>
    <th>Type</th>
    <th>Montant</th>
    <th>Frais</th>
</tr>


<?php foreach($historique as $h): ?>

<tr>
    <td><?= $h['date'] ?></td>
    <td><?= $h['type_operation'] ?></td>
    <td><?= $h['montant'] ?></td>
    <td><?= $h['frais'] ?></td>
</tr>

<?php endforeach; ?>


</table>