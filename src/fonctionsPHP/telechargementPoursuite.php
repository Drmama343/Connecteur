<?php

session_start();
require ("DB.inc.php");
require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_poursuite'] = "Connexion à la base de données impossible";
	header("Location: ../pages/export.php");
}

// Créer un nouvel objet FPDF
$pdf = new Fpdi();

// Ajouter une nouvelle page
$pdf->AddPage();

// Paramètres du document
$pdf->SetTitle('Rapport_' . $year . '_' . $semester);

// Contenu du PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Rapport', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Année : ' . $year, 0, 1, 'L');
$pdf->Cell(0, 10, 'Semestre : ' . $semester, 0, 1, 'L');

// Nom du fichier à télécharger
$filename = 'rapport_' . $year . '_' . $semester . '.pdf';

// Définir les en-têtes HTTP pour le téléchargement du fichier
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Sortie du PDF vers le navigateur
echo $pdf->Output('D');
?>