<?php

session_start();
require ("DB.inc.php");

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_import_jury'] = "Connexion à la base de données impossible";
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

			$db->insertIntoPromotion($annee);

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
					// Créer un tableau associatif avec les libellés et les données de chaque ligne
					$data = array_combine($libelles, $rowData);

					$db->insertIntoEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], "", "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");

					$semestre = substr($fileName, 1, 2);
					switch ($semestre) {
						case "1":
							insertJuryAnnee($db, intval($data['code_nip']), 'BUT1', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '1', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee($db, intval($data['code_nip']), 1, 'BUT1', floatval($rowData[23]), $rowData[24], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 2, 'BUT1', floatval($rowData[25]), $rowData[26], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 3, 'BUT1', floatval($rowData[27]), $rowData[28], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 4, 'BUT1', floatval($rowData[29]), $rowData[30], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 5, 'BUT1', floatval($rowData[31]), $rowData[32], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 6, 'BUT1', floatval($rowData[33]), $rowData[34], 0);

							insertMoyCompSem($db, intval($data['code_nip']), 'BIN11', '1', floatval($rowData[39]), $rowData[40]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN12', '1', floatval($rowData[41]), $rowData[42]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN13', '1', floatval($rowData[43]), $rowData[44]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN14', '1', floatval($rowData[45]), $rowData[46]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN15', '1', floatval($rowData[47]), $rowData[48]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN16', '1', floatval($rowData[49]), $rowData[50]);
							break;
						case "2":
							insertJuryAnnee($db, intval($data['code_nip']), "2022-2023", 'BUT1', null, $data['RCUEs'], $data['Année'],null, intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '2', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee($db, intval($data['code_nip']), 1, 'BUT1', floatval($rowData[23]), $rowData[24], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 2, 'BUT1', floatval($rowData[25]), $rowData[26], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 3, 'BUT1', floatval($rowData[27]), $rowData[28], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 4, 'BUT1', floatval($rowData[29]), $rowData[30], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 5, 'BUT1', floatval($rowData[31]), $rowData[32], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 6, 'BUT1', floatval($rowData[33]), $rowData[34], 0);

							insertMoyCompSem($db, intval($data['code_nip']), 'BIN21', '2', floatval($rowData[39]), $rowData[40]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN22', '2', floatval($rowData[41]), $rowData[42]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN23', '2', floatval($rowData[43]), $rowData[44]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN24', '2', floatval($rowData[45]), $rowData[46]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN25', '2', floatval($rowData[47]), $rowData[48]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN26', '2', floatval($rowData[49]), $rowData[50]);
							break;
						case "3":
							//insert ou update
							insertJuryAnnee($db, intval($data['code_nip']), 'BUT2', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '3', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee($db, intval($data['code_nip']), 1, 'BUT2', floatval($rowData[23]), $rowData[24], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 2, 'BUT2', floatval($rowData[25]), $rowData[26], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 3, 'BUT2', floatval($rowData[27]), $rowData[28], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 4, 'BUT2', floatval($rowData[29]), $rowData[30], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 5, 'BUT2', floatval($rowData[31]), $rowData[32], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 6, 'BUT2', floatval($rowData[33]), $rowData[34], 0);

							insertMoyCompSem($db, intval($data['code_nip']), 'BIN31', '3', floatval($rowData[39]), $rowData[40]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN32', '3', floatval($rowData[41]), $rowData[42]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN33', '3', floatval($rowData[43]), $rowData[44]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN34', '3', floatval($rowData[45]), $rowData[46]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN35', '3', floatval($rowData[47]), $rowData[48]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN36', '3', floatval($rowData[49]), $rowData[50]);
							break;
						case "4":
							//insert ou update
							insertJuryAnnee($db, intval($data['code_nip']), 'BUT2', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '4', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee($db, intval($data['code_nip']), 1, 'BUT2', floatval($rowData[23]), $rowData[24], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 2, 'BUT2', floatval($rowData[25]), $rowData[26], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 3, 'BUT2', floatval($rowData[27]), $rowData[28], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 4, 'BUT2', floatval($rowData[29]), $rowData[30], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 5, 'BUT2', floatval($rowData[31]), $rowData[32], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 6, 'BUT2', floatval($rowData[33]), $rowData[34], 0);

							insertMoyCompSem($db, intval($data['code_nip']), 'BIN41', '4', floatval($rowData[39]), $rowData[40]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN42', '4', floatval($rowData[41]), $rowData[42]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN43', '4', floatval($rowData[43]), $rowData[44]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN44', '4', floatval($rowData[45]), $rowData[46]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN45', '4', floatval($rowData[47]), $rowData[48]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN46', '4', floatval($rowData[49]), $rowData[50]);
							break;
						case "5":
							//insert ou update
							insertJuryAnnee($db, intval($data['code_nip']), 'BUT3', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));
							
							$db->insertIntoJurySem(intval($data['code_nip']), '5', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee($db, intval($data['code_nip']), 1, 'BUT3', floatval($rowData[23]), $rowData[24], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 2, 'BUT3', floatval($rowData[25]), $rowData[26], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 6, 'BUT3', floatval($rowData[33]), $rowData[34], 0);

							insertMoyCompSem($db, intval($data['code_nip']), 'BIN51', '5', floatval($rowData[39]), $rowData[40]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN52', '5', floatval($rowData[41]), $rowData[42]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN56', '5', floatval($rowData[49]), $rowData[50]);
							break;
						case "6":
							insertJuryAnnee($db, intval($data['code_nip']), 'BUT3', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

							$db->insertIntoJurySem(intval($data['code_nip']), '6', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

							//insert ou update
							insertMoyCompAnnee($db, intval($data['code_nip']), 1, 'BUT3', floatval($rowData[23]), $rowData[24], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 2, 'BUT3', floatval($rowData[25]), $rowData[26], 0);
							insertMoyCompAnnee($db, intval($data['code_nip']), 6, 'BUT3', floatval($rowData[33]), $rowData[34], 0);

							insertMoyCompSem($db, intval($data['code_nip']), 'BIN61', '6', floatval($rowData[39]), $rowData[40]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN62', '6', floatval($rowData[41]), $rowData[42]);
							insertMoyCompSem($db, intval($data['code_nip']), 'BIN66', '6', floatval($rowData[49]), $rowData[50]);
							break;
					}
				}

				// Créer un tableau associatif avec les libellés et les données de chaque ligne
				$data = array_combine($libelles, $rowData);

				$db->insertIntoEtudiant(intval($data['code_nip']), $rowData[5], $data['Prénom'], "", "", (strpos($fileName, "FAP") ? substr($fileName, 0, 2) : ""), "", "", "", "");

				$semestre = substr($fileName, 1, 2);
				switch ($semestre) {
					case "1":
						insertJuryAnnee($db, intval($data['code_nip']), $annee, 'BUT1', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

						$db->insertIntoJurySem(intval($data['code_nip']), $annee, '1', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

						//insert ou update
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 1, 'BUT1', floatval($rowData[23]), $rowData[24], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 2, 'BUT1', floatval($rowData[25]), $rowData[26], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 3, 'BUT1', floatval($rowData[27]), $rowData[28], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 4, 'BUT1', floatval($rowData[29]), $rowData[30], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 5, 'BUT1', floatval($rowData[31]), $rowData[32], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 6, 'BUT1', floatval($rowData[33]), $rowData[34], 0);

						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN11', '1', floatval($rowData[39]), $rowData[40]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN12', '1', floatval($rowData[41]), $rowData[42]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN13', '1', floatval($rowData[43]), $rowData[44]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN14', '1', floatval($rowData[45]), $rowData[46]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN15', '1', floatval($rowData[47]), $rowData[48]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN16', '1', floatval($rowData[49]), $rowData[50]);
						break;
					case "2":
						insertJuryAnnee($db, intval($data['code_nip']), $annee, 'BUT1', null, $data['RCUEs'], $data['Année'],null, "2022-2023", intval($data['Abs'] - $data['Just.']));

						$db->insertIntoJurySem(intval($data['code_nip']), $annee, '2', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

						//insert ou update
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 1, 'BUT1', floatval($rowData[23]), $rowData[24], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 2, 'BUT1', floatval($rowData[25]), $rowData[26], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 3, 'BUT1', floatval($rowData[27]), $rowData[28], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 4, 'BUT1', floatval($rowData[29]), $rowData[30], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 5, 'BUT1', floatval($rowData[31]), $rowData[32], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 6, 'BUT1', floatval($rowData[33]), $rowData[34], 0);

						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN21', '2', floatval($rowData[39]), $rowData[40]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN22', '2', floatval($rowData[41]), $rowData[42]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN23', '2', floatval($rowData[43]), $rowData[44]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN24', '2', floatval($rowData[45]), $rowData[46]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN25', '2', floatval($rowData[47]), $rowData[48]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN26', '2', floatval($rowData[49]), $rowData[50]);
						break;
					case "3":
						//insert ou update
						insertJuryAnnee($db, intval($data['code_nip']), $annee, 'BUT2', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

						$db->insertIntoJurySem(intval($data['code_nip']), $annee, '3', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

						//insert ou update
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 1, 'BUT2', floatval($rowData[23]), $rowData[24], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 2, 'BUT2', floatval($rowData[25]), $rowData[26], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 3, 'BUT2', floatval($rowData[27]), $rowData[28], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 4, 'BUT2', floatval($rowData[29]), $rowData[30], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 5, 'BUT2', floatval($rowData[31]), $rowData[32], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 6, 'BUT2', floatval($rowData[33]), $rowData[34], 0);

						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN31', '3', floatval($rowData[39]), $rowData[40]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN32', '3', floatval($rowData[41]), $rowData[42]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN33', '3', floatval($rowData[43]), $rowData[44]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN34', '3', floatval($rowData[45]), $rowData[46]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN35', '3', floatval($rowData[47]), $rowData[48]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN36', '3', floatval($rowData[49]), $rowData[50]);
						break;
					case "4":
						//insert ou update
						insertJuryAnnee($db, intval($data['code_nip']), $annee, 'BUT2', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

						$db->insertIntoJurySem(intval($data['code_nip']), $annee, '4', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

						//insert ou update
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 1, 'BUT2', floatval($rowData[23]), $rowData[24], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 2, 'BUT2', floatval($rowData[25]), $rowData[26], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 3, 'BUT2', floatval($rowData[27]), $rowData[28], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 4, 'BUT2', floatval($rowData[29]), $rowData[30], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 5, 'BUT2', floatval($rowData[31]), $rowData[32], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 6, 'BUT2', floatval($rowData[33]), $rowData[34], 0);

						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN41', '4', floatval($rowData[39]), $rowData[40]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN42', '4', floatval($rowData[41]), $rowData[42]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN43', '4', floatval($rowData[43]), $rowData[44]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN44', '4', floatval($rowData[45]), $rowData[46]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN45', '4', floatval($rowData[47]), $rowData[48]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN46', '4', floatval($rowData[49]), $rowData[50]);
						break;
					case "5":
						//insert ou update
						insertJuryAnnee($db, intval($data['code_nip']), $annee, 'BUT3', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));
						
						$db->insertIntoJurySem(intval($data['code_nip']), $annee, '5', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

						//insert ou update
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 1, 'BUT3', floatval($rowData[23]), $rowData[24], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 2, 'BUT3', floatval($rowData[25]), $rowData[26], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 6, 'BUT3', floatval($rowData[33]), $rowData[34], 0);

						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN51', '5', floatval($rowData[39]), $rowData[40]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN52', '5', floatval($rowData[41]), $rowData[42]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN56', '5', floatval($rowData[49]), $rowData[50]);
						break;
					case "6":
						insertJuryAnnee($db, intval($data['code_nip']), $annee, 'BUT3', null, $data['RCUEs'], $data['Année'], null, "2022-2023", intval($data['Abs'] - $data['Just.']));

						$db->insertIntoJurySem(intval($data['code_nip']), $annee, '6', floatval($data['Moy']), $data['UEs'], null, intval($data['Rg']));

						//insert ou update
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 1, 'BUT3', floatval($rowData[23]), $rowData[24], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 2, 'BUT3', floatval($rowData[25]), $rowData[26], 0);
						insertMoyCompAnnee($db, intval($data['code_nip']), $annee, 6, 'BUT3', floatval($rowData[33]), $rowData[34], 0);

						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN61', '6', floatval($rowData[39]), $rowData[40]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN62', '6', floatval($rowData[41]), $rowData[42]);
						insertMoyCompSem($db, intval($data['code_nip']), $annee, 'BIN66', '6', floatval($rowData[49]), $rowData[50]);
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
} //fin du else connexion reussie

function insertJuryAnnee($db, $codenip, $anneepromo, $nomannee, $moyannee, $rcue, $decision, $rang, $abs) {
	if($db->insertIntoJuryAnnee($codenip, $anneepromo, $nomannee, $moyannee, $rcue, $decision, $rang, $abs) === 1)
	{
		$db->updateJuryAnnee($codenip, $anneepromo, $nomannee, $moyannee, $rcue, $decision, $rang, $abs);
	}
}

function insertMoyCompAnnee($db, $codenip, $annee, $idcomp, $nomannee, $moycompannee, $avis, $rang) {
	if($db->insertIntoMoyCompAnnee($codenip, $annee, $idcomp, $nomannee, $moycompannee, $avis, $rang) === 1)
	{
		$db->updateMoyCompAnnee($codenip, $annee, $idcomp, $nomannee, $moycompannee, $avis, $rang);
	}
}

function insertMoyCompSem($db, $codenip, $annee, $idcomp, $idsem, $moycompsem, $avis) {
	if($db->insertIntoMoyCompSem($codenip, $annee, $idcomp, $idsem, $moycompsem, $avis) === 1)
	{
		$db->updateMoyCompSem($codenip, $annee, $idcomp, $idsem, $moycompsem, $avis);
	}
}
$db->close();
?>
