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
	exit();
}
else {
	$annee = isset($_GET['annee']) ? $_GET['annee'] : '';

	if(empty($annee) || !preg_match('/^\d{4}-\d{4}$/', $annee)) {
		$_SESSION['info_poursuite'] = "Veuillez renseigner l'année correctement";
		header("Location: ../pages/export.php");
		exit();
	}
	else {
		try {
			$etus = $db->getEtudiants();
		} //fin try
		catch (Exception $e) {
			$_SESSION['info_poursuite'] = $e->getMessage();
			header("Location: ../pages/export.php");
			exit();
		}

		// Créer un objet FPDI
		$pdf = new Fpdi();

		// Chemin vers le fichier PDF modèle
		$pdfTemplate = '../images/Avis_Poursuite_etudes_modele.pdf';

		// Définir le fichier PDF modèle
		$pdf->setSourceFile($pdfTemplate);

		// Importer la première page du modèle PDF
		$templateId = $pdf->importPage(1);

		foreach ($etus as &$etudiant) {
			// Ajouter une page vide
			$pdf->AddPage();
			// Utiliser la page importée comme modèle
			$pdf->useTemplate($templateId);

			try {
				$db->MettreAJourRangsCompetencesParAnnee('BUT1');
				$db->MettreAJourRangsCompetencesParAnnee('BUT2');
				$db->MettreAJourRangsCompetencesParAnnee('BUT3');
				$note1 = $db->getMoyCompAnnee($etudiant->getCode(), 'BUT1');
				$note2 = $db->getMoyCompAnnee($etudiant->getCode(), 'BUT2');
				$note3 = $db->getMoyCompAnnee($etudiant->getCode(), 'BUT3');
				$jury1 = $db->getJuryAnnee($etudiant->getCode(), 'BUT1');
				$jury2 = $db->getJuryAnnee($etudiant->getCode(), 'BUT2');
				$jury3 = $db->getJuryAnnee($etudiant->getCode(), 'BUT3');
				$maths1 = $db->MoyenneMathsParAnnee($etudiant->getCode(), 'BUT1');
				$maths2 = $db->MoyenneMathsParAnnee($etudiant->getCode(), 'BUT2');
				$maths3 = $db->MoyenneMathsParAnnee($etudiant->getCode(), 'BUT3');
				$anglais1 = $db->MoyenneAnglaisParAnnee($etudiant->getCode(), 'BUT1');
				$anglais2 = $db->MoyenneAnglaisParAnnee($etudiant->getCode(), 'BUT2');
			} //fin try
			catch (Exception $e) {
				$_SESSION['info_poursuite'] = $e->getMessage();
				header("Location: ../pages/export.php");
				exit();
			}

			$pdf->SetFont('Arial', '', 10);
			$pdf->SetTextColor(0, 0, 0);

			//Nom
			$pdf->SetXY(73.5, 44.2);
			$pdf->Cell(120, 10, $etudiant->getNom() . " " . $etudiant->getPrenom(), 0, 0, 'L');

			//Apprentissage
			if($etudiant->getApprentissage() === "S4")
			{
				$pdf->SetXY(88, 48.5);
				$pdf->Cell(27, 10, 'Non', 0, 0, 'L');
				$pdf->SetXY(130, 48.5);
				$pdf->Cell(26, 10, 'Oui', 0, 0, 'L');
				$pdf->SetXY(170, 48.5);
				$pdf->Cell(27, 10, 'Oui', 0, 0, 'L');
			}
			else if ($etudiant->getApprentissage() === "S6") {
				$pdf->SetXY(88, 48.5);
				$pdf->Cell(27, 10, 'Non', 0, 0, 'L');
				$pdf->SetXY(130, 48.5);
				$pdf->Cell(26, 10, 'Non', 0, 0, 'L');
				$pdf->SetXY(170, 48.5);
				$pdf->Cell(27, 10, 'Oui', 0, 0, 'L');
			}
			else {
				$pdf->SetXY(88, 48.5);
				$pdf->Cell(27, 10, 'Non', 0, 0, 'L');
				$pdf->SetXY(130, 48.5);
				$pdf->Cell(26, 10, 'Non', 0, 0, 'L');
				$pdf->SetXY(170, 48.5);
				$pdf->Cell(27, 10, 'Non', 0, 0, 'L');
			}

			//parcours
			$pdf->SetXY(88, 53);
			$pdf->Cell(27, 10, $etudiant->getParcours(), 0, 0, 'L');
			$pdf->SetXY(130, 53);
			$pdf->Cell(26, 10, $etudiant->getParcours(), 0, 0, 'L');
			$pdf->SetXY(170, 53);
			$pdf->Cell(27, 10, $etudiant->getParcours(), 0, 0, 'L');

			//etranger
			$pdf->SetXY(73.5, 62);
			$pdf->Cell(120, 10, $etudiant->getMobEtrang(), 0, 0, 'L');

			//BUT1 - Moy
			$pdf->SetXY(80, 88);
			$pdf->Cell(10, 10, (count($note1) >= 1 ? $note1[0]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 93);
			$pdf->Cell(10, 10, (count($note1) >= 2 ? $note1[1]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 98);
			$pdf->Cell(10, 10, (count($note1) >= 3 ? $note1[2]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 103);
			$pdf->Cell(10, 10, (count($note1) >= 4 ? $note1[3]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 108.5);
			$pdf->Cell(10, 10, (count($note1) >= 5 ? $note1[4]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 114);
			$pdf->Cell(10, 10, (count($note1) >= 6 ? $note1[5]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 119);
			$pdf->Cell(10, 10, (count($maths1) >= 1 ? $maths1["moyennemathsparannee"] : "~"), 0, 0, 'L');
			$pdf->SetXY(80, 124);
			$pdf->Cell(10, 10, (count($anglais1) >= 1 ? $anglais1["moyenneanglaisparannee"] : "~"), 0, 0, 'L');

			//BUT1 - Rang
			$pdf->SetXY(93, 88);
			$pdf->Cell(10, 10, (count($note1) >= 1 ? $note1[0]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(93, 93);
			$pdf->Cell(10, 10, (count($note1) >= 2 ? $note1[1]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(93, 98);
			$pdf->Cell(10, 10, (count($note1) >= 3 ? $note1[2]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(93, 103);
			$pdf->Cell(10, 10, (count($note1) >= 4 ? $note1[3]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(93, 108.5);
			$pdf->Cell(10, 10, (count($note1) >= 5 ? $note1[4]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(93, 114);
			$pdf->Cell(10, 10, (count($note1) >= 6 ? $note1[5]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(93, 119);
			$pdf->Cell(10, 10, '~', 0, 0, 'L');
			$pdf->SetXY(93, 124);
			$pdf->Cell(10, 10, '~', 0, 0, 'L');

			//BUT2 - Moy
			$pdf->SetXY(105, 88);
			$pdf->Cell(10, 10, (count($note2) >= 1 ? $note2[0]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 93);
			$pdf->Cell(10, 10, (count($note2) >= 2 ? $note2[1]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 98);
			$pdf->Cell(10, 10, (count($note2) >= 3 ? $note2[2]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 103);
			$pdf->Cell(10, 10, (count($note2) >= 4 ? $note2[3]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 108.5);
			$pdf->Cell(10, 10, (count($note2) >= 5 ? $note2[4]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 114);
			$pdf->Cell(10, 10, (count($note2) >= 6 ? $note2[5]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 119);
			$pdf->Cell(10, 10, (count($maths2) >= 1 ? $maths2["moyennemathsparannee"] : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 124);
			$pdf->Cell(10, 10, (count($anglais2) >= 1 ? $anglais2["moyenneanglaisparannee"] : "~"), 0, 0, 'L');

			//BUT2 - Rang
			$pdf->SetXY(118, 88);
			$pdf->Cell(10, 10, (count($note2) >= 1 ? $note2[0]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(118, 93);
			$pdf->Cell(10, 10, (count($note2) >= 2 ? $note2[1]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(118, 98);
			$pdf->Cell(10, 10, (count($note2) >= 3 ? $note2[2]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(118, 103);
			$pdf->Cell(10, 10, (count($note2) >= 4 ? $note2[3]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(118, 108.5);
			$pdf->Cell(10, 10, (count($note2) >= 5 ? $note2[4]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(118, 114);
			$pdf->Cell(10, 10, (count($note2) >= 6 ? $note2[5]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(118, 119);
			$pdf->Cell(10, 10, '~', 0, 0, 'L');
			$pdf->SetXY(118, 124);
			$pdf->Cell(10, 10, '~', 0, 0, 'L');

			//Absences
			$pdf->SetXY(80, 129.5);
			$pdf->Cell(20, 10, (count($jury1) >= 1 ? $jury1[0]->getAbsInjust() : "~"), 0, 0, 'L');
			$pdf->SetXY(105, 129.5);
			$pdf->Cell(20, 10, (count($jury2) >= 1 ? $jury2[0]->getAbsInjust() : "~"), 0, 0, 'L');

			//BUT3 - Moy
			$pdf->SetXY(91, 147);
			$pdf->Cell(10, 10, (count($note3) >= 1 ? $note3[0]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(91, 153);
			$pdf->Cell(10, 10, (count($note3) >= 2 ? $note3[1]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(91, 173);
			$pdf->Cell(10, 10, (count($note3) >= 3 ? $note3[2]->getMoyCompAnnee() : "~"), 0, 0, 'L');
			$pdf->SetXY(91, 178);
			$pdf->Cell(10, 10, (count($maths3) >= 1 ? $maths3["moyennemathsparannee"] : "~"), 0, 0, 'L');

			//BUT3 - Rang
			$pdf->SetXY(111, 147);
			$pdf->Cell(10, 10, (count($note3) >= 1 ? $note3[0]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(111, 153);
			$pdf->Cell(10, 10, (count($note3) >= 2 ? $note3[1]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(111, 173);
			$pdf->Cell(10, 10, (count($note3) >= 3 ? $note3[2]->getRang() : "~"), 0, 0, 'L');
			$pdf->SetXY(111, 178);
			$pdf->Cell(10, 10, '~', 0, 0, 'L');

			//Absences
			$pdf->SetXY(91, 183.5);
			$pdf->Cell(20, 10, (count($jury3) >= 1 ? $jury3[0]->getAbsInjust() : "~"), 0, 0, 'L');

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
			$pdf->Cell(10, 10, $etudiant->getCommentaire(), 0, 0, 'L');
		}

		// Nom du fichier du nouveau PDF
		$newPdfFile = 'poursuiteEtude_' . $annee . '.pdf';

		// Enregistrer le nouveau PDF
		$pdf->Output($newPdfFile, 'D');
	}
}
?>