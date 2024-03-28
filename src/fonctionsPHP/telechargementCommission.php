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
    $year = isset($_GET['year']) ? $_GET['year'] : '';
    $semestre = isset($_GET['semestre']) ? $_GET['semestre'] : '';

    // Vérifier si l'année ou le semestre est vide
    if (empty($year) || empty($semestre) || !preg_match('/^\d{4}-\d{4}$/', $year)) {
        var_dump($semestre);
        $_SESSION['info_commission'] = "Veuillez renseigner l'année correctement, ainsi qu'un semestre";
        header("Location: ../pages/export.php");
    } else {
        // Créer un nouveau objet Spreadsheet
		// Charger le fichier Excel modèle
		$templatePath = '../images/modele.xlsx';
		$spreadsheet = IOFactory::load($templatePath);

		// Sélectionner la feuille active
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getStyle('A1:B2')->getFont()->setSize(14)->setBold(true);

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

        // Définir le message de succès dans la session
        $_SESSION['info_commission'] = "Votre fichier Excel est exporté";

        // Redirection vers la page de destination
        header("Location: ../pages/export.php");
    }
}
?>