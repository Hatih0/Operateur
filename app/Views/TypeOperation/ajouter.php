<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <a href="/liste_type_operation"> liste type operation </a>
    <h1> ajouter Type operation </h1>

    <form action="/ajouter_type_operation" method="post">
        <label for="libelle">Libellé:</label>
        <input type="text" name="libelle" id="libelle" required>
        <br><br>
        <input type="submit" value="Ajouter">
    </form>

</body>
</html>