<div class="nav-container">
    <button class="burger-menu" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="nav-links">
        <a href="index.php?page=acceuil">Page d'acceuil</a>
        <a href="index.php?page=detail">Détails du produits</a>
        <a href="index.php?page=commande">Panier</a>
        <a href="index.php?page=confirmation">Confirmation de commande</a>
        <a href="index.php?page=ajout">Ajouter au catalogue</a>
        <a href="index.php?page=contact">Nous Contacter</a>
    </div>
</div>

<script>
function toggleMenu() {
    document.querySelector('.nav-links').classList.toggle('active');
    document.querySelector('.burger-menu').classList.toggle('active');
}
</script>
