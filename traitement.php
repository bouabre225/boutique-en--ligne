<?php
session_start();
//si il y avait eut une session au préalable on vas la détruire
if(isset($_SESSION['login']) && isset($_SESSION['password']))
{
    session_destroy();
    $_SESSION = array();
    unset($_SESSION);
}

// Récupération des données du formulaire
$login = $_POST['login'] ?? '';
$userPassword = $_POST['password'] ?? '';

// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'admin';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// selection de l'utilisateur dans la base de donnée
$stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login AND password = :password");
$stmt->execute(['login' => $login, 'password' => $userPassword]);
$user = $stmt->fetch();

if ($user) {
    $_SESSION['login'] = $login;
    $_SESSION['password'] = $userPassword;
    header('Location: ./ajout.php');
    exit();
} else {
    echo "Mauvais login ou mot de passe";
}
?>