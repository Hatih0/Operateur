<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/ajouter_type_operation"> ajouter type operation </a>

    <h1> Liste Type operation </h1>

    <table border="1">
        <thead>
            <tr>
                <th>Libelle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($typeOperations as $typeOperation) : ?>
                <tr>
                    <td><?= $typeOperation['libelle'] ?></td>
                    <td>
                        <a href="/modifier_type_operation/<?= $typeOperation['id'] ?>">Modifier</a>
                        <a href="/supprimer_type_operation/<?= $typeOperation['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</body>
</html>