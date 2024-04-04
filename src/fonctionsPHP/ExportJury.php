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
		$mcs = [];
		$juryAnnee = [];
		$cpt = 0;

		$nbEtu = [];
		$nbEtu = $db->getJurySemByAnneeSem ($annee, $semestre);

		$nbComp = [];
		$nbComp = $db->getCompBySem($semestre);

		

		if ($semestre % 2 != 0)
		foreach ($nbEtu as $etu) {
			$val = $db->getEtudiantsByCode($etu->getCode());
			$etudiant = $val[0];
			$codenip = $etudiant->getCode();
			$avisSem = $db->getAvisSem($codenip, $comp, $semestre);
			$avis = $avisSem[0];
			$ligneDebut = $etu->getRang()+8;
			$sheet	->setCellValue('A'.$ligneDebut, $codenip)
					->setCellValue('B'.$ligneDebut, $ligneDebut - 8) //rang
					->setCellValue('C'.$ligneDebut, $etudiant->getNom())
					->setCellValue('D'.$ligneDebut, $etudiant->getPrenom())
					->setCellValue('E'.$ligneDebut, $etudiant->getParcours())
					->setCellValue('F'.$ligneDebut, $etudiant->getCursus());

			$lettre = 'G';
			for ($cpt = 0; $cpt < count($nbComp); $cpt++) {
				$mcs = $db->getAvisSem($codenip, $nbComp[$cpt]->getIdComp(), $semestre);
				$sheet->setCellValue($lettre.$ligneDebut, $mcs[0]->getAvis());
				$lettre++;
			}

			$jurySem = $db->getJurySemByEtudSem($codenip, $semestre);
			$sheet->setCellValue('M'.$ligneDebut, $jurySem[0]->getUE())
				->setCellValue('N'.$ligneDebut, $jurySem[0]->getMoySem());

			moyennesComp('O', $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);

		}
		if ($semestre % 2 == 0) {
			foreach ($nbEtu as $etu) {
				$val = $db->getEtudiantsByCode($etu->getCode());
				$etudiant = $val[0];
				$codenip = $etudiant->getCode();
				$avisSem = $db->getAvisSem($codenip, $comp, $semestre);
				$avis = $avisSem[0];
				$ligneDebut = $etu->getRang()+8;

				$juryAnnee = $db->getJuryAnnee($codenip, $nomannee);


				$sheet	->setCellValue('A'.$ligneDebut, $codenip)
						->setCellValue('B'.$ligneDebut, $ligneDebut - 8) //rang
						->setCellValue('C'.$ligneDebut, $etudiant->getNom())
						->setCellValue('D'.$ligneDebut, $etudiant->getPrenom())
						->setCellValue('E'.$ligneDebut, $etudiant->getParcours())
						->setCellValue('F'.$ligneDebut, $etudiant->getCursus())
						/*RCUEs*/;

				moyennesComp('H', $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);
			}


			//compétences BUT
			switch ($semestre) {
				case 4 :
					moyennesComp('N', $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);
					break;
					
				case 6 :
					moyennesComp('N', $ligneDebut, $mcs, $nbComp, $codenip, 4,         $db, $sheet);
					moyennesComp('T', $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);
					break;
			}

			// UE ANNEE
			// DECISION ANNEE
		}

		// Définir les en-têtes HTTP pour le téléchargement du fichier
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		// Envoyer le fichier Excel au navigateur
		$writer->save('php://output');
	}
}

function moyennesComp ($lettre, $ligne, $mcs, $nbComp, $codenip, $semestre, $db, $sheet) {
	$cpt = 0;
	for ($cpt = 0; $cpt < count($nbComp); $cpt++) {
		$mcs = $db->getAvisSem($codenip, $nbComp[$cpt]->getIdComp(), $semestre);
		$sheet->setCellValue($lettre.$ligne, $mcs[0]->getMoyCompSem());
		$lettre++;
	}
}
?>