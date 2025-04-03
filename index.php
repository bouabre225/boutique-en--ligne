<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/bootstrap@5.3.2/css/bootstrap.min.css">
    <title>Vente en ligne</title>
</head>
<body>
    <header class="header">
        <?php include ("./barRechercher.php"); ?>
    </header>
    <nav >
        <?php include ("./structure/nav.php"); ?>
    </nav>
    <main class="main">
        <?php
        if (isset ($_REQUEST['page']) && !empty ($_REQUEST['page']))
        {
            switch ($_REQUEST['page'])
            {
                case "acceuil" : include ("./acceuil.php");
                break;
                case "detail" : include ("./details.php");
                break;
                case "commande" : include ("./panier.php");
                break;
                case "confirmation" : include ("./confirm.php");
                break;
                case "ajout" : include ("./ajouter.php");
                break;
                case "contact" : include ("./contact.php");
                break;
                default : include ("./acceuil.php");
            }
        } else {
            include ("./acceuil.php");
        }
        ?>
    </main>
    <footer class="footer">
        <?php include ("./structure/foot.php"); ?>
    </footer>
</body>
</html>