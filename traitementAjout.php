<?php
require_once("./database.php");

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Recuperer la photo par le nom et le mettre dans un dossier en créant ce dossier s'il n'existe pas
if (!file_exists("./images")) {
    mkdir("./images", 0777, true);
}

// Recuperer les données du formulaire
$name = $_POST['name'];
$description = $_POST['description'];
$categorie = $_POST['categorie'];
$prix = $_POST['prix'];
$remise = $_POST['remise'];

// Debug: Afficher les informations sur le fichier
echo "<pre>";
echo "Informations sur le fichier uploadé :\n";
var_dump($_FILES);
echo "</pre>";

// Traitement de l'image
$image = '';
if (isset($_FILES['image'])) {
    $error = $_FILES['image']['error'];
    switch($error) {
        case UPLOAD_ERR_OK:
            $image = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            
            // Vérifier le type de fichier
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['image']['type'], $allowed)) {
                die("Type de fichier non autorisé. Seuls les formats JPEG, PNG et GIF sont acceptés.");
            }
            
            // Debug: Vérifier le chemin de destination
            $destination = "./images/" . $image;
            echo "Tentative de déplacement vers : " . $destination . "\n";
            
            // Déplacer le fichier
            if (!move_uploaded_file($tmpName, $destination)) {
                die("Erreur lors du téléchargement de l'image. Vérifiez les permissions du dossier images.");
            }
            break;
        case UPLOAD_ERR_INI_SIZE:
            die("Le fichier dépasse la taille autorisée par PHP.ini");
        case UPLOAD_ERR_FORM_SIZE:
            die("Le fichier dépasse la taille autorisée par le formulaire");
        case UPLOAD_ERR_PARTIAL:
            die("Le fichier n'a été que partiellement uploadé");
        case UPLOAD_ERR_NO_FILE:
            die("Aucun fichier n'a été uploadé");
        default:
            die("Erreur inconnue lors de l'upload : " . $error);
    }
} else {
    die("Le champ 'image' n'existe pas dans le formulaire");
}

// Insérer les données dans la base de données
$database = new Database();
$pdo = $database->getConnection();
$stmt = $pdo->prepare("INSERT INTO produits (id, nom, detail, categorie, image, prix, remise) VALUES (NULL, :name, :description, :categorie, :image, :prix, :remise)");
$stmt->execute([
    'name' => $name,
    'description' => $description,
    'categorie' => $categorie,
    'prix' => $prix,
    'remise' => $remise,
    'image' => $image
]);

// Rediriger vers la page des produits
header("Location: index.php?page=detail");
exit();

?>