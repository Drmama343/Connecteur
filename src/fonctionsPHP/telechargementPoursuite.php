<?php

session_start();
require ("DB.inc.php");
require_once('../../fpdf/fpdf.php');
require_once('../../vendor/autoload.php');

use setasign\Fpdi\Fpdi;

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_poursuite'] = "Connexion à la base de données impossible";
	header("Location: ../pages/export.php");
}
else {
	// Créer un objet FPDI
	$pdf = new Fpdi();

	// Chemin vers le fichier PDF modèle
	$pdfTemplate = '../images/Avis_Poursuite_etudes_modele.pdf';

	// Définir le fichier PDF modèle
	$pdf->setSourceFile($pdfTemplate);

	// Ajouter une page vide
	$pdf->AddPage();

	// Importer la première page du modèle PDF
	$templateId = $pdf->importPage(1);

	// Utiliser la page importée comme modèle
	$pdf->useTemplate($templateId);

	// Ajouter du texte sur la page importée
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetXY(0, 44); // Position du texte
	$pdf->Cell(0, 10, 'Lecarpentier Luc', 0, 1, 'C');

	// Nom du fichier du nouveau PDF
	$newPdfFile = 'nouveau_pdf.pdf';

	// Enregistrer le nouveau PDF
	$pdf->Output($newPdfFile, 'D');

	$_SESSION['info_poursuite'] = "Votre fichier pdf est exporté";
	header("Location: ../pages/export.php");
}
?>