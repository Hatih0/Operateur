<h2>Informations client</h2>

Nom : <?= $informations['nom'] ?><br>
Code : <?= $informations['code'] ?><br>
Numéro : <?= $informations['numero'] ?>


<hr>


<h2>Solde</h2>

Solde actuel :
<?= $situation['solde'] ?> Ar


<hr>


<h2>Actions</h2>

<a href="<?= base_url('client/formulaire/'.$informations['id'].'/depot') ?>">
    <button>
        Faire un dépôt
    </button>
</a>


<a href="<?= base_url('client/formulaire/'.$informations['id'].'/retrait') ?>">
    <button>
        Faire un retrait
    </button>
</a>


<a href="<?= base_url('client/formulaire/'.$informations['id'].'/transfert') ?>">
    <button>
        Transférer argent
    </button>
</a>


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

    <td><?= $h['montant'] ?> Ar</td>

    <td><?= $h['frais'] ?> Ar</td>
</tr>

<?php endforeach; ?>


</table>