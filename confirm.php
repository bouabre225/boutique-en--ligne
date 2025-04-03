<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('database.php');

// Vérifier si le panier existe et n'est pas vide
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header('Location: index.php?page=commande');
    exit;
}

// Traitement du formulaire
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des champs
    $required_fields = ['nom', 'prenom', 'adresse', 'ville', 'code_postal', 'email', 'telephone'];
    $is_valid = true;
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $is_valid = false;
            $message = 'Tous les champs sont obligatoires.';
            break;
        }
    }
    
    // Validation de l'email
    if ($is_valid && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $is_valid = false;
        $message = 'Adresse email invalide.';
    }
    
    // Validation du numéro de téléphone (10 chiffres)
    if ($is_valid && !preg_match('/^[0-9]{10}$/', $_POST['telephone'])) {
        $is_valid = false;
        $message = 'Numéro de téléphone invalide (10 chiffres requis).';
    }
    
    if ($is_valid) {
        // Sauvegarder les informations de livraison dans la session
        $_SESSION['livraison'] = [
            'nom' => htmlspecialchars($_POST['nom']),
            'prenom' => htmlspecialchars($_POST['prenom']),
            'adresse' => htmlspecialchars($_POST['adresse']),
            'ville' => htmlspecialchars($_POST['ville']),
            'code_postal' => htmlspecialchars($_POST['code_postal']),
            'email' => htmlspecialchars($_POST['email']),
            'telephone' => htmlspecialchars($_POST['telephone'])
        ];
        
        // Rediriger vers la génération de facture
        header('Location: generer_facture.php');
        exit;
    }
}

// Calcul du total
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $prix = isset($item['prix']) ? $item['prix'] : (isset($item['price']) ? $item['price'] : 0);
    $total += $prix * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap@5.3.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h2>Confirmation de commande</h2>
    </header>

    <main class="confirmation-container">
        <?php if (!empty($message)): ?>
            <div class="message error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="confirmation-grid">
            <!-- Récapitulatif de la commande -->
            <div class="order-summary">
                <h3>Récapitulatif de votre commande</h3>
                <div class="order-items">
                    <?php foreach ($_SESSION['panier'] as $item): 
                        $nom = isset($item['nom']) ? $item['nom'] : (isset($item['name']) ? $item['name'] : 'Produit inconnu');
                        $prix = isset($item['prix']) ? $item['prix'] : (isset($item['price']) ? $item['price'] : 0);
                    ?>
                        <div class="order-item">
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($nom); ?></h4>
                                <p>Prix unitaire: <?php echo number_format($prix, 2); ?> €</p>
                                <p>Quantité: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="item-total">
                                <?php echo number_format($prix * $item['quantity'], 2); ?> €
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-total">
                    <h4>Total à payer: <?php echo number_format($total, 2); ?> €</h4>
                </div>
            </div>

            <!-- Formulaire de livraison -->
            <div class="shipping-form">
                <h3>Informations de livraison</h3>
                <form action="confirm.php" method="POST" id="shipping-form" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="code_postal">Code Postal</label>
                        <input type="text" id="code_postal" name="code_postal" class="form-control" required pattern="[0-9]{5}" title="Le code postal doit contenir 5 chiffres">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" class="form-control" required pattern="[0-9]{10}" title="Le numéro de téléphone doit contenir 10 chiffres">
                    </div>
                    <button type="submit" class="btn btn-commander">Valider la commande</button>
                </form>
            </div>
        </div>
    </main>

    <script>
    function validateForm() {
        var nom = document.getElementById('nom').value;
        var prenom = document.getElementById('prenom').value;
        var adresse = document.getElementById('adresse').value;
        var ville = document.getElementById('ville').value;
        var code_postal = document.getElementById('code_postal').value;
        var email = document.getElementById('email').value;
        var telephone = document.getElementById('telephone').value;

        if (nom === '' || prenom === '' || adresse === '' || ville === '' || code_postal === '' || email === '' || telephone === '') {
            alert('Tous les champs sont obligatoires.');
            return false;
        }

        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if (!emailRegex.test(email)) {
            alert('Adresse email invalide.');
            return false;
        }

        var telephoneRegex = /^[0-9]{10}$/;
        if (!telephoneRegex.test(telephone)) {
            alert('Numéro de téléphone invalide (10 chiffres requis).');
            return false;
        }

        var codePostalRegex = /^[0-9]{5}$/;
        if (!codePostalRegex.test(code_postal)) {
            alert('Code postal invalide (5 chiffres requis).');
            return false;
        }

        return true;
    }
    </script>
</body>
</html>