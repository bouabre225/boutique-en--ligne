<?php
session_start();
require_once("./database.php");

try {
    // Initialiser la connexion à la base de données
    $database = new Database();
    $pdo = $database->getConnection();

    // Traitement de la recherche
    $searchResults = [];
    $message = '';
    $search_term = '';

    if (isset($_GET['search'])) {
        $search_term = trim($_GET['search']);
        
        if (!empty($search_term)) {
            $search = '%' . $search_term . '%';
            
            // Préparer et exécuter la requête de recherche
            $stmt = $pdo->prepare("SELECT * FROM produits WHERE nom LIKE :search OR categorie LIKE :search");
            $stmt->bindParam(':search', $search);
            $stmt->execute();
            $searchResults = $stmt->fetchAll();
            
            if (empty($searchResults)) {
                $message = "Aucun produit trouvé pour votre recherche.";
            }
        } else {
            $message = "Veuillez entrer un terme de recherche.";
        }
    }
} catch (PDOException $e) {
    $message = "Une erreur s'est produite lors de la recherche.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un produit</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap@5.3.2/css/bootstrap.min.css">
</head>
<body>
    <h2 class="text-center">Boutigue en ligne</h2>

    <div class="search-container">
        <form action="barRechercher.php" method="GET" class="search-form">
            <div class="search-input-group">
                <input type="text" 
                       name="search" 
                       placeholder="Rechercher un produit..." 
                       value="<?php echo htmlspecialchars($search_term); ?>"
                       class="search-input"
                       required>
                <button type="submit" class="search-button btn btn-primary">
                    Rechercher
                </button>
            </div>
        </form>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo empty($searchResults) ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($searchResults)): ?>
            <div class="search-results">
                <h3>Résultats de la recherche</h3>
                <div class="products-grid">
                    <?php foreach ($searchResults as $product): ?>
                        <div class="product-card">
                            <h4><?php echo htmlspecialchars($product['nom']); ?></h4>
                            <?php if (!empty($product['image'])): ?>
                                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['nom']); ?>"
                                     class="product-image">
                            <?php endif; ?>
                            <p class="product-details"><?php echo htmlspecialchars($product['detail']); ?></p>
                            <p class="product-category">Catégorie: <?php echo htmlspecialchars($product['categorie']); ?></p>
                            <p class="product-price">
                                Prix: <?php echo number_format($product['prix'], 2); ?> €
                                <?php if ($product['remise'] > 0): ?>
                                    <span class="discount">
                                        (<?php echo $product['remise']; ?>% de remise)
                                    </span>
                                <?php endif; ?>
                            </p>
                            <form action="ajouter_panier.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['nom']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $product['prix']; ?>">
                                <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="back-link">
        <a href="index.php?page=detail" class="btn btn-secondary">Retour aux produits</a>
    </div>
</body>
</html>