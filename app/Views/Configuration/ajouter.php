<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/liste_configuration"> liste configuration </a>

    <?php if (session()->get('success')) { ?>
        <div class="alert alert-success">
            <?= session()->get('success') ?>
        </div>
    <?php } elseif (session()->get('error')){ ?>
        <div class="alert alert-danger">
            <?= session()->get('error') ?>
        </div>
    <?php } ?>

    <h1> Ajouter Configuration </h1>

    <form action="/ajouter_configuration" method="post">
        
        <select name="typeOperation_id" id="typeOperation_id" required>
            <option value="">Sélectionner un type d'operation</option>
            <?php foreach ($typeOperations as $typeOperation) : ?>
                <option value="<?= $typeOperation['id'] ?>"><?= $typeOperation['libelle'] ?></option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <input type="number" name="min" id="min" placeholder="Min" required>
        <input type="number" name="max" id="max" placeholder="Max" required>
        <input type="number" name="montant" id="montant" placeholder="Montant" required>

        <input type="submit" value="Ajouter">
    </form>

</body>
</html>