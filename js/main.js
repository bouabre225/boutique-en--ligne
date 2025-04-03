// Fonction pour mettre à jour la quantité dans le panier
function updateQuantity(input, productId) {
    const quantity = parseInt(input.value);
    if (quantity < 1) {
        input.value = 1;
    }
    input.form.submit();
}

// Fonction pour confirmer la suppression d'un produit
function confirmDelete(form) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit du panier ?')) {
        form.submit();
    }
    return false;
}

// Validation du formulaire de confirmation
function validateForm() {
    const requiredFields = ['nom', 'prenom', 'adresse', 'ville', 'code_postal', 'email', 'telephone'];
    const form = document.getElementById('shipping-form');
    let isValid = true;

    // Réinitialiser les messages d'erreur
    document.querySelectorAll('.error-message').forEach(el => el.remove());

    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            isValid = false;
            showError(input, 'Ce champ est requis');
        } else {
            // Validations spécifiques
            switch (field) {
                case 'email':
                    if (!validateEmail(input.value)) {
                        isValid = false;
                        showError(input, 'Email invalide');
                    }
                    break;
                case 'code_postal':
                    if (!validatePostalCode(input.value)) {
                        isValid = false;
                        showError(input, 'Code postal invalide');
                    }
                    break;
                case 'telephone':
                    if (!validatePhone(input.value)) {
                        isValid = false;
                        showError(input, 'Numéro de téléphone invalide');
                    }
                    break;
            }
        }
    });

    return isValid;
}

// Fonctions de validation
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePostalCode(code) {
    return /^\d{5}$/.test(code);
}

function validatePhone(phone) {
    // Accepte les formats: 0612345678, 06 12 34 56 78, +33612345678, +33 6 12 34 56 78
    phone = phone.replace(/\s/g, ''); // Supprime les espaces
    return /^(0|\+33|0033)[1-9][0-9]{8}$/.test(phone);
}

function showError(input, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
    input.classList.add('error');
}

// Recherche en temps réel
let searchTimeout;
function handleSearch(input) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const searchQuery = input.value.trim();
        if (searchQuery.length >= 2) {
            const form = input.closest('form');
            form.submit();
        }
    }, 100);
}

// Animation d'ajout au panier
function animateAddToCart(button) {
    button.classList.add('adding');
    setTimeout(() => {
        button.classList.remove('adding');
        button.classList.add('added');
        setTimeout(() => {
            button.classList.remove('added');
        }, 1500);
    }, 1000);
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour la recherche en temps réel
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', () => handleSearch(searchInput));
    }

    // Gestionnaire pour le formulaire de confirmation
    const shippingForm = document.getElementById('shipping-form');
    if (shippingForm) {
        shippingForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    }

    // Gestionnaire pour les boutons de suppression
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            confirmDelete(this);
        });
    });

    // Gestionnaire pour les champs de quantité
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            updateQuantity(this);
        });
    });

    // Gestionnaire pour les boutons d'ajout au panier
    document.querySelectorAll('.btn-panier').forEach(button => {
        button.addEventListener('click', function(e) {
            animateAddToCart(this);
        });
    });
});
