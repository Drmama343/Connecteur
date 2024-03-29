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

	$pdf->SetFont('Arial', '', 10);
	$pdf->SetTextColor(0, 0, 0);

	//Nom
	$pdf->SetXY(73.5, 44.2);
	$pdf->Cell(120, 10, 'Baillobay Trystan le bg', 0, 0, 'L');

	//Apprentissage
	$pdf->SetXY(88, 48.5);
	$pdf->Cell(27, 10, '~', 0, 0, 'L');
	$pdf->SetXY(88, 53);
	$pdf->Cell(27, 10, '~', 0, 0, 'L');

	$pdf->SetXY(130, 48.5);
	$pdf->Cell(26, 10, '~', 0, 0, 'L');
	$pdf->SetXY(130, 53);
	$pdf->Cell(26, 10, '~', 0, 0, 'L');

	$pdf->SetXY(170, 48.5);
	$pdf->Cell(27, 10, '~', 0, 0, 'L');
	$pdf->SetXY(170, 53);
	$pdf->Cell(27, 10, '~', 0, 0, 'L');

	//etranger
	$pdf->SetXY(73.5, 62);
	$pdf->Cell(120, 10, '~', 0, 0, 'L');

	//BUT1 - Moy
	$pdf->SetXY(80, 88);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(80, 93);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(80, 98);
	$pdf->Cell(10, 10, '18,59', 0, 0, 'L');
	$pdf->SetXY(80, 103);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(80, 108.5);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(80, 114);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(80, 119);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(80, 124);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');

	//BUT1 - Rang
	$pdf->SetXY(93, 88);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 93);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 98);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 103);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 108.5);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 114);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 119);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(93, 124);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');

	//BUT2 - Moy
	$pdf->SetXY(105, 88);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 93);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 98);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 103);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 108.5);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 114);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 119);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 124);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');

	//BUT2 - Rang
	$pdf->SetXY(118, 88);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 93);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 98);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 103);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 108.5);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 114);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 119);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(118, 124);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');

	//Absences
	$pdf->SetXY(80, 129.5);
	$pdf->Cell(20, 10, '~', 0, 0, 'L');
	$pdf->SetXY(105, 129.5);
	$pdf->Cell(20, 10, '~', 0, 0, 'L');

	//BUT3 - Moy
	$pdf->SetXY(91, 147);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(91, 153);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(91, 173);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(91, 178);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');

	//BUT3 - Rang
	$pdf->SetXY(111, 147);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(111, 153);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(111, 173);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');
	$pdf->SetXY(111, 178);
	$pdf->Cell(10, 10, '~', 0, 0, 'L');

	//Absences
	$pdf->SetXY(91, 183.5);
	$pdf->Cell(20, 10, '~', 0, 0, 'L');

	//Cases à cocher (ingé)
	$pdf->SetFont('Arial', 'B', 5);
	$pdf->SetXY(81.2, 215.5);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(106.2, 215.5);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(129.9, 215.5);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(153.7, 215.5);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(181.3, 215.5);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');

	//Cases à cocher (master)
	$pdf->SetXY(81.2, 223);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(106.2, 223);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(129.9, 223);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(153.7, 223);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');
	$pdf->SetXY(181.3, 223);
	$pdf->Cell(10, 10, 'x', 0, 0, 'L');

	$pdf->SetFont('Arial', '', 10);

	//Total promo
	$pdf->SetXY(28, 240.9);
	$pdf->Cell(10, 10, '53', 0, 0, 'L');

	//Nb avis (ingé)
	$pdf->SetXY(70, 230.2);
	$pdf->Cell(26, 10, '~', 0, 0, 'C');
	$pdf->SetXY(102.8, 230.2);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');
	$pdf->SetXY(126.6, 230.2);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');
	$pdf->SetXY(150.5, 230.2);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');
	$pdf->SetXY(178, 230.2);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');

	//Nb avis (master)
	$pdf->SetXY(70, 238.3);
	$pdf->Cell(26, 10, '~', 0, 0, 'C');
	$pdf->SetXY(102.8, 238.3);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');
	$pdf->SetXY(126.6, 238.3);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');
	$pdf->SetXY(150.5, 238.3);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');
	$pdf->SetXY(178, 238.3);
	$pdf->Cell(10, 10, '~', 0, 0, 'C');

	//Commentaire
	$pdf->SetXY(41, 247);
	$pdf->Cell(10, 10, 'Ceci est un commentaire', 0, 0, 'L');

	// Nom du fichier du nouveau PDF
	$newPdfFile = 'nouveau_pdf.pdf';

	// Enregistrer le nouveau PDF
	$pdf->Output($newPdfFile, 'D');
}
?>