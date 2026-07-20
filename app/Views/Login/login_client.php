<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php if (session()->get('success')) { ?>
        <div class="alert alert-success">
            <?= session()->get('success') ?>
        </div>
    <?php } elseif (session()->get('error')){ ?>
        <div class="alert alert-danger">
            <?= session()->get('error') ?>
        </div>
    <?php } ?>

    <h1> Login Client </h1>

    <form action="/login" method="post">
        <label for="Numero">Numero:</label>
        <input type="text" name="Numero" id="Numero" required>

        <input type="submit" value="Se Connecter">
    </form>

    <a href="/login_operateur"> Se connecter en tant qu'opérateur </a>

</body>
</html>