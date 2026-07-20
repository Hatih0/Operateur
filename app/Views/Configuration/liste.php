<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/ajouter_prefixe"> ajouter prefixe </a>
    <a href="/ajouter_configuration"> ajouter configuration </a>
    <h1> Liste Configuration </h1>

    <table border="1">
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
                        <a href="/modifier_configuration/<?= $configuration['id'] ?>">Modifier</a>
                        <a href="/supprimer_configuration/<?= $configuration['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

</body>
</html>