<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/virtualisation.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	if ( $_SESSION['droitAcces'] === 2 )
		contenuAdmin();
	else
		contenuUser();
	echo pied();

	function contenuUser() {
		$db = DB::getInstance();
		if ($db == null) {
			echo "Impossible de se connecter";
		}
		else {
			try {
				$t = $db->getEtudiants();
			} //fin try
			catch (Exception $e) {
				echo $e->getMessage();
			}  
			$db->close();
		} //fin du else connexion reussie

		echo "<header>";
		echo "<h1>Visualisation</h1>";
		echo "</header>";

		echo "		<section>
			<table>
				<thead>
					<tr>
						<th>Code NIP</th>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Cursus</th>
						<th>Parcours</th>
						<th>Apprentissage</th>
						<th>Avis Ingénieur</th>
						<th>Avis Master</th>
						<th>Commentaire</th>
						<th>Mobilité étrangère</th>
					</tr>
				</thead>
				<tbody>\n";

		foreach ($t as &$v) {

			echo "<td>" . $v->getCode() . "</td>\n";
			echo "<td>" . $v->getNom() . "</td>\n";
			echo "<td>" . $v->getPrenom() . "</td>\n";
			echo "<td>" . $v->getCursus() . "</td>\n";
			echo "<td>" . $v->getParcours() . "</td>\n";
			echo "<td>" . $v->getApprentissage() . "</td>\n";
			echo "<td>" . $v->getAvisInge() . "</td>\n";
			echo "<td>" . $v->getAvisMaster() . "</td>\n";
			echo "<td>" . $v->getCommentaire() . "</td>\n";
			echo "<td>" . $v->getMobEtrang() . "</td>\n";

			echo "</tr>\n";
		}

		echo "
					</tbody>
				</table>
			</section>\n";
	}

	function contenuAdmin () {
		$db = DB::getInstance();
		if ($db == null) {
			echo "Impossible de se connecter";
		}
		else {
			try {
				$t = $db->getEtudiants();
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}  
			$db->close();
		}
	
		echo "<header>";
		echo "<h1>Visualisation</h1>";
		echo "</header>";
	
		echo "<form method='post' action='valider_modifications.php'>"; // Formulaire pour soumettre les modifications
		echo "<section>";
		echo "<table>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Code NIP</th>";
		echo "<th>Nom</th>";
		echo "<th>Prénom</th>";
		echo "<th>Cursus</th>";
		echo "<th>Parcours</th>";
		echo "<th>Apprentissage</th>";
		echo "<th>Avis Ingénieur</th>";
		echo "<th>Avis Master</th>";
		echo "<th>Commentaire</th>";
		echo "<th>Mobilité étrangère</th>";
		echo "<th>Action</th>"; // Ajout d'une colonne pour les actions
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>\n";
	
		foreach ($t as &$v) {
			echo "<tr>";
			echo "<td>" . $v->getCode() . "</td>";
			echo "<td><input type='text' name='nom[]' value='" . $v->getNom() . "'></td>";
			echo "<td><input type='text' name='prenom[]' value='" . $v->getPrenom() . "'></td>";
			echo "<td>" . $v->getCursus() . "</td>";
			echo "<td>" . $v->getParcours() . "</td>";
			echo "<td>" . $v->getApprentissage() . "</td>";
			echo "<td>" . $v->getAvisInge() . "</td>";
			echo "<td>" . $v->getAvisMaster() . "</td>";
			echo "<td><input type='text' name='commentaire[]' value='" . $v->getCommentaire() . "'></td>";
			echo "<td>" . $v->getMobEtrang() . "</td>";
			echo "<td><input type='checkbox' name='modifier[]' value='" . $v->getCode() . "'></td>"; // Ajout d'une case à cocher pour sélectionner la ligne à modifier
			echo "</tr>";
		}
	
		echo "</tbody>";
		echo "</table>";
		echo "</section>";
		echo "<footer>";
		echo "<input type='submit' name='valider' value='Valider'>"; // Bouton pour valider les modifications
		echo "</footer>";
		echo "</form>";
	}
?>
