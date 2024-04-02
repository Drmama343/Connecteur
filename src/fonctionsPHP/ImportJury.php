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

					$val = $db->insertIntoEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
					// Utiliser les libellés pour insérer les données dans la base de données
					if ( $val === 1 ) {
						if (isset($_POST['submit'])) {
							$db->updateEtudiant(intval($data['code_nip']), $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
						} else {
							$_SESSION['alerteErreur'] = "Jury";
							header("Location: ../pages/import.php");
							continue;
						}
					}

					$semestre = substr($fileName, 1, 2);
					switch ($semestre) {
						case "1":
							insertJuryAnnee(intval($data['code_nip']), 'BUT1', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '1', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN11', 'BUT1', floatval($rowData[23]), $rowData[24]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN12', 'BUT1', floatval($rowData[25]), $rowData[26]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN13', 'BUT1', floatval($rowData[27]), $rowData[28]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN14', 'BUT1', floatval($rowData[29]), $rowData[30]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN15', 'BUT1', floatval($rowData[31]), $rowData[32]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN16', 'BUT1', floatval($rowData[33]), $rowData[34]);

							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN11', '1', floatval($rowData[39]), $rowData[40]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN12', '1', floatval($rowData[41]), $rowData[42]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN13', '1', floatval($rowData[43]), $rowData[44]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN14', '1', floatval($rowData[45]), $rowData[46]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN15', '1', floatval($rowData[47]), $rowData[48]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN16', '1', floatval($rowData[49]), $rowData[50]);
							break;
						case "2":
							insertJuryAnnee(intval($data['code_nip']), 'BUT1', null, $data['RCUEs'], $data['Année'],null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '2', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN21', 'BUT1', floatval($rowData[23]), $rowData[24]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN22', 'BUT1', floatval($rowData[25]), $rowData[26]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN23', 'BUT1', floatval($rowData[27]), $rowData[28]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN24', 'BUT1', floatval($rowData[29]), $rowData[30]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN25', 'BUT1', floatval($rowData[31]), $rowData[32]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN26', 'BUT1', floatval($rowData[33]), $rowData[34]);

							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN21', '2', floatval($rowData[39]), $rowData[40]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN22', '2', floatval($rowData[41]), $rowData[42]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN23', '2', floatval($rowData[43]), $rowData[44]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN24', '2', floatval($rowData[45]), $rowData[46]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN25', '2', floatval($rowData[47]), $rowData[48]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN26', '2', floatval($rowData[49]), $rowData[50]);
							break;
						case "3":
							//insert ou update
							insertJuryAnnee(intval($data['code_nip']), 'BUT2', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '3', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN31', 'BUT2', floatval($rowData[23]), $rowData[24]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN32', 'BUT2', floatval($rowData[25]), $rowData[26]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN33', 'BUT2', floatval($rowData[27]), $rowData[28]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN34', 'BUT2', floatval($rowData[29]), $rowData[30]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN35', 'BUT2', floatval($rowData[31]), $rowData[32]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN36', 'BUT2', floatval($rowData[33]), $rowData[34]);

							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN31', '3', floatval($rowData[39]), $rowData[40]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN32', '3', floatval($rowData[41]), $rowData[42]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN33', '3', floatval($rowData[43]), $rowData[44]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN34', '3', floatval($rowData[45]), $rowData[46]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN35', '3', floatval($rowData[47]), $rowData[48]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN36', '3', floatval($rowData[49]), $rowData[50]);
							break;
						case "4":
							//insert ou update
							insertJuryAnnee(intval($data['code_nip']), 'BUT2', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '4', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN41', 'BUT2', floatval($rowData[23]), $rowData[24]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN42', 'BUT2', floatval($rowData[25]), $rowData[26]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN43', 'BUT2', floatval($rowData[27]), $rowData[28]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN44', 'BUT2', floatval($rowData[29]), $rowData[30]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN45', 'BUT2', floatval($rowData[31]), $rowData[32]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN46', 'BUT2', floatval($rowData[33]), $rowData[34]);

							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN41', '4', floatval($rowData[39]), $rowData[40]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN42', '4', floatval($rowData[41]), $rowData[42]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN43', '4', floatval($rowData[43]), $rowData[44]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN44', '4', floatval($rowData[45]), $rowData[46]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN45', '4', floatval($rowData[47]), $rowData[48]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN46', '4', floatval($rowData[49]), $rowData[50]);
							break;
						case "5":
							//insert ou update
							insertJuryAnnee(intval($data['code_nip']), 'BUT3', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));
							
							$db->insertIntoJurySem(intval($data['code_nip']), '5', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN51', 'BUT3', floatval($rowData[23]), $rowData[24]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN52', 'BUT3', floatval($rowData[25]), $rowData[26]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN56', 'BUT3', floatval($rowData[33]), $rowData[34]);

							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN51', '5', floatval($rowData[39]), $rowData[40]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN52', '5', floatval($rowData[41]), $rowData[42]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN56', '5', floatval($rowData[49]), $rowData[50]);
							break;
						case "6":
							insertJuryAnnee(intval($data['code_nip']), 'BUT3', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '6', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN61', 'BUT3', floatval($rowData[23]), $rowData[24]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN62', 'BUT3', floatval($rowData[25]), $rowData[26]);
							insertMoyCompAnnee(intval($data['code_nip']), 'BIN66', 'BUT3', floatval($rowData[33]), $rowData[34]);

							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN61', '6', floatval($rowData[39]), $rowData[40]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN62', '6', floatval($rowData[41]), $rowData[42]);
							$db->insertIntoMoyCompSem(intval($data['code_nip']), 'BIN66', '6', floatval($rowData[49]), $rowData[50]);
							break;
					}
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

function insertJuryAnnee($codenip, $nomannee, $moyannee, $rcue, $decision, $rang, $anneepromo, $abs) {
	if($db->insertIntoJuryAnnee($codenip, $nomannee, $moyannee, $rcue, $decision, $rang, $anneepromo, $abs) === 1)
	{
		$db->updateIntoJuryAnnee($codenip, $nomannee, $moyannee, $rcue, $decision, $rang, $anneepromo, $abs);
	}
}

function insertMoyCompAnnee($codenip, $idcomp, $nomannee, $moycompannee, $avis) {
	if($db->insertIntoMoyCompAnnee($codenip, $idcomp, $nomannee, $moycompannee, $avis) === 1)
	{
		$db->updateIntoMoyCompAnnee($codenip, $idcomp, $nomannee, $moycompannee, $avis);
	}
}

?>
