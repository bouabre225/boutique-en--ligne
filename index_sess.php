<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap@5.3.2/css/bootstrap.min.css">
    <title>connection</title>
</head>
<body>
   <main>
    <h2 id="form" class="text-center">Se connecter</h2>
        <form method="post" action="traitement.php" id="list" class="form">
            <label for="login" class="form-label">Login</label>
            <input type="text" id="login" name="login" placeholder="Entrez votre nom d'utilisateur" class="form-control">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" require class="form-control">    
            <input type="submit" value="Se connecter" class="btn">
        </form>
        <p><a href="deconn.php" class="btn">Se déconnecter</a></p>
   </main>
</body>
</html>

