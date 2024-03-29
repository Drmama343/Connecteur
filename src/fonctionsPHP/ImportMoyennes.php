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
				if ( $db->insertIntoEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "") === 1 ) {
					if (isset($_POST['submit'])) {
						$db->updateEtudiant(intval($data['code_nip']), $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
					} else {
						$_SESSION['alerteErreur'] = true;
						header("Location: ../pages/import.php");
						break;
					}
				}

				$semestre = substr($fileName, 1, 2);
				$suffixe = (strpos($fileName, "FAP") !== false) ? 'A' : '';
				switch ($semestre) {
					case "1":
						insertionCompetence($data, 11, $db, $suffixe, $semestre);
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN11'] == ' ' ? null : floatval($data['Bonus BIN11'])), null);
						insertionRessource($data, 101, $db, $suffixe);
						$db->insertIntoMoyRess($data['code_nip'], "BINS101", ($data['BINS101'] == '~' ? null : floatval($data['BINS101'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS102", ($data['BINS102'] == '~' ? null : floatval($data['BINS102'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS103", ($data['BINS103'] == '~' ? null : floatval($data['BINS103'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS104", ($data['BINS104'] == '~' ? null : floatval($data['BINS104'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS105", ($data['BINS105'] == '~' ? null : floatval($data['BINS105'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS106", ($data['BINS106'] == '~' ? null : floatval($data['BINS106'])));
						break;

					case "2":
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN21", $semestre, ($data['BIN21'] == '~' ? null : floatval($data['BIN21'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN22", $semestre, ($data['BIN22'] == '~' ? null : floatval($data['BIN22'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN23", $semestre, ($data['BIN23'] == '~' ? null : floatval($data['BIN23'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN24", $semestre, ($data['BIN24'] == '~' ? null : floatval($data['BIN24'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN25", $semestre, ($data['BIN25'] == '~' ? null : floatval($data['BIN25'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN26", $semestre, ($data['BIN26'] == '~' ? null : floatval($data['BIN26'])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN21'] == ' ' ? null : floatval($data['Bonus BIN21'])), null);
						insertionRessource($data, 201, $db, $suffixe);
						$db->insertIntoMoyRess($data['code_nip'], "BINS201", ($data['BINS201'] == '~' ? null : floatval($data['BINS201'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS202", ($data['BINS202'] == '~' ? null : floatval($data['BINS202'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS203", ($data['BINS203'] == '~' ? null : floatval($data['BINS203'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS204", ($data['BINS204'] == '~' ? null : floatval($data['BINS204'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS205", ($data['BINS205'] == '~' ? null : floatval($data['BINS205'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINS206", ($data['BINS206'] == '~' ? null : floatval($data['BINS206'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINP201", ($data['BINP201'] == '~' ? null : floatval($data['BINP201'])));
						break;

					case "3":
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN31", $semestre, ($data['BIN31'] == '~' ? null : floatval($data['BIN31'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN32", $semestre, ($data['BIN32'] == '~' ? null : floatval($data['BIN32'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN33", $semestre, ($data['BIN33'] == '~' ? null : floatval($data['BIN33'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN34", $semestre, ($data['BIN34'] == '~' ? null : floatval($data['BIN34'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN35", $semestre, ($data['BIN35'] == '~' ? null : floatval($data['BIN35'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN36", $semestre, ($data['BIN36'] == '~' ? null : floatval($data['BIN36'])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN31'] == ' ' ? null : floatval($data['Bonus BIN31'])), null);
						insertionRessource($data, 301, $db, $suffixe);
						$db->insertIntoMoyRess($data['code_nip'], "BINS301", ($data['BINS301'] == '~' ? null : floatval($data['BINS301'])));
						break;

					case "4":
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN41', $semestre, ($data['BIN41'] == '~' ? null : floatval($data['BIN41'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN42', $semestre, ($data['BIN42'] == '~' ? null : floatval($data['BIN42'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN43', $semestre, ($data['BIN43'] == '~' ? null : floatval($data['BIN43'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN44', $semestre, ($data['BIN44'] == '~' ? null : floatval($data['BIN44'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN45', $semestre, ($data['BIN45'] == '~' ? null : floatval($data['BIN45'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN46', $semestre, ($data['BIN46'] == '~' ? null : floatval($data['BIN46'])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN41'] == ' ' ? null : floatval($data['Bonus BIN41'])), null);
						insertionRessource($data, 401, $db, $suffixe);
						$db->insertIntoMoyRess($data['code_nip'], 'BINP401', ($data['BINP401'] == '~' ? null : floatval($data['BINP401'])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS401', ($data['BINS401'] == '~' ? null : floatval($data['BINS401'])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS411', ($data['BINS411'] == '~' ? null : floatval($data['BINS411'])));
						break;

					case "5":
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN51', $semestre, ($data['BIN51'.$suffixe] == '~' ? null : floatval($data['BIN51'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN52', $semestre, ($data['BIN52'.$suffixe] == '~' ? null : floatval($data['BIN52'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN56', $semestre, ($data['BIN56'.$suffixe] == '~' ? null : floatval($data['BIN56'.$suffixe])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN51'.$suffixe] == ' ' ? null : floatval($data['Bonus BIN51'.$suffixe])), null);
						insertionRessource($data, 501, $db, $suffixe);
						$db->insertIntoMoyRess($data['code_nip'], 'BINS501', ($data['BINS501'.$suffixe] == '~' ? null : floatval($data['BINS501'.$suffixe])));
						break;

					case "6":
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN61', $semestre, ($data['BIN61'.$suffixe] == '~' ? null : floatval($data['BIN61'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN62', $semestre, ($data['BIN62'.$suffixe] == '~' ? null : floatval($data['BIN62'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN66', $semestre, ($data['BIN66'.$suffixe] == '~' ? null : floatval($data['BIN66'.$suffixe])), "");
						insertionRessource($data, 601, $db, $suffixe);
						$db->insertIntoMoyRess($data['code_nip'], 'BINP601', ($data['BINP601'.$suffixe] == '~' ? null : floatval($data['BINP601'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS601', ($data['BINS601'.$suffixe] == '~' ? null : floatval($data['BINS601'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS611', ($data['BINS611'.$suffixe] == '~' ? null : floatval($data['BINS611'.$suffixe])));
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
function insertionRessource($data, $binRessourceDepart, $db, $suffixe) {
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
        if (array_key_exists($nomBin, $data)) {
            $db->insertIntoMoyRess($data['code_nip'], $nomBin, ($data[$nomBin.$suffixe] == '~' ? null : floatval($data[$nomBin.$suffixe])));
        } else {
            // Si la clé n'existe pas, insère une valeur null
            $db->insertIntoMoyRess($data['code_nip'], $nomBin, null);
        }
    }
}

function insertionCompetence($data, $binCompetenceDepart, $db, $suffixe, $semestre) {
    $binCompetenceArrivee = 0;
    
    // Recherche de la colonne avec le numéro le plus élevé
    foreach ($data as $key => $value) {
        // Vérifie si la clé commence par 'BINR'
        if (strpos($key, 'BIN') === 0) {
            // Récupère le numéro après 'BINR' et le compare avec $maxColumnNumber
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
        if (array_key_exists($nomBin, $data)) {
            $db->insertIntoMoyCompSem($data['code_nip'], "BIN11", $semestre, ($data[$nomBin.$suffixe] == '~' ? null : floatval($data[$nomBin.$suffixe])), "");
        }
    }
}
$db->close();
?>
