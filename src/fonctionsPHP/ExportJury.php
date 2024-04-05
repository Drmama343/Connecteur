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
				$incr = 1;
				break;
			case '4' :
				$comp = 'BIN41';
				$anneebut = 'BUT2';
				$incr = 1;
				break;
			case '5' :
				$comp = 'BIN51';
				$anneebut = 'BUT3';
				$incr = 2;
				break;
			case '6' :
				$comp = 'BIN61';
				$anneebut = 'BUT3';
				$incr = 2;
				break;
			
			default:
				$comp = 'BIN0';
				$anneebut = 'BUT0';
				$incr = 0;
				break;
		}

		// Créer un objet Writer pour exporter le fichier Excel
		$writer = new Xlsx($spreadsheet);
		$db->MettreAJourMoyenneAnnee($anneebut, $annee);
		$db->MettreAJourRangsAnnee($anneebut, $annee);

		$avisSem = [];
		$etudiant = [];
		$mcs = [];
		$mca = [];
		$juryAnnee = [];
		$cpt = 0;
		$lettreFinComps = ' ';

		$nbEtu = [];
		$nbEtu = $db->getJuryAnneeByAnnees ($anneebut, $annee);	// getJurySemByAnneeSem

		$nbComp = [];
		$nbComp = $db->getCompBySem($semestre);
		

		if ( $semestre == 1 )
		foreach ( $nbEtu as $etu ) {
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
			$sheet	->setCellValue('M'.$ligneDebut, $jurySem[0]->getUE())
					->setCellValue('N'.$ligneDebut, $jurySem[0]->getMoySem());

					moyenneCompSem('O', $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);
		}

		if ( $semestre >= 2 ) {
			foreach ( $nbEtu as $etu ) {
				$val = $db->getEtudiantsByCode($etu->getCode());
				$etudiant = $val[0];
				$codenip = $etudiant->getCode();
				$avisSem = $db->getAvisSem($codenip, $comp, $semestre);
				foreach($avisSem as $a) {$avis = $a;break;}
				$ligneDebut = $etu->getRang()+8;

				$mca = $db->getMoyCompAnneeByComp($codenip, $anneebut, $annee, 1);

				$juryAnnee = $db->getJuryAnnee($codenip, $anneebut, $annee);


				$sheet	->setCellValue('A'.$ligneDebut, $codenip)
						->setCellValue('B'.$ligneDebut, $ligneDebut - 8) //rang
						->setCellValue('C'.$ligneDebut, $etudiant->getNom())
						->setCellValue('D'.$ligneDebut, $etudiant->getPrenom())
						->setCellValue('E'.$ligneDebut, $etudiant->getParcours())
						->setCellValue('F'.$ligneDebut, $etudiant->getCursus());
				$lettre = 'G';

				if ( $semestre == 2 ) {
					$sheet->setCellValue('G'.$ligneDebut, $juryAnnee[0]->getRCUE());
					$lettre++;
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbComp, $codenip, 2,         $db, $sheet, anneeMoins($annee, 0), $anneebut);
					$lettre = chr (ord($lettre) + 6);
					$juryAnnee = $db->getJuryAnnee($codenip, $anneebut, $annee);
					$sheet	->setCellValue($lettre++.$ligneDebut, $juryAnnee[0]->getMoyAnnee());
					moyenneCompAnnee ($lettre, $ligneDebut, $mca, $nbComp, $codenip, $semestre, $db, $sheet, $annee, $anneebut );
					$lettre = chr (ord($lettre) + 6);
					$sheet	->setCellValue($lettre.$ligneDebut, $juryAnnee[0]->getDecision());
				}
				
				if ( $semestre == 3 ) {
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbComp, $codenip, 2,         $db, $sheet, anneeMoins($annee, 1), 'BUT1');
					$jurySem = $db->getJurySemByEtudSem($codenip, $semestre);
					$sheet	->setCellValue('M'.$ligneDebut, $jurySem[0]->getUE())
							->setCellValue('N'.$ligneDebut, $jurySem[0]->getMoySem());
					moyenneCompSem('O', $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);
				}

				if ( $semestre == 4 ) {
					$sheet->setCellValue('G'.$ligneDebut, $juryAnnee[0]->getRCUE());
					$lettre++;
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbComp, $codenip, 2,         $db, $sheet, anneeMoins($annee, 1), 'BUT1');
					$lettre = chr (ord($lettre) + 6);
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbComp, $codenip, $semestre, $db, $sheet, $annee, $anneebut);
					$lettre = chr (ord($lettre) + 6);
					$juryAnnee = $db->getJuryAnnee($codenip, $anneebut, $annee);
					$sheet	->setCellValue($lettre++.$ligneDebut, $juryAnnee[0]->getMoyAnnee());
					moyenneCompAnnee ($lettre, $ligneDebut, $mca, $nbComp, $codenip, $semestre, $db, $sheet, $annee, $anneebut );
					$sheet	->setCellValue('AA'.$ligneDebut, $juryAnnee[0]->getDecision());
				}

				if ( $semestre == 5 ) {
					$nbCompTemp = $db->getCompBySem(2);
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbCompTemp, $codenip, 2,         $db, $sheet, anneeMoins($annee, 2), 'BUT1');
					$lettre = chr (ord($lettre) + 6);
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbCompTemp, $codenip, 4,         $db, $sheet, anneeMoins($annee, 1), 'BUT2');
					$lettre = chr (ord($lettre) + 6);
					$jurySem = $db->getJurySemByEtudSem($codenip, $semestre);
					$juryAnnee = $db->getJuryAnnee($codenip, $anneebut, $annee);
					$sheet	->setCellValue($lettre++.$ligneDebut, $juryAnnee[0]->getDecision());
					$sheet	->setCellValue($lettre++.$ligneDebut, $jurySem[0]->getUE())
							->setCellValue($lettre++.$ligneDebut, $jurySem[0]->getMoySem());
					moyenneCompSem($lettre, $ligneDebut, $mcs, $nbComp, $codenip, $semestre, $db, $sheet);
				}

				if ( $semestre == 6 ) {
					$juryAnnee = $db->getJuryAnnee($codenip, 'BUT2', anneeMoins($annee, 1));
					$sheet->setCellValue('G'.$ligneDebut, $juryAnnee[0]->getRCUE());
					$lettre++;
					$nbCompTemp = $db->getCompBySem(2);
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbCompTemp, $codenip, 2,         $db, $sheet, anneeMoins($annee, 2), 'BUT1');
					$lettre = chr (ord($lettre) + 6);
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbCompTemp, $codenip, 4,         $db, $sheet, anneeMoins($annee, 1), 'BUT2');
					$lettre = chr (ord($lettre) + 6);
					avisCompAnnee($lettre, $ligneDebut, $mca, $nbComp, $codenip, 6,         $db, $sheet, $annee, 'BUT3');
					$lettre = chr (ord($lettre) + 3);
					$jurySem = $db->getJurySemByEtudSem($codenip, $semestre);
					$juryAnnee = $db->getJuryAnnee($codenip, $anneebut, $annee);
					$sheet	->setCellValue($lettre++.$ligneDebut, $juryAnnee[0]->getMoyAnnee());
					moyenneCompAnnee ($lettre, $ligneDebut, $mca, $nbComp, $codenip, $semestre, $db, $sheet, $annee, $anneebut );
					$sheet	->setCellValue('AA'.$ligneDebut, $juryAnnee[0]->getDecision());
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

