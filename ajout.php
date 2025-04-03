<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un produit</title>
</head>
<body>
    <header>
        <h2 id="form" class="text-center">Ajouter un produit</h2>
    </header>
    <main class="container">
    <form action="./traitementAjout.php" method="post" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label for="name" class="form-label">Nom du produit</label>
            <input type="text" name="name" id="name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Détails</label>
            <textarea name="description" id="description" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="categorie" class="form-label">Categorie</label>
            <select name="categorie" id="categorie" required class="form-control">
                <option value="Fruit">Fruit</option>
                <option value="Legume">Legume</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif" required class="form-control">
        </div>

        <div class="form-group">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" name="prix" id="prix" step="0.01" required class="form-control">
        </div>

        <div class="form-group">
            <label for="remise" class="form-label">Remise</label>
            <input type="number" name="remise" id="remise" required class="form-control">
        </div>

        <button type="submit" class="btn btn-ajouter">Ajouter le produit</button>
    </form>
    </main>
</body>
</html>