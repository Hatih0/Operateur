<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/ajouter_prefixe"> ajouter prefixe </a>

    <h1> Liste Prefixe </h1>

    <table border="1">
        <thead>
            <tr>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prefixes as $prefixe) : ?>
                <tr>
                    <td><?= $prefixe['code'] ?></td>
                    <td>
                        <a href="/modifier_prefixe/<?= $prefixe['id'] ?>">Modifier</a>
                        <a href="/supprimer_prefixe/<?= $prefixe['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

</body>
</html>