<?php

session_start();
require ("DB.inc.php");

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_import_moyennes'] = "Connexion à la base de données impossible";
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
				$libelles = [];

				// Parcourir les lignes du fichier Excel à partir de la deuxième ligne (pour éviter la ligne d'en-tête)
				foreach ($worksheet->getRowIterator() as $row) {
					// Ignorer la première ligne (en-tête)
					if ($row->getRowIndex() == 1) {
						foreach ($row->getCellIterator() as $cell) {
							$libelles[] = $cell->getValue();
						}
						continue; // Une fois que nous avons obtenu les libellés, nous quittons la boucle
					}

					$rowData = [];
					foreach ($row->getCellIterator() as $cell) {
						$rowData[] = $cell->getValue();
					}

					// Créer un tableau associatif avec les libellés et les données de chaque ligne
					$data = array_combine($libelles, $rowData);

					// Utiliser les libellés pour insérer les données dans la base de données
					$db->insertIntoEtudiant($data['code_nip'], $rowData[6], $data['Prénom'], $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", "", "", "", "", $data['Abs'] - $data['Just.']);

					$semestre = substr($fileName, 1, 2);
					switch ($semestre) {
						case "1":
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN11", $semestre, floatval($data['BIN11']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN12", $semestre, floatval($data['BIN12']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN13", $semestre, floatval($data['BIN13']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN14", $semestre, floatval($data['BIN14']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN15", $semestre, floatval($data['BIN15']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN16", $semestre, floatval($data['BIN16']), "");
							$db->insertIntoJurySem($data['code_nip'], $semestre, $data['Moy'], $data['UEs'], floatval($data['Bonus BIN11']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR101", floatval($data['BINR101']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR102", floatval($data['BINR102']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR103", floatval($data['BINR103']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR104", floatval($data['BINR104']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR105", floatval($data['BINR105']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR106", floatval($data['BINR106']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR107", floatval($data['BINR107']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR108", floatval($data['BINR108']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR109", floatval($data['BINR109']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR110", floatval($data['BINR110']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR111", floatval($data['BINR111']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR112", floatval($data['BINR112']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS101", floatval($data['BINS101']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS102", floatval($data['BINS102']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS103", floatval($data['BINS103']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS104", floatval($data['BINS104']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS105", floatval($data['BINS105']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS106", floatval($data['BINS106']));
							break;
						case "2":
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN21", $semestre, floatval($data['BIN21']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN22", $semestre, floatval($data['BIN22']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN23", $semestre, floatval($data['BIN23']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN24", $semestre, floatval($data['BIN24']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN25", $semestre, floatval($data['BIN25']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN26", $semestre, floatval($data['BIN26']), "");
							$db->insertIntoJurySem($data['code_nip'], $semestre, $data['Moy'], $data['UEs'], floatval($data['Bonus BIN21']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR201", floatval($data['BINR201']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR202", floatval($data['BINR202']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR203", floatval($data['BINR203']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR204", floatval($data['BINR204']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR205", floatval($data['BINR205']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR206", floatval($data['BINR206']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR207", floatval($data['BINR207']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR208", floatval($data['BINR208']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR209", floatval($data['BINR209']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR210", floatval($data['BINR210']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR211", floatval($data['BINR211']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR212", floatval($data['BINR212']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR213", floatval($data['BINR213']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR214", floatval($data['BINR214']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS201", floatval($data['BINS201']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS202", floatval($data['BINS202']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS203", floatval($data['BINS203']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS204", floatval($data['BINS204']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS205", floatval($data['BINS205']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS206", floatval($data['BINS206']));
							$db->insertIntoMoyRess($data['code_nip'], "BINP201", floatval($data['BINP201']));
							break;
						case "3":
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN31", $semestre, floatval($data['BIN31']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN32", $semestre, floatval($data['BIN32']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN33", $semestre, floatval($data['BIN33']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN34", $semestre, floatval($data['BIN34']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN35", $semestre, floatval($data['BIN35']), "");
							$db->insertIntoMoyCompSem($data['code_nip'], "BIN36", $semestre, floatval($data['BIN36']), "");
							$db->insertIntoJurySem($data['code_nip'], $semestre, $data['Moy'], $data['UEs'], floatval($data['Bonus BIN31']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR301", floatval($data['BINR301']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR302", floatval($data['BINR302']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR303", floatval($data['BINR303']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR304", floatval($data['BINR304']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR305", floatval($data['BINR305']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR306", floatval($data['BINR306']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR307", floatval($data['BINR307']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR308", floatval($data['BINR308']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR309", floatval($data['BINR309']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR310", floatval($data['BINR310']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR311", floatval($data['BINR311']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR312", floatval($data['BINR312']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR313", floatval($data['BINR313']));
							$db->insertIntoMoyRess($data['code_nip'], "BINR314", floatval($data['BINR314']));
							$db->insertIntoMoyRess($data['code_nip'], "BINS301", floatval($data['BINS301']));
							break;
						case "4":
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN41', intval(substr($fileName, 1, 2), $data['BIN41'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN42', intval(substr($fileName, 1, 2), $data['BIN42'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN43', intval(substr($fileName, 1, 2), $data['BIN43'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN44', intval(substr($fileName, 1, 2), $data['BIN44'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN45', intval(substr($fileName, 1, 2), $data['BIN45'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN46', intval(substr($fileName, 1, 2), $data['BIN46'], ""));
							$db->insertIntoJurySem($data['code_nip'], $semestre, $data['Moy'], $data['UEs'], floatval($data['Bonus BIN41']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR401', floatval($data['BINR401']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR402', floatval($data['BINR402']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR403', floatval($data['BINR403']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR404', floatval($data['BINR404']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR405', floatval($data['BINR405']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR406', floatval($data['BINR406']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR407', floatval($data['BINR407']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR408', floatval($data['BINR408']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR409', floatval($data['BINR409']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR410', floatval($data['BINR410']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR411', floatval($data['BINR411']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR412', floatval($data['BINR412']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINP401', floatval($data['BINP401']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINS401', floatval($data['BINS401']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINS411', floatval($data['BINS411']));
							break;
						case "5":
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN51', intval(substr($fileName, 1, 2), $data['BIN51'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN52', intval(substr($fileName, 1, 2), $data['BIN52'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN56', intval(substr($fileName, 1, 2), $data['BIN56'], ""));
							$db->insertIntoJurySem($data['code_nip'], $semestre, $data['Moy'], $data['UEs'], floatval($data['Bonus BIN51']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR501', floatval($data['BINR501']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR502', floatval($data['BINR502']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR503', floatval($data['BINR503']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR504', floatval($data['BINR504']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR505', floatval($data['BINR505']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR506', floatval($data['BINR506']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR507', floatval($data['BINR507']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR508', floatval($data['BINR508']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR509', floatval($data['BINR509']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR510', floatval($data['BINR510']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR511', floatval($data['BINR511']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR512', floatval($data['BINR512']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR513', floatval($data['BINR513']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR514', floatval($data['BINR514']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINS501', floatval($data['BINS501']));
							break;
						case "6":
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN61', intval(substr($fileName, 1, 2), $data['BIN61'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN62', intval(substr($fileName, 1, 2), $data['BIN62'], ""));
							$db->insertIntoMoyCompSem($data['code_nip'], 'BIN66', intval(substr($fileName, 1, 2), $data['BIN66'], ""));
							$db->insertIntoJurySem($data['code_nip'], $semestre, $data['Moy'], $data['UEs'], floatval($data['Bonus BIN61']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR601', floatval($data['BINR601']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR602', floatval($data['BINR602']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR603', floatval($data['BINR603']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR604', floatval($data['BINR604']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR605', floatval($data['BINR605']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINR606', floatval($data['BINR606']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINP601', floatval($data['BINP601']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINS601', floatval($data['BINS601']));
							$db->insertIntoMoyRess($data['code_nip'], 'BINS611', floatval($data['BINS611']));
							break;
					}
				}

				$_SESSION['info_import_moyennes'] = "Les données du fichier $fileName ont été insérées avec succès dans la base de données";
				header("Location: ../pages/import.php");
			} else {
				$_SESSION['info_import_moyennes'] = "Seuls les fichiers .xlsx sont autorisés";
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
