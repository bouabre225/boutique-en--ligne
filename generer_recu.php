<?php
session_start();
require_once('database.php');
require_once(__DIR__ . '/fpdf/fpdf.php');

// Vérifier si le panier existe et n'est pas vide
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header('Location: panier.php');
    exit;
}

// Créer un nouveau PDF
$pdf = new FPDF();
$pdf->AddPage();

// En-tête du reçu
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Recu de commande', 0, 1, 'C');
$pdf->Ln(10);

// Informations de la boutique
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Boutique en ligne', 0, 1, 'C');
$pdf->Cell(190, 10, 'Date: ' . date('d/m/Y H:i'), 0, 1, 'C');
$pdf->Ln(10);

// En-tête du tableau
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Produit', 1);
$pdf->Cell(30, 10, 'Prix unit.', 1);
$pdf->Cell(30, 10, 'Quantite', 1);
$pdf->Cell(50, 10, 'Total', 1);
$pdf->Ln();

// Contenu du tableau
$pdf->SetFont('Arial', '', 12);
$total = 0;

foreach ($_SESSION['panier'] as $item) {
    // Gérer la compatibilité avec les anciennes et nouvelles clés
    $nom = isset($item['nom']) ? $item['nom'] : (isset($item['name']) ? $item['name'] : 'Produit inconnu');
    $prix = isset($item['prix']) ? $item['prix'] : (isset($item['price']) ? $item['price'] : 0);
    $quantite = $item['quantity'];
    
    $sous_total = $prix * $quantite;
    $total += $sous_total;

    $pdf->Cell(80, 10, utf8_decode($nom), 1);
    $pdf->Cell(30, 10, number_format($prix, 2) . ' EUR', 1);
    $pdf->Cell(30, 10, $quantite, 1);
    $pdf->Cell(50, 10, number_format($sous_total, 2) . ' EUR', 1);
    $pdf->Ln();
}

// Total général
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 10, 'Total general:', 0);
$pdf->Cell(50, 10, number_format($total, 2) . ' EUR', 1);

// Pied de page
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(190, 10, 'Merci de votre confiance !', 0, 1, 'C');

// Générer le PDF
$pdf->Output('D', 'recu_commande.pdf');

// Vider le panier après la génération de la facture
unset($_SESSION['panier']);
unset($_SESSION['livraison']);