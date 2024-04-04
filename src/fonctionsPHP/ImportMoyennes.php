<?php

session_start();
require ("DB.inc.php");

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_import_moyennes'] = "Connexion à la base de données impossible";
	header("Location: ../pages/import.php");
}
else {
	// Récupérer les données du formulaire (année et semestre)
	$annee = isset($_POST['annee']) ? $_POST['annee'] : '';

	// Vérifier si l'année ou le semestre est vide
	if(empty($annee) || !preg_match('/^\d{4}-\d{4}$/', $annee) || !isset($_FILES["file"])) {
		$_SESSION['info_import_moyennes'] = "Veuillez renseigner l'année correctement, ainsi qu'un fichier";
		header("Location: ../pages/import.php");
	}
	else {

		$db->insertIntoPromotion($annee);

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

				$val = $db->insertIntoEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
				// Utiliser les libellés pour insérer les données dans la base de données
				if ( $val === 1 ) {
					if (isset($_POST['submit'])) {
						$db->updateEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
					} else {
						$_SESSION['alerteErreur'] = "Moyennes";
						header("Location: ../pages/import.php");
						continue;
					}
				}

				$semestre = substr($fileName, 1, 2);
				$suffixe = (strpos($fileName, "FAP") !== false) ? 'A' : '';
				switch ($semestre) {
					case "1":
						insertionCompetence($data, 11, $db, $suffixe, $semestre, $annee);
						$db->insertIntoJurySem($data['code_nip'], $annee, $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN11'] == ' ' ? null : floatval($data['Bonus BIN11'])), null);
						insertionRessource($data, 101, $db, $suffixe, $annee);
						insertionSAE($data, 101, $db, $suffixe, $annee);
						break;

					case "2":
						insertionCompetence($data, 21, $db, $suffixe, $semestre, $annee);
						$db->insertIntoJurySem($data['code_nip'], $annee, $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN21'] == ' ' ? null : floatval($data['Bonus BIN21'])), null);
						insertionRessource($data, 201, $db, $suffixe, $annee);
						insertionSAE($data, 201, $db, $suffixe, $annee);
						$db->insertIntoMoyRess($data['code_nip'], $annee, "BINP201", ($data['BINP201'] == '~' ? null : floatval($data['BINP201'])));
						break;

					case "3":
						insertionCompetence($data, 31, $db, $suffixe, $semestre, $annee);
						$db->insertIntoJurySem($data['code_nip'], $annee, $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN31'] == ' ' ? null : floatval($data['Bonus BIN31'])), null);
						insertionRessource($data, 301, $db, $suffixe, $annee);
						insertionSAE($data, 301, $db, $suffixe, $annee);
						break;

					case "4":
						insertionCompetence($data, 41, $db, $suffixe, $semestre, $annee);
						$db->insertIntoJurySem($data['code_nip'], $annee, $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN41'] == ' ' ? null : floatval($data['Bonus BIN41'])), null);
						insertionRessource($data, 401, $db, $suffixe, $annee);
						$db->insertIntoMoyRess($data['code_nip'], $annee, 'BINP401', ($data['BINP401'] == '~' ? null : floatval($data['BINP401'])));
						insertionSAE($data, 401, $db, $suffixe, $annee);
						break;

					case "5":
						insertionCompetence($data, 51, $db, $suffixe, $semestre, $annee);
						$db->insertIntoJurySem($data['code_nip'], $annee, $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN51'.$suffixe] == ' ' ? null : floatval($data['Bonus BIN51'.$suffixe])), null);
						insertionRessource($data, 501, $db, $suffixe, $annee);
						insertionSAE($data, 501, $db, $suffixe, $annee);
						break;

					case "6":
						insertionCompetence($data, 61, $db, $suffixe, $semestre, $annee);
						$db->insertIntoJurySem($data['code_nip'], $annee, $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN61'.$suffixe] == ' ' ? null : floatval($data['Bonus BIN61'.$suffixe])), null);
						insertionRessource($data, 601, $db, $suffixe, $annee);
						$db->insertIntoMoyRess($data['code_nip'], $annee, 'BINP601', ($data['BINP601'.$suffixe] == '~' ? null : floatval($data['BINP601'.$suffixe])));
						insertionSAE($data, 601, $db, $suffixe, $annee);
						break;
				}
			}
			$_SESSION['info_import_moyennes'] = "Les données du fichier $fileName ont été insérées avec succès dans la base de données";
			header("Location: ../pages/import.php");
		}
		else {
			$_SESSION['info_import_moyennes'] = "Seuls les fichiers .xlsx sont autorisés";
			header("Location: ../pages/import.php");
		}
	}
}
function insertionRessource($data, $binRessourceDepart, $db, $suffixe, $annee) {
	$binRessourceArrivee = 0;
	
	// Recherche de la colonne avec le numéro le plus élevé
	foreach ($data as $key => $value) {
		// Vérifie si la clé commence par 'BINR'
		if (strpos($key, 'BINR') === 0) {
			// Récupère le numéro après 'BINR' et le compare avec $maxColumnNumber
			$matches = [];
			if (preg_match('/BINR(\d+)([A-Z]*)/', $key, $matches)) {
				$bin = intval($matches[1]);
				if ($bin > $binRessourceArrivee) {
					$binRessourceArrivee = $bin;
				}
			}
		}
	}
	
	// Utilisation de la valeur maximale trouvée dans la boucle
	for ($i = $binRessourceDepart; $i <= $binRessourceArrivee; $i++) {
		$nomBin = 'BINR' . $i;
		// Vérifie si la clé existe dans le tableau $data
		if (array_key_exists($nomBin.$suffixe, $data)) {
			$db->insertIntoMoyRess($data['code_nip'], $annee, $nomBin, ($data[$nomBin.$suffixe] == '~' ? null : floatval($data[$nomBin.$suffixe])));
		} else {
			// Si la clé n'existe pas, insère une valeur null
			$db->insertIntoMoyRess($data['code_nip'], $annee, $nomBin, null);
		}
	}
}

function insertionSAE($data, $binSAEDepart, $db, $suffixe, $annee) {
	$binSAEArrivee = 0;
	
	// Recherche de la colonne avec le numéro le plus élevé
	foreach ($data as $key => $value) {
		// Vérifie si la clé commence par 'BIN'
		if (strpos($key, 'BINS') === 0) {
			// Récupère le numéro après 'BIN' et le compare avec $maxColumnNumber
			$matches = [];
			if (preg_match('/BINS(\d+)([A-Z]*)/', $key, $matches)) {
				$bin = intval($matches[1]);
				if ($bin > $binSAEArrivee) {
					$binSAEArrivee = $bin;
				}
			}
		}
	}
	
	// Utilisation de la valeur maximale trouvée dans la boucle
	for ($i = $binSAEDepart; $i <= $binSAEArrivee; $i++) {
		$nomBin = 'BINS' . $i;
		// Vérifie si la clé existe dans le tableau $data
		if (array_key_exists($nomBin.$suffixe, $data)) {
			$db->insertIntoMoyRess($data['code_nip'], $annee, $nomBin, ($data[$nomBin.$suffixe] == '~' ? null : floatval($data[$nomBin.$suffixe])));
		} else {
			// Si la clé n'existe pas, insère une valeur null
			$db->insertIntoMoyRess($data['code_nip'], $annee, $nomBin, null);
		}
	}
}

function insertionCompetence($data, $binCompetenceDepart, $db, $suffixe, $semestre, $annee) {
	$binCompetenceArrivee = 0;
	
	// Recherche de la colonne avec le numéro le plus élevé
	foreach ($data as $key => $value) {
		// Vérifie si la clé commence par 'BIN'
		if (strpos($key, 'BIN') === 0) {
			// Récupère le numéro après 'BIN' et le compare avec $maxColumnNumber
			$matches = [];
			if (preg_match('/BIN(\d+)([A-Z]*)/', $key, $matches)) {
				$bin = intval($matches[1]);
				if ($bin > $binCompetenceArrivee) {
					$binCompetenceArrivee = $bin;
				}
			}
		}
	}
	
	// Utilisation de la valeur maximale trouvée dans la boucle
	for ($i = $binCompetenceDepart; $i <= $binCompetenceArrivee; $i++) {
		$nomBin = 'BIN' . $i;
		// Vérifie si la clé existe dans le tableau $data
		if (array_key_exists($nomBin.$suffixe, $data)) {
			$db->insertIntoMoyCompSem($data['code_nip'], $annee, $nomBin, $semestre, ($data[$nomBin.$suffixe] == '~' ? null : floatval($data[$nomBin.$suffixe])), '');
		} else {
			// Si la clé n'existe pas, insère une valeur null
			$db->insertIntoMoyCompSem($data['code_nip'], $annee, $nomBin, $semestre, null, '');
		}
	}
}
$db->close();
?>


