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

    <h1>Modifier un prefixe</h1> 
    <form action="/modifier_prefixe/<?= $prefixe['id'] ?>" method="post">
        <label for="code">Code:</label>
        <input type="text" name="code" id="code" value="<?= $prefixe['code'] ?>" required>
        <br><br>
        <input type="submit" value="Modifier">


    </form>

</body>
</html>