<?php
session_start();
require_once("./database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connexion à la base de données
        $database = new Database();
        $pdo = $database->getConnection();

        // Récupération des données du produit
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
        $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
        $product_price = isset($_POST['product_price']) ? floatval($_POST['product_price']) : 0;
        $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

        if (!$product_id) {
            throw new Exception("Produit invalide");
        }

        // Vérification du stock
        $stmt = $pdo->prepare("SELECT stock FROM produits WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            throw new Exception("Produit non trouvé");
        }

        // Initialisation du panier s'il n'existe pas
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        // Calculer la quantité totale (panier actuel + nouvelle quantité)
        $current_quantity = 0;
        foreach ($_SESSION['panier'] as $item) {
            if ($item['id'] === $product_id) {
                $current_quantity = $item['quantity'];
                break;
            }
        }

        $new_total_quantity = $current_quantity + $quantity;

        // Vérifier si le stock est suffisant
        if ($new_total_quantity > $product['stock']) {
            header('Location: index.php?page=detail&message=error&error=stock');
            exit;
        }

        // Vérifier si le produit existe déjà dans le panier
        $product_exists = false;
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] === $product_id) {
                $item['quantity'] += $quantity;
                $product_exists = true;
                break;
            }
        }

        // Si le produit n'existe pas, l'ajouter au panier
        if (!$product_exists) {
            $_SESSION['panier'][] = [
                'id' => $product_id,
                'nom' => $product_name,
                'prix' => $product_price,
                'quantity' => $quantity
            ];
        }

        // Mettre à jour le stock dans la base de données
        $stmt = $pdo->prepare("UPDATE produits SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);

        // Redirection vers la page des détails avec un message de succès
        header('Location: index.php?page=detail&message=added');
        exit;

    } catch (Exception $e) {
        header('Location: index.php?page=detail&message=error&error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Redirection en cas d'accès direct à la page
    header('Location: index.php?page=detail');
    exit;
}