function moyenneCompSem ($lettre, $ligne, $mcs, $nbComp, $codenip, $semestre, $db, $sheet) {
	for ($cpt = 0; $cpt < count($nbComp); $cpt++) {
		$mcs = $db->getAvisSem($codenip, $nbComp[$cpt]->getIdComp(), $semestre);
		$sheet->setCellValue($lettre.$ligne, $mcs[0]->getMoyCompSem());
		$lettre++;
	}
}

function moyenneCompAnnee ($lettre, $ligne, $mca, $nbComp, $codenip, $semestre, $db, $sheet, $annee, $anneebut) {
	for ($cpt = 0; $cpt < count($nbComp); $cpt++) {
		if ($semestre >= 5 && $cpt == 2 ) {
			$cpt = 5;
		}
		$mca = $db->getMoyCompAnneeByComp($codenip, $anneebut, $annee, $cpt+1);
		$sheet->setCellValue($lettre.$ligne, $mca[0]->getMoyCompAnnee());
		$lettre++;
	}
}


function avisCompAnnee ($lettre, $ligne, $mca, $nbComp, $codenip, $semestre, $db, $sheet, $annee, $anneebut) {
	for ($cpt = 1; $cpt <= count($nbComp); $cpt++) {
		if ($semestre >= 5 && $cpt == 2 ) {
			$cpt = 5;
		}
		$mca = $db->getMoyCompAnneeByComp($codenip, $anneebut, $annee, $cpt);
		if ( !empty($mca) )
			$sheet->setCellValue($lettre.$ligne, $mca[0]->getAvis());
		$lettre++;
	}
}

function anneeMoins($annee, $decr) {
	$annee1 = intval(explode("-", $annee)[0]) - $decr;
	return $annee1 . "-" . ($annee1 + 1);
}
?>