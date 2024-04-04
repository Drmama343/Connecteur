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


	$libelles = [];
	$rowData = [];

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

	// Ajout de la dernière valeur pour $dbtfinseq après la boucle
	$dbtfinseq[] = count($libelles);

	$db->MettreAJourRangsSemestre($semestre, $annee);

	$nbEtu = [];
	$nbEtu = $db->getJurySemByAnneeSem($annee, $semestre);

	if ($nbEtu[0] === null){
		$_SESSION['info_commission'] = "Il n'y a aucune donnée pour se semestre, veuillez insérez des fichiers pour ce semestre";
		header("Location: ../pages/export.php");
	}

	foreach($nbEtu as $etu) {
		if ( $etu !== null ) {
			$moySem = $db->getJurySemByEtudAnneeSemByCodeAnneeIdSem($etu->getCode(), $annee, $semestre);

			$ligne = $etu->getRang();
			if ($ligne === null){
				$_SESSION['info_commission'] = "Il n'y as pas de rangs de rentrés dans la base de donnée, impossible de génerer un fichier";
				header("Location: ../pages/export.php");
			}
			$ligne += 7;

			try {
				$etudiant = $db->getEtudiantsByCode($moySem[0]->getCode());
			} catch (\Throwable $th) {
				var_dump('bitr');
			}

			$bonus = $moySem[0]->getBonus() !== null ? $moySem[0]->getBonus() : 0;

			//infos etudiant
			$sheet->setCellValue('A'.$ligne, ''.$ligne-7) //rang
				->setCellValue('B'.$ligne, $etudiant[0]->getNom())
				->setCellValue('C'.$ligne, $etudiant[0]->getPrenom())
				->setCellValue('D'.$ligne, $etudiant[0]->getParcours())
				->setCellValue('E'.$ligne, $etudiant[0]->getCursus());

			//infos semestre
			$sheet->setCellValue('F'.$ligne, $moySem[0]->getUE())
				->setCellValue('G'.$ligne, $moySem[0]->getMoySem());

			//var_dump($libelles);

			//si on trouve le lib de la colonne comme une comp, on rempli la moy de la comp et le bonus sinon c'est que c'est une ressource et on cherche la moyenne de la ressource
			$ress = "";
			$moySemComp = null;
			for ($ii=0; $ii < count($libelles); $ii++) {
				if (preg_match('/^BIN\d{2}$/', $libelles[$ii])) {
					$ress = $libelles[$ii];
					$moySemComp = $db->getMoyCompSemByCodeAnneeCompSem($etudiant[0]->getCode(), $annee, $libelles[7], $semestre);
					$sheet->setCellValue($rowData[$ii].$ligne, $moySemComp[0]->getMoyCompSem());
					$sheet->setCellValue($rowData[($ii)+1].$ligne, $bonus);
					$ii++;
				}
				else {
					if ($ress !== ""){
						try {
							$sheet->setCellValue($rowData[$ii].$ligne, $db->getMoyRessByCodeAnneeIdRess($etudiant[0]->getCode(), $annee, $libelles[$ii])[0]->getMoyRess());
						} catch (\Throwable $th) {
							$_SESSION['info_commission'] = "Il manque des données, veuillez insérez des fichiers moyenne à ce semestre";
							header("Location: ../pages/export.php");
						}
						
					}
				}
			}

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


/*
$_SESSION['info_commission'] = "erreur dans l'export";
header("Location: ../pages/export.php");
*/
?>