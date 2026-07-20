<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/liste_type_operation"> liste type operation </a>
    <h1> Modifier Type Operation </h1>

    <form action="/modifier_type_operation/<?= $typeOperation['id'] ?>" method="post">
        <label for="libelle">Libelle:</label>
        <input type="text" name="libelle" id="libelle" value="<?= $typeOperation['libelle'] ?>" required>
        <br><br>
        <input type="submit" value="Modifier">
    </form>

</body>
</html>