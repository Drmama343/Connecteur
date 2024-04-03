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
} else {
	// Récupérer les données du formulaire (année et semestre)
	$annee = isset($_GET['annee']) ? $_GET['annee'] : '';
	$semestre = isset($_GET['semestre']) ? $_GET['semestre'] : '';

	// Vérifier si l'année ou le semestre est vide
	if (empty($annee) || empty($semestre) || !preg_match('/^\d{4}-\d{4}$/', $annee)) {
		$_SESSION['info_commission'] = "Veuillez renseigner l'année correctement, ainsi qu'un semestre";
		header("Location: ../pages/export.php");
		exit(); // Terminer l'exécution du script après la redirection
	}

	$templatePath = '../images/ModeleS' . $semestre . 'Commission.xlsx';
	$spreadsheet = IOFactory::load($templatePath);

	$sheet = $spreadsheet->getActiveSheet();

	$sheet->getStyle('E1:E2')->getFont()->setSize(18)->setBold(true);

	// Ajouter des données au fichier Excel
	$sheet->setCellValue('E1', "SEMESTRE " . $semestre . " - BUT INFO")
		->setCellValue('E2', $annee);

	$anneebut = 'BUT0';
	switch ($semestre) {
		case '1':
		case '2':
			$anneebut = 'BUT1';
			break;
		case '3':
		case '4':
			$anneebut = 'BUT2';
			break;
		case '5':
		case '6':
			$anneebut = 'BUT3';
			break;
		default:
			$anneebut = 'BUT0';
			break;
	}

	$libelles = [];
	$rowData = [];
	$dbtfinseq = [];

	foreach ($sheet->getRowIterator() as $row) {
		// Ignorer la première ligne (en-tête)
		if ($row->getRowIndex() == 6) {
			foreach ($row->getCellIterator() as $cell) {
				$libelles[] = $cell->getValue();
				$rowData[] = $cell->getColumn();
			}
			continue; // Une fois que nous avons obtenu les libellés, nous quittons la boucle
		}
	}

	// Maintenant, nous vérifions les libellés pour définir le début et la fin des séquences
	for ($i=0; $i < count($libelles); $i++) {
		if (preg_match('/^BIN\d{2}$/', $libelles[$i])) {
			if (count($dbtfinseq) == 0) {
				$dbtfinseq[] = $i;
			} else {
				$dbtfinseq[] = $i - 1;
				$dbtfinseq[] = $i;
			}
		}
	}

	// Ajout de la dernière valeur pour $dbtfinseq après la boucle
	$dbtfinseq[] = count($libelles);

	$nbEtu = [];
	$nbEtu = $db->getJuryAnneeByAnnees($anneebut, $annee);
	$i=0;

	foreach($nbEtu as $etu) {
		if ( $etu !== null ) {
			$moySem = $db->getJurySemByEtudSem($etu->getCode(), $semestre);
			$ligne = $etu->getRang();
			$ligne += 8 + $i;
			
			try {
				$etudiant = $db->getEtudiantsByCode($moySem[0]->getCode());
			} catch (\Throwable $th) {
				var_dump('bitr');
			}
			//var_dump($etudiant);
			

			$bonus = $moySem[0]->getBonus() !== null ? $moySem[0]->getBonus() : 0;

			//infos etudiant
			$sheet->setCellValue('A'.$ligne, ''.$ligne-8) //rang
				->setCellValue('B'.$ligne, $etudiant[0]->getNom())
				->setCellValue('C'.$ligne, $etudiant[0]->getPrenom())
				->setCellValue('D'.$ligne, $etudiant[0]->getParcours())
				->setCellValue('E'.$ligne, $etudiant[0]->getCursus());

			//infos semestre
			$sheet->setCellValue('F'.$ligne, $moySem[0]->getUE())
				->setCellValue('G'.$ligne, $moySem[0]->getMoySem());


			$ress = "";
			$moySemComp = null;
			for ($ii=0; $ii < count($libelles)-15; $ii++) {
				if (preg_match('/^BIN\d{2}$/', $libelles[$ii])) {
					$ress = $libelles[$ii];
					$moySemComp = $db->getMoyCompSem($etudiant[0]->getCode(), $libelles[7], $semestre);
					$sheet->setCellValue($rowData[$ii].$ligne, $moySemComp[0]->getMoyCompSem());
					$sheet->setCellValue($rowData[($ii)+1].$ligne, $bonus);
					$ii++;
				}
				else {
					if ($ress !== ""){
						$sheet->setCellValue($rowData[$ii].$ligne, $db->getMoyRess($etudiant[0]->getCode(), $libelles[$ii])[0]->getMoyRess());
					}
				}
			}

			/*for ($ii=0; $ii < count($dbtfinseq); $ii+2){
				$moySemComp = $db->getMoyCompSem($etudiant[0]->getCode(), $libelles[7], $semestre);
				$sheet->setCellValue($rowData[$dbtfinseq[$ii]].$ligne, $moySemComp[0]->getMoyCompSem());
				$sheet->setCellValue($rowData[($dbtfinseq[$ii]+1)].$ligne, $bonus);
			}*/

			//infos notes et bonus dans comp
			/*for ($ii=0; $ii < count($dbtfinseq); $ii+2) { 
				completerMoyCompSem($db, $sheet, $ligne, $etu, $semestre, $libelles[$dbtfinseq[$ii]], $moySem[0]->getBonus(), $libelles, $rowData, $dbtfinseq[$ii], $dbtfinseq[$ii+1]);
				continue;
			}*/
			$i++;
		}
	}

	// Récupérer les données des étudiants et les compléter dans le fichier Excel
	// Notez que la suite du code n'est pas corrigée car cela dépend de l'implémentation de vos fonctions de base de données
	// Assurez-vous que les appels de fonctions et les manipulations de données sont corrects.

	// Créer un objet Writer pour exporter le fichier Excel
	$writer = new Xlsx($spreadsheet);

	// Nom du fichier à télécharger
	$filename = 'PV Commission S' . $semestre . ' ' . $annee . '.xlsx';

	// Définir les en-têtes HTTP pour le téléchargement du fichier
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $filename . '"');
	header('Cache-Control: max-age=0');

	// Envoyer le fichier Excel au navigateur
	$writer->save('php://output');
}

function completerMoyCompSem($db, $sheet, $ligne, $etudiant, $semestre, $competence, $bonus, $libelles, $rowData, $debut, $fin) {

	if ($fin - $debut <= 2){
		$_SESSION['info_commission'] = "erreur dans le modele ou la methode";
		header("Location: ../pages/export.php");
	}

	//moy de la comp sem et le bonus
	

	//moyenne des ressources
	/*for ($i=$debut+2; $i < $fin; $i++) { 
		$sheet->setCellValue('A1', "i");
	}*/
}

/*
$_SESSION['info_commission'] = "erreur dans l'export";
header("Location: ../pages/export.php");
*/
?>