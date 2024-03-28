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
		$templatePath = '../images/ModeleS1Commission.xlsx';
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

		// Envoyer le fichier Excel au navigateur
		$writer->save('php://output');
	}
}
?>