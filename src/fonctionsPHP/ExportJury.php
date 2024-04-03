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

		// Créer un objet Writer pour exporter le fichier Excel
		$writer = new Xlsx($spreadsheet);

		$comp = $db->getCompBySem($semestre);

		$nbEtu = [];
		$nbEtu = $db->getJuryAnneeByAnnees($anneebut, $annee);
		var_dump($nbEtu);
		
		for ($i=0; $i < count($nbEtu); $i++) { 
			$avisSem = $db->getAvisSem($nbEtu[i]->getCode(), $idComp, $semestre);
			$ligne = $avisSem->getRang() + 8;
			var_dump($ligne);
			$etudiant = $db->getEtudiantsByCode($avisSem->getCode());


			//infos etudiant
			$sheet->setCellValue('A'.$ligne, $etudiant->getCode())
				->setCellValue('B'.$ligne, $ligne-8) //rang
				->setCellValue('C'.$ligne, $etudiant->getNom())
				->setCellValue('D'.$ligne, $etudiant->getPrenom())
				->setCellValue('E'.$ligne, $etudiant->getParcours())
				->setCellValue('F'.$ligne, $etudiant->getCursus());

			//infos semestre
			//$sheet->setCellValue('G'.$ligne, $moySem->getUE())
			//	->setCellValue('H'.$ligne, $moySem->getMoySem());

			//for ($i=0; $i < count($dbtfinseq); $i+2) { 
			//	completerMoyCompSem($db, $sheet, $ligne, $nbEtu[i], $semestre, $competence, $moySem->getBonus(), $libelles, $rowData, $dbtfinseq[$i], $dbtfinseq[$i+1]);
			//}
	
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