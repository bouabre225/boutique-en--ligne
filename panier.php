<?php

require_once("./database.php");

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Traitement des actions (modifier quantité ou supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $product_id = $_POST['product_id'];
        
        if ($_POST['action'] === 'update') {
            $quantity = max(1, intval($_POST['quantity'])); // Minimum 1 produit
            foreach ($_SESSION['panier'] as &$item) {
                if ($item['id'] === $product_id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            foreach ($_SESSION['panier'] as $key => $item) {
                if ($item['id'] === $product_id) {
                    unset($_SESSION['panier'][$key]);
                    break;
                }
            }
            $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer le tableau
        }
        
        // Redirection pour éviter la resoumission du formulaire
        header('Location: panier.php');
        exit;
    }
}

$total = 0;
$total_remise = 0;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap@5.3.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h2>Mon Panier</h2>
    </header>

    <main class="panier-container">
        <?php if (empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide</p>
                <a href="index.php?page=detail" class="btn">Retour aux produits</a>
            </div>
        <?php else: ?>
            <div class="panier-items">
                <?php foreach ($_SESSION['panier'] as $item): 
                    // Gérer la compatibilité avec les anciennes et nouvelles clés
                    $nom = isset($item['nom']) ? $item['nom'] : (isset($item['name']) ? $item['name'] : 'Produit inconnu');
                    $prix = isset($item['prix']) ? $item['prix'] : (isset($item['price']) ? $item['price'] : 0);
                ?>
                    <div class="panier-item" data-product-id="<?php echo $item['id']; ?>">
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($nom); ?></h3>
                            <p class="price"><?php echo number_format($prix, 2); ?> €</p>
                        </div>
                        <div class="item-actions">
                            <form action="panier.php" method="POST" class="quantity-form">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                       min="1" class="quantity-input">
                                <button type="submit" class="btn">Mettre à jour</button>
                            </form>
                            <form action="panier.php" method="POST" class="delete-form">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-delete">Supprimer</button>
                            </form>
                        </div>
                        <div class="item-total">
                            <?php
                            $sous_total = $prix * $item['quantity'];
                            $total += $sous_total;
                            echo 'Sous-total: ' . number_format($sous_total, 2) . ' €';
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="panier-summary">
                <h3>Récapitulatif</h3>
                <p class="total">Total: <span><?php echo number_format($total, 2); ?> €</span></p>
                <div class="panier-actions">
                    <a href="index.php?page=confirmation" class="btn btn-commander">Passer la commande</a>
                    <a href="index.php?page=detail" class="btn">Continuer les achats</a>
                </div>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>