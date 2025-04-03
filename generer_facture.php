<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('database.php');

// Vérifier si FPDF est installé
if (!file_exists(__DIR__ . '/fpdf/fpdf.php')) {
    die("Erreur: La bibliothèque FPDF n'est pas installée.");
}
require_once(__DIR__ . '/fpdf/fpdf.php');

// Vérifier si le panier et les informations de livraison existent
if (!isset($_SESSION['panier']) || empty($_SESSION['panier']) || !isset($_SESSION['livraison'])) {
    header('Location: index.php?page=detail');
    exit;
}

try {
    // Créer le dossier temp s'il n'existe pas
    if (!file_exists(__DIR__ . '/temp')) {
        mkdir(__DIR__ . '/temp', 0777, true);
    }

    // Vérifier les permissions du dossier temp
    if (!is_writable(__DIR__ . '/temp')) {
        throw new Exception("Le dossier temp n'est pas accessible en écriture");
    }

    // Créer un nouveau PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // En-tête de la facture
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'FACTURE', 0, 1, 'C');
    $pdf->Ln(10);

    // Informations de la boutique
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'Boutique en ligne', 0, 1, 'L');
    $pdf->Cell(190, 10, 'Date: ' . date('d/m/Y H:i'), 0, 1, 'L');
    $pdf->Cell(190, 10, 'N° Facture: ' . uniqid('FAC'), 0, 1, 'L');
    $pdf->Ln(10);

    // Informations client
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Adresse de livraison:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, $_SESSION['livraison']['prenom'] . ' ' . $_SESSION['livraison']['nom'], 0, 1, 'L');
    $pdf->Cell(190, 10, $_SESSION['livraison']['adresse'], 0, 1, 'L');
    $pdf->Cell(190, 10, $_SESSION['livraison']['code_postal'] . ' ' . $_SESSION['livraison']['ville'], 0, 1, 'L');
    $pdf->Cell(190, 10, 'Email: ' . $_SESSION['livraison']['email'], 0, 1, 'L');
    $pdf->Cell(190, 10, 'Tel: ' . $_SESSION['livraison']['telephone'], 0, 1, 'L');
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
    $pdf->Cell(140, 10, 'Total TTC:', 0);
    $pdf->Cell(50, 10, number_format($total, 2) . ' EUR', 1);

    // Conditions de vente
    $pdf->Ln(20);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(190, 10, 'Merci de votre confiance !', 0, 1, 'C');
    $pdf->Cell(190, 10, 'Cette facture fait office de garantie pour vos produits.', 0, 1, 'C');

    // Sauvegarder le PDF temporairement
    $pdfFileName = 'facture_' . uniqid() . '.pdf';
    $tempFile = __DIR__ . '/temp/' . $pdfFileName;
    
    // Vérifier si le fichier peut être créé
    if ($pdf->Output('F', $tempFile) === false) {
        throw new Exception("Impossible de créer le fichier PDF");
    }

    // Vérifier si le fichier a bien été créé
    if (!file_exists($tempFile)) {
        throw new Exception("Le fichier PDF n'a pas été créé correctement");
    }

    // Vider le panier et les informations de livraison
    $_SESSION['panier'] = array();
    unset($_SESSION['livraison']);

    // Télécharger le PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="facture.pdf"');
    header('Content-Length: ' . filesize($tempFile));
    
    if (readfile($tempFile) === false) {
        throw new Exception("Erreur lors de l'envoi du fichier");
    }

    // Supprimer le fichier temporaire
    @unlink($tempFile);
    exit();

} catch (Exception $e) {
    // En cas d'erreur, rediriger vers la page de confirmation avec un message d'erreur
    header('Location: confirm.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>