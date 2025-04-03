<?php
require_once ("./database.php");
try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Activer l'affichage des erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la colonne stock existe
    $stmt = $pdo->query("DESCRIBE produits");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('stock', $columns)) {
        // Ajouter la colonne stock si elle n'existe pas
        $pdo->exec("ALTER TABLE produits ADD COLUMN stock INT DEFAULT 100");
    }
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des produits</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap@5.3.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h2>Nos Produits</h2>
    </header>

    <main>
        <?php if (isset($_GET['message'])): ?>
            <div class="message <?php echo $_GET['message'] === 'added' ? 'success' : 'error'; ?>" id="message">
                <?php 
                    if ($_GET['message'] === 'added') {
                        echo "Le produit a été ajouté au panier avec succès !";
                    } elseif ($_GET['message'] === 'error') {
                        if (isset($_GET['error']) && $_GET['error'] === 'stock') {
                            echo "Désolé, le stock disponible est insuffisant pour cette quantité.";
                        } else {
                            echo "Une erreur s'est produite lors de l'ajout au panier : " . 
                                 (isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "erreur inconnue");
                        }
                    }
                ?>
            </div>
        <?php endif; ?>

        <div class="products-container">
        <?php
        try {
            // Sélection des produits
            $stmt = $pdo->query("SELECT * FROM produits ORDER BY categorie, nom");
            $products = $stmt->fetchAll();

            if (empty($products)) {
                echo "<p class='no-products'>Aucun produit disponible pour le moment.</p>";
            } else {
                foreach ($products as $product) {
                    $prixFinal = $product['prix'];
                    if (isset($product['remise']) && $product['remise'] > 0) {
                        $prixFinal = $product['prix'] * (1 - $product['remise']/100);
                    }
                    
                    // Utiliser une valeur par défaut de 100 si le stock n'est pas défini
                    $stock = isset($product['stock']) ? $product['stock'] : 100;
                    ?>
                    <div class='product' data-product-id='<?php echo htmlspecialchars($product['id']); ?>'>
                        <h3><?php echo htmlspecialchars($product['nom']); ?></h3>
                        <p class="product-details"><?php echo htmlspecialchars($product['detail']); ?></p>
                        <p class="product-category">Catégorie: <?php echo htmlspecialchars($product['categorie']); ?></p>
                        <?php if (!empty($product['image'])): ?>
                            <div class="product-image-container">
                                <img src='images/<?php echo htmlspecialchars($product['image']); ?>' 
                                     alt='<?php echo htmlspecialchars($product['nom']); ?>' 
                                     class='product-image' 
                                     loading='lazy'>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-price-info">
                            <?php if (isset($product['remise']) && $product['remise'] > 0): ?>
                                <p class="original-price">Prix initial: <?php echo number_format($product['prix'], 2); ?> €</p>
                                <p class="discount">Remise: <?php echo $product['remise']; ?>%</p>
                                <p class="final-price">Prix final: <?php echo number_format($prixFinal, 2); ?> €</p>
                            <?php else: ?>
                                <p class="price">Prix: <?php echo number_format($product['prix'], 2); ?> €</p>
                            <?php endif; ?>
                        </div>

                        <div class="stock-info">
                            <p>Stock disponible: <?php echo $stock; ?></p>
                        </div>
                        
                        <div class='product-buttons'>
                            <form action='ajouter_panier.php' method='POST' class='add-to-cart-form'>
                                <input type='hidden' name='product_id' value='<?php echo $product['id']; ?>'>
                                <input type='hidden' name='product_name' value='<?php echo htmlspecialchars($product['nom']); ?>'>
                                <input type='hidden' name='product_price' value='<?php echo $prixFinal; ?>'>
                                <div class="quantity-container">
                                    <label for="quantity-<?php echo $product['id']; ?>">Quantité:</label>
                                    <input type='number' 
                                           name='quantity' 
                                           id="quantity-<?php echo $product['id']; ?>"
                                           value='1' 
                                           min='1' 
                                           max='<?php echo $stock; ?>' 
                                           class='quantity-input'>
                                </div>
                                <button type='submit' class='btn btn-primary add-to-cart'>Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
        } catch (Exception $e) {
            echo "<p class='error'>Une erreur s'est produite lors du chargement des produits: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
        </div>
    </main>
</body>
</html>
