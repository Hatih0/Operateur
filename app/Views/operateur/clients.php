<h2>Situation des comptes clients</h2>

<table border="1">
    <tr>
        <th>Nom</th>
        <th>Code</th>
        <th>Numéro</th>
        <th>Action</th>
    </tr>

    <?php foreach ($clients as $client): ?>

        <tr>
            <td><?= $client['nom'] ?></td>
            <td><?= $client['code'] ?></td>
            <td><?= $client['numero'] ?></td>

            <td>
                <a href="<?= base_url('operateur/situationClient/'.$client['id']) ?>">
                    Voir situation
                </a>
            </td>
        </tr>

    <?php endforeach; ?>

</table>