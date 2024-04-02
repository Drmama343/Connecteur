<?php

session_start();
require ("DB.inc.php");
require ("../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
 
$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_commission'] = "Connexion à la base de données impossible";
	header("Location: ../pages/export.php");
}
else {
	// Récupérer les données du formulaire (année et semestre)
	$annee = isset($_GET['annee']) ? $_GET['annee'] : '';
	$semestre = isset($_GET['semestre']) ? $_GET['semestre'] : '';

	// Vérifier si l'année ou le semestre est vide
	if(empty($annee) || empty($semestre) || !preg_match('/^\d{4}-\d{4}$/', $annee)) {
		$_SESSION['info_commission'] = "Veuillez renseigner l'année correctement, ainsi qu'un semestre";
		header("Location: ../pages/export.php");
	}
	else {
		$templatePath = '../images/ModeleS'.$semestre.'Commission.xlsx';
		$spreadsheet = IOFactory::load($templatePath);

		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getStyle('E1:E2')->getFont()->setSize(18)->setBold(true);

		// Ajouter des données au fichier Excel
		$sheet->setCellValue('E1', "SEMESTRE " . $semestre . " - BUT INFO")
			  ->setCellValue('E2', $annee);

		// Créer un objet Writer pour exporter le fichier Excel
		$writer = new Xlsx($spreadsheet);

		// Nom du fichier à télécharger
		$filename = 'PV Commission S' . $semestre . ' ' . $annee . '.xlsx';

		// Définir les en-têtes HTTP pour le téléchargement du fichier
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$anneebut = 'BUT0';
		switch ($semestre) {
			case '1' || '2':
				$anneebut = 'BUT1';
				break;
			case '3' || '4':
				$anneebut = 'BUT2';
				break;
			case '5' || '6':
				$anneebut = 'BUT3';
				break;
			
			default:
				$anneebut = 'BUT0';
				break;
		}
		
		//recupère les etudiants qui sont de cette année et de cette promo puis fait un compte de ça
		if ($db->getJuryAnnee($anneebut, $annee)==null)
			return null;
		$nbEtu = $db->getJuryAnnee($anneebut, $annee);

		for ($i=0; $i < count($nbEtu); $i++) { 
			$moySem = $db->getJurySemByEtudSem($nbEtu[i]->getCode(), $semestre);
			$moySemComp = $db->getMoyCompSemByEtudSem($nbEtu[i]->getCode(), $semestre, $competence);
			$ligne = $moySem->getRang() + 8;
			$etudiant = $db->getEtudiantsByCode($moySem->getCode());

			$bonus = $moySem->getBonus();

			//infos etudiant
			$sheet->setCellValue('A'.$ligne, $ligne-8) //rang
				->setCellValue('B'.$ligne, $etudiant->getNom())
				->setCellValue('C'.$ligne, $etudiant->getPrenom())
				->setCellValue('D'.$ligne, $etudiant->getParcours())
				->setCellValue('E'.$ligne, $etudiant->getCursus());

			//infos semestre
			$sheet->setCellValue('F'.$ligne, $moySem->getUE())
				->setCellValue('G'.$ligne, $moySem->getMoySem());
			
			switch ($semestre) {
				case 'value':
					# code...
					break;
				
				default:
					# code...
					break;
			}
		}
			
			

		// Envoyer le fichier Excel au navigateur
		$writer->save('php://output');
	}
}

/*
$_SESSION['info_commission'] = "erreur dans l'export";
header("Location: ../pages/export.php");
*/
?>