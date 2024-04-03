<?php

session_start();
require ("DB.inc.php");
require ("../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_jury'] = "Connexion à la base de données impossible";
	header("Location: ../pages/export.php");
}
else {
	// Récupérer les données du formulaire (année et semestre)
	$annee = isset($_GET['annee']) ? $_GET['annee'] : '';
	$semestre = isset($_GET['semestre']) ? $_GET['semestre'] : '';

	// Vérifier si l'année ou le semestre est vide
	if(empty($annee) || empty($semestre) || !preg_match('/^\d{4}-\d{4}$/', $annee)) {
		$_SESSION['info_jury'] = "Veuillez renseigner l'année correctement, ainsi qu'un semestre";
		header("Location: ../pages/export.php");
	}
	else {
		$templatePath = '../images/ModeleS'.$semestre.'Jury.xlsx';
		$spreadsheet = IOFactory::load($templatePath);
		$filename = 'PV Jury S' . $semestre . ' ' . $annee . '.xlsx';

		$sheet = $spreadsheet->getActiveSheet();

		switch ($semestre) {
			case '1' :
				$comp = 'BIN11';
				$anneebut = 'BUT1';
				break;
			case '2' :
				$comp = 'BIN21';
				$anneebut = 'BUT1';
				break;
			case '3' :
				$comp = 'BIN31';
				$anneebut = 'BUT2';
				break;
			case '4' :
				$comp = 'BIN41';
				$anneebut = 'BUT2';
				break;
			case '5' :
				$comp = 'BIN51';
				$anneebut = 'BUT3';
				break;
			case '6' :
				$comp = 'BIN61';
				$anneebut = 'BUT3';
				break;
			
			default:
				$comp = 'BIN0';
				$anneebut = 'BUT0';
				break;
		}

		// Créer un objet Writer pour exporter le fichier Excel
		$writer = new Xlsx($spreadsheet);

		$avisSem = [];
		$etudiant = [];

		$nbEtu = [];
		$nbEtu = $db->getJuryAnneeByAnnees($anneebut, $annee);

		$nbComp = [];
		$nbComp = $db->getCompBySem($semestre);

		

		$ligneDebut = 9;

		foreach ($nbEtu as $etu) {
			$avisSem = $db->getAvisSem($etu->getCode(), $comp, $semestre);
		
			if (!empty($avisSem)) {
				$avis = $avisSem[0];
				$etudiants = $db->getEtudiantsByCode($avis->getCode());
				
		
				foreach ($etudiants as $etudiant) {
					$codenip = $etudiant->getCode();
					$sheet->setCellValue('B'.$ligneDebut, $ligneDebut - 8) //rang
						  ->setCellValue('C'.$ligneDebut, $etudiant->getNom())
						  ->setCellValue('D'.$ligneDebut, $etudiant->getPrenom())
						  ->setCellValue('E'.$ligneDebut, $etudiant->getParcours())
						  ->setCellValue('F'.$ligneDebut, $etudiant->getCursus());
		
					$lettre = 'G';
					foreach ($nbComp as $competence) {
						$avis = $db->getAvisParComp($codenip, $competence);
						if (!empty($avis)) {
							$sheet->setCellValue($lettre.$ligneDebut, $avis);
						} else {
							$sheet->setCellValue($lettre.$ligneDebut, '');
						}
						$lettre++;
					}
		
					$jurySem = $db->getJurySemByEtudSemTest($codenip, $semestre);
		
					if (!empty($jurySem)) {
						foreach ($jurySem as $jury) {
							$sheet->setCellValue('M'.$ligneDebut, $jury->getUE())
								  ->setCellValue('N'.$ligneDebut, $jury->getMoySem());
							$ligneDebut++;
						}
					} else {
						$sheet->setCellValue('M'.$ligneDebut, '')
							  ->setCellValue('N'.$ligneDebut, '');
						$ligneDebut++;
					}
				}
			}
		}

		// Définir les en-têtes HTTP pour le téléchargement du fichier
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		// Envoyer le fichier Excel au navigateur
		$writer->save('php://output');
	}
}
?>