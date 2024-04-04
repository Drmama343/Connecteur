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

				// Vérifie si une année a été sélectionnée dans le formulaire
				if (isset($_GET['annee']) && !empty($_GET['annee'])) {
					$annee = $_GET['annee'];

					echo "<header>\n";
					echo "	<h1>Visualisation de l'annee : " . $annee . "</h1>\n";
					echo "</header>\n";

					echo "<section>\n";

					// Récupérer toutes les moyennes des ressources pour une année spécifique
					$moyennes = $db->getMoyennesRessourcesParAnnee($annee);

					// Tableau pour stocker les ressources qui ont des notes pour cette année
					$ressourcesAvecNotes = array();

					// Vérifier pour chaque ressource si au moins un étudiant a une note pour cette année
					foreach ($tRes as $r) {
						foreach ($tEtu as $e) {
							if (isset($moyennes[$e->getCode()][$r->getIdRess()])) {
								$ressourcesAvecNotes[$r->getIdRess()] = true;
								break;
							}
						}
					}

					// Afficher le tableau HTML avec les ressources qui ont des notes pour cette année
					echo "    <table id=\"etudiantsTable\">\n";
					echo "    <thead id=\"tableHeader\">\n";
					echo "        <tr>\n";
					echo "            <th>Code NIP</th>\n";
					
					foreach ($tRes as $r) {
						if (isset($ressourcesAvecNotes[$r->getIdRess()])) {
							echo "        <th>" . $r->getIdRess() . "</th>\n";
						}
					}
					
					echo "        </tr>\n";
					echo "    </thead>\n";
					echo "    <tbody id=\"tableBody\">\n";

					// Afficher les étudiants avec leurs notes pour les ressources ayant des notes pour cette année
					foreach ($tEtu as $e) {
						// Vérifier si l'étudiant a des notes pour cette année
						$notesExist = false;
						foreach ($tRes as $r) {
							if (isset($moyennes[$e->getCode()][$r->getIdRess()])) {
								$notesExist = true;
								break;
							}
						}

						// Si l'étudiant a des notes pour cette année, l'afficher dans le tableau
						if ($notesExist) {
							echo "    <tr>\n";
							echo "        <td>" . $e->getCode() . "</td>\n";
							
							foreach ($tRes as $r) {
								if (isset($ressourcesAvecNotes[$r->getIdRess()])) {
									// Récupérer la moyenne pour cet étudiant et cette ressource
									$moyenne = $moyennes[$e->getCode()][$r->getIdRess()] ?? ''; // Utilisation de ?? pour gérer les cas où aucune moyenne n'est disponible
									
									echo "        <td>$moyenne</td>\n";
								}
							}

							echo "    </tr>\n";
						}
					}

					echo "    </tbody>\n";
					echo "    </table>\n";
				}
				
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
