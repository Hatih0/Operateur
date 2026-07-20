<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/liste_configuration"> Liste des configurations </a>
    <?php if (session()->get('success')) { ?>
        <div class="alert alert-success">
            <?= session()->get('success') ?>
        </div>
    <?php } elseif (session()->get('error')){ ?>
        <div class="alert alert-danger">
            <?= session()->get('error') ?>
        </div>
    <?php } ?>

    <h1>Modifier la configuration</h1>

    <form action="/modifier_configuration/<?= $configuration['id'] ?>" method="post">
        <div>
            <label for="typeOperation_id">Type d'opération:</label>
            <select name="typeOperation_id" id="typeOperation_id">
                <?php foreach ($typeOperations as $typeOperation) : ?>
                    <option value="<?= $typeOperation['id'] ?>" <?= ($typeOperation['id'] == $configuration['id_type_operation']) ? 'selected' : '' ?>>
                        <?= $typeOperation['libelle'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="min">Minimum:</label>
            <input type="number" name="min" id="min" value="<?= $configuration['min'] ?>" required>
        </div>
        <div>
            <label for="max">Maximum:</label>
            <input type="number" name="max" id="max" value="<?= $configuration['max'] ?>" required>
        </div>
        <div>
            <label for="montant">Montant:</label>
            <input type="number" name="montant" id="montant" value="<?= $configuration['montant'] ?>" required>
        </div>
        <button type="submit">Mettre a jour</button>
    </form>

</body>
</html>