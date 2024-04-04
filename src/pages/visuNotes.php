<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/visualisation.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	contenu();
	echo pied();

	function contenu() {
		$db = DB::getInstance();
		if ($db == null) {
			echo "Impossible de se connecter";
		}
		else {
			try {
				$tRes = $db->getRessources();
				$tEtu = $db->getEtudiants();

				echo "<header>\n";
				echo "	<h1>Visualisation</h1>\n";
				echo "</header>\n";

				echo "<section>\n";
				echo "	<table id=\"etudiantsTable\">\n";
				echo "	<thead id=\"tableHeader\">\n";
				echo "		<tr>\n";
				echo "			<th>Code NIP</th>\n";
			
				foreach ($tRes as &$v) {
					echo "		<th>" . $v->getIdRess() . "</th>\n";
				}
			
				echo "		</tr>\n";
				echo "	</thead>\n";
				echo "	<tbody id=\"tableBody\">\n";

				// Vérifie si une année a été sélectionnée dans le formulaire
				if (isset($_GET['annee']) && !empty($_GET['annee'])) {
					$annee = $_GET['annee'];

					foreach ($tEtu as &$e) {
						echo "		<tr>\n";
						echo "			<td>" . $e->getCode() . "</td>\n";
						foreach ($tRes as &$r) {
							$tmoy = $db->getMoyRessByEtu($e->getCode(),$r->getIdRess(),$annee);
							foreach ($tmoy as &$moy) {
								echo "			<td>" . $moy->getMoyRess() . "</td>\n";
							}
						}
						echo "		</tr>\n";
					}
				}

				echo "	</tbody>\n";
				echo "	</table>\n";
				echo "</section>\n";

				echo "<footer>\n";
				echo "  <form action=\"./visuNotes.php\" method=\"GET\">\n"; // L'action est la même page actuelle
				echo "      <select name=\"annee\" id=\"yearSelect\">\n";
				echo "          <option value=\"\">Sélectionner une année</option>\n";

				// Récupérer les promotions depuis la base de données
				$promotions = $db->getPromotions();

				// Afficher les options de la liste déroulante
				foreach ($promotions as $promotion) {
					echo "          <option value=\"" . $promotion->getAnneePromo() . "\">" . $promotion->getAnneePromo() . "</option>\n";
				}

				echo "      </select>\n";
				echo "      <input type=\"submit\" value=\"Changer\">\n";
				echo "  </form>\n";
				echo "  <input type=\"text\" id=\"searchInput\" placeholder=\"Rechercher...\">\n";
				echo "</footer>\n";

				echo '<script src="../JS/ModifEtu.js"></script>' . "\n";
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}
			$db->close();
		}
	}
?>
