<?php

session_start();
require ("DB.inc.php");

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_import_jury'] = "Connexion à la base de données impossible";
	header("Location: ../pages/import.php");
}
else {
	try {
		// Vérifier si le formulaire a été soumis et le fichier téléchargé
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
			// Obtenir les détails sur le fichier téléchargé
			$fileName = basename($_FILES["file"]["name"]);
			$fileType = pathinfo($fileName, PATHINFO_EXTENSION);

			// Vérifier si le fichier est un fichier réel et avec l'extension .xlsx
			if ($fileType == "xlsx") {
				// Lire le contenu du fichier Excel
				require '../../vendor/autoload.php'; // Charge la bibliothèque PhpSpreadsheet
				$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$spreadsheet = $reader->load($_FILES["file"]["tmp_name"]);
				$worksheet = $spreadsheet->getActiveSheet();
				
				// Parcourir les lignes du fichier Excel à partir de la deuxième ligne (pour éviter la ligne d'en-tête)
				foreach ($worksheet->getRowIterator() as $row) {
					// Ignorer la première ligne (en-tête)
					if ($row->getRowIndex() == 1) {
						continue;
					}
					
					$rowData = [];
					foreach ($row->getCellIterator() as $cell) {
						$rowData[] = $cell->getValue();
					}

					//$db->insertIntoEtudiant($rowData[1], $rowData[5], $rowData[6], $rowData[10], $rowData[7], (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", $rowData[13] - $rowData[14]);
					$db ->insertIntoJurySem($rowData[1], intval(substr($fileName, 1, 2)), $rowData[37], $rowData[36], 0.35);
				}

				$_SESSION['info_import_jury'] = "Les données du fichier $fileName ont été insérées avec succès dans la base de données";
				header("Location: ../pages/import.php");
			} else {
				$_SESSION['info_import_jury'] = "Seuls les fichiers .xlsx sont autorisés";
				header("Location: ../pages/import.php");
			}
		}
	} //fin try
	catch (Exception $e) {
		echo $e->getMessage();
	}
	$db->close();
} //fin du else connexion reussie
?>
