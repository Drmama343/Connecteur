<?php

	require ("DB.inc.php");

	// Vérifier si des données ont été soumises
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Vérifier si les données nécessaires sont présentes
		if (isset($_POST["codeNip"])) {
			// Récupérer les données
			$codeNip = $_POST["codeNip"];
			$nom = isset($_POST["nom"]) ? $_POST["nom"] : "";
			$prenom = isset($_POST["prenom"]) ? $_POST["prenom"] : "";
			$cursus = isset($_POST["cursus"]) ? $_POST["cursus"] : "";
			$parcours = isset($_POST["parcours"]) ? $_POST["parcours"] : "";
			$apprenti = isset($_POST["apprenti"]) ? $_POST["apprenti"] : "";
			$avisI = isset($_POST["avisI"]) ? $_POST["avisI"] : "";
			$avisM = isset($_POST["avisM"]) ? $_POST["avisM"] : "";
			$commentaire = isset($_POST["commentaire"]) ? $_POST["commentaire"] : "";
			$mobilite = isset($_POST["mobilite"]) ? $_POST["mobilite"] : "";

			$db = DB::getInstance();
			if ($db == null) {
				echo "Impossible de se connecter";
			}
			else {
				try {
					var_dump($codeNip[0], $nom[0], $prenom[0], $cursus[0], $parcours[0], $apprenti[0], $avisI[0], $avisM[0], $commentaire[0], $mobilite[0]);
					$t = $db->updateEtudiant($codeNip[0], $nom[0], $prenom[0], $cursus[0], $parcours[0], $apprenti[0], $avisI[0], $avisM[0], $commentaire[0], $mobilite[0]);
				}
				catch (Exception $e) {
					echo $e->getMessage();
				}  
				$db->close();
			}
			header("Location: ../pages/visualisation.php");
			exit;
		} else {
			header("Location: ../pages/visualisation.php");
		}
	} else {
		header("Location: ../pages/visualisation.php");
		exit;
	}
?>