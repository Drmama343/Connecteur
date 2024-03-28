<?php

session_start();
require ("DB.inc.php");
require ("../../vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_jury'] = "Connexion à la base de données impossible";
	header("Location: ../pages/export.php");
}
else {
	// Récupérer les données du formulaire (année et semestre)
	$year = isset($_GET['year']) ? $_GET['year'] : '';
	$semestre = isset($_GET['semestre']) ? $_GET['semestre'] : '';

	// Vérifier si l'année ou le semestre est vide
	if(empty($year) || empty($semestre) || !preg_match('/^\d{4}-\d{4}$/', $year)) {
		$_SESSION['info_jury'] = "Veuillez renseigner l'année correctement, ainsi qu'un semestre";
		header("Location: ../pages/export.php");
	}
	else {
		// Créer un nouveau objet Spreadsheet
		$spreadsheet = new Spreadsheet();

		// Sélectionner la feuille active
		$sheet = $spreadsheet->getActiveSheet();

		// Ajouter des données au fichier Excel
		$sheet->setCellValue('A1', 'Année')
			->setCellValue('B1', 'Semestre')
			->setCellValue('A2', $year)
			->setCellValue('B2', $semestre);

		// Créer un objet Writer pour exporter le fichier Excel
		$writer = new Xlsx($spreadsheet);

		// Nom du fichier à télécharger
		$filename = 'rapport_' . $year . '_' . $semestre . '.xlsx';

		// Définir les en-têtes HTTP pour le téléchargement du fichier
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		// Envoyer le fichier Excel au navigateur
		$writer->save('php://output');

		$_SESSION['info_jury'] = "Votre fichier Excel est exporté";
		header("Location: ../pages/export.php");
	}
}
?>