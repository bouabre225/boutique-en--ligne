<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un produit</title>
</head>
<body>
    <h1>Ajouter un produit</h1>
    <form action="./traitementAjout.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="name" class="form-label">Nom du produit</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div>
            <label for="description" class="form-label">Détails</label>
            <textarea name="description" id="description" required class="form-control"></textarea>
        </div>

        <div>
            <label for="categorie">Categorie</label>
            <select name="categorie" id="categorie" required>
                <option value="Fruit">Fruit</option>
                <option value="Legume">Legume</option>
            </select>
        </div>

        <div>
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif" required class="form-control">
        </div>

        <div>
            <label for="prix">Prix</label>
            <input type="number" name="prix" id="prix" step="0.01" required class="form-control">
        </div>

        <div>
            <label for="remise">Remise</label>
            <input type="number" name="remise" id="remise" required>
        </div>

        <button type="submit" class="btn btn-ajouter">Ajouter le produit</button>
    </form>
</body>
</html>