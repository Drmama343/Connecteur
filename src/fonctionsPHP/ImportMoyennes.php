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

				$val = $db->insertIntoEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
				// Utiliser les libellés pour insérer les données dans la base de données
				if ( $val === 1 ) {
					if (isset($_POST['submit'])) {
						$db->updateEtudiant(intval($data['code_nip']), $data['Cursus'], array_key_exists('Parcours', $data) 	? $data['Parcours'] : "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");
					} else {
						$_SESSION['alerteErreur'] = true;
						header("Location: ../pages/import.php");
						continue;
					}
				}

				$semestre = substr($fileName, 1, 2);
				
				$suffixe = (strpos($fileName, "FAP") !== false) ? 'A' : '';
				switch ($semestre) {
					case "1":
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN11", $semestre, ($data['BIN11'] == '~' ? null : floatval($data['BIN11'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN12", $semestre, ($data['BIN12'] == '~' ? null : floatval($data['BIN12'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN13", $semestre, ($data['BIN13'] == '~' ? null : floatval($data['BIN13'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN14", $semestre, ($data['BIN14'] == '~' ? null : floatval($data['BIN14'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN15", $semestre, ($data['BIN15'] == '~' ? null : floatval($data['BIN15'])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], "BIN16", $semestre, ($data['BIN16'] == '~' ? null : floatval($data['BIN16'])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN11'] == ' ' ? null : floatval($data['Bonus BIN11'])), null);
						$db->insertIntoMoyRess($data['code_nip'], "BINR101", ($data['BINR101'] == '~' ? null : floatval($data['BINR101'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR102", ($data['BINR102'] == '~' ? null : floatval($data['BINR102'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR103", ($data['BINR103'] == '~' ? null : floatval($data['BINR103'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR104", ($data['BINR104'] == '~' ? null : floatval($data['BINR104'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR105", ($data['BINR105'] == '~' ? null : floatval($data['BINR105'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR106", ($data['BINR106'] == '~' ? null : floatval($data['BINR106'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR107", ($data['BINR107'] == '~' ? null : floatval($data['BINR107'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR108", ($data['BINR108'] == '~' ? null : floatval($data['BINR108'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR109", ($data['BINR109'] == '~' ? null : floatval($data['BINR109'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR110", ($data['BINR110'] == '~' ? null : floatval($data['BINR110'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR111", ($data['BINR111'] == '~' ? null : floatval($data['BINR111'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR112", ($data['BINR112'] == '~' ? null : floatval($data['BINR112'])));
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
						$db->insertIntoMoyRess($data['code_nip'], "BINR201", ($data['BINR201'] == '~' ? null : floatval($data['BINR201'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR202", ($data['BINR202'] == '~' ? null : floatval($data['BINR202'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR203", ($data['BINR203'] == '~' ? null : floatval($data['BINR203'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR204", ($data['BINR204'] == '~' ? null : floatval($data['BINR204'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR205", ($data['BINR205'] == '~' ? null : floatval($data['BINR205'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR206", ($data['BINR206'] == '~' ? null : floatval($data['BINR206'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR207", ($data['BINR207'] == '~' ? null : floatval($data['BINR207'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR208", ($data['BINR208'] == '~' ? null : floatval($data['BINR208'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR209", ($data['BINR209'] == '~' ? null : floatval($data['BINR209'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR210", ($data['BINR210'] == '~' ? null : floatval($data['BINR210'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR211", ($data['BINR211'] == '~' ? null : floatval($data['BINR211'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR212", ($data['BINR212'] == '~' ? null : floatval($data['BINR212'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR213", ($data['BINR213'] == '~' ? null : floatval($data['BINR213'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR214", ($data['BINR214'] == '~' ? null : floatval($data['BINR214'])));
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
						$db->insertIntoMoyRess($data['code_nip'], "BINR301", ($data['BINR301'] == '~' ? null : floatval($data['BINR301'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR302", ($data['BINR302'] == '~' ? null : floatval($data['BINR302'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR303", ($data['BINR303'] == '~' ? null : floatval($data['BINR303'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR304", ($data['BINR304'] == '~' ? null : floatval($data['BINR304'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR305", ($data['BINR305'] == '~' ? null : floatval($data['BINR305'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR306", ($data['BINR306'] == '~' ? null : floatval($data['BINR306'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR307", ($data['BINR307'] == '~' ? null : floatval($data['BINR307'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR308", ($data['BINR308'] == '~' ? null : floatval($data['BINR308'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR309", ($data['BINR309'] == '~' ? null : floatval($data['BINR309'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR310", ($data['BINR310'] == '~' ? null : floatval($data['BINR310'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR311", ($data['BINR311'] == '~' ? null : floatval($data['BINR311'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR312", ($data['BINR312'] == '~' ? null : floatval($data['BINR312'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR313", ($data['BINR313'] == '~' ? null : floatval($data['BINR313'])));
						$db->insertIntoMoyRess($data['code_nip'], "BINR314", ($data['BINR314'] == '~' ? null : floatval($data['BINR314'])));
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
						insertIntoMoyRessWithMaxColumnNumber($data, 401, $db);
						$db->insertIntoMoyRess($data['code_nip'], 'BINP401', ($data['BINP401'] == '~' ? null : floatval($data['BINP401'])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS401', ($data['BINS401'] == '~' ? null : floatval($data['BINS401'])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS411', ($data['BINS411'] == '~' ? null : floatval($data['BINS411'])));
						break;

					case "5":
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN51', $semestre, ($data['BIN51'.$suffixe] == '~' ? null : floatval($data['BIN51'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN52', $semestre, ($data['BIN52'.$suffixe] == '~' ? null : floatval($data['BIN52'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN56', $semestre, ($data['BIN56'.$suffixe] == '~' ? null : floatval($data['BIN56'.$suffixe])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN51'.$suffixe] == ' ' ? null : floatval($data['Bonus BIN51'.$suffixe])), null);
						$db->insertIntoMoyRess($data['code_nip'], 'BINR501', ($data['BINR501'.$suffixe] == '~' ? null : floatval($data['BINR501'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR502', ($data['BINR502'.$suffixe] == '~' ? null : floatval($data['BINR502'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR503', ($data['BINR503'.$suffixe] == '~' ? null : floatval($data['BINR503'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR504', ($data['BINR504'.$suffixe] == '~' ? null : floatval($data['BINR504'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR505', ($data['BINR505'.$suffixe] == '~' ? null : floatval($data['BINR505'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR506', ($data['BINR506'.$suffixe] == '~' ? null : floatval($data['BINR506'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR507', ($data['BINR507'.$suffixe] == '~' ? null : floatval($data['BINR507'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR508', ($data['BINR508'.$suffixe] == '~' ? null : floatval($data['BINR508'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR509', ($data['BINR509'.$suffixe] == '~' ? null : floatval($data['BINR509'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR510', ($data['BINR510'.$suffixe] == '~' ? null : floatval($data['BINR510'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR511', ($data['BINR511'.$suffixe] == '~' ? null : floatval($data['BINR511'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR512', ($data['BINR512'.$suffixe] == '~' ? null : floatval($data['BINR512'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR513', ($data['BINR513'.$suffixe] == '~' ? null : floatval($data['BINR513'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR514', ($data['BINR514'.$suffixe] == '~' ? null : floatval($data['BINR514'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINS501', ($data['BINS501'.$suffixe] == '~' ? null : floatval($data['BINS501'.$suffixe])));
						break;

					case "6":
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN61', $semestre, ($data['BIN61'.$suffixe] == '~' ? null : floatval($data['BIN61'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN62', $semestre, ($data['BIN62'.$suffixe] == '~' ? null : floatval($data['BIN62'.$suffixe])), "");
						$db->insertIntoMoyCompSem($data['code_nip'], 'BIN66', $semestre, ($data['BIN66'.$suffixe] == '~' ? null : floatval($data['BIN66'.$suffixe])), "");
						$db->insertIntoJurySem($data['code_nip'], $semestre, floatval($data['Moy']), $data['UEs'], ($data['Bonus BIN61'.$suffixe] == ' ' ? null : floatval($data['Bonus BIN61'.$suffixe])), null);
						$db->insertIntoMoyRess($data['code_nip'], 'BINR601', ($data['BINR601'.$suffixe] == '~' ? null : floatval($data['BINR601'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR602', ($data['BINR602'.$suffixe] == '~' ? null : floatval($data['BINR602'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR603', ($data['BINR603'.$suffixe] == '~' ? null : floatval($data['BINR603'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR604', ($data['BINR604'.$suffixe] == '~' ? null : floatval($data['BINR604'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR605', ($data['BINR605'.$suffixe] == '~' ? null : floatval($data['BINR605'.$suffixe])));
						$db->insertIntoMoyRess($data['code_nip'], 'BINR606', ($data['BINR606'.$suffixe] == '~' ? null : floatval($data['BINR606'.$suffixe])));
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
function insertIntoMoyRessWithMaxColumnNumber($data, $startColumnNumber, $db) {
    $maxColumnNumber = 0;
    
    // Recherche de la colonne avec le numéro le plus élevé
    foreach ($data as $key => $value) {
        // Vérifie si la clé commence par 'BINR'
        if (strpos($key, 'BINR') === 0) {
            // Récupère le numéro après 'BINR' et le compare avec $maxColumnNumber
            $columnNumber = intval(substr($key, 4));
            if ($columnNumber > $maxColumnNumber) {
                $maxColumnNumber = $columnNumber;
            }
        }
    }
    
    // Utilisation de la valeur maximale trouvée dans la boucle
    for ($i = $startColumnNumber; $i <= $maxColumnNumber; $i++) {
        $columnName = 'BINR' . $i;
        // Vérifie si la clé existe dans le tableau $data
        if (array_key_exists($columnName, $data)) {
            $db->insertIntoMoyRess($data['code_nip'], $columnName, ($data[$columnName] == '~' ? null : floatval($data[$columnName])));
        } else {
            // Si la clé n'existe pas, insère une valeur null
            $db->insertIntoMoyRess($data['code_nip'], $columnName, null);
        }
    }
}
$db->close();
?>