<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/visualisation.css"]);
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
		echo "<h1>Visualisation des etudiants</h1>";
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
	
		echo "<header>\n";
		echo "	<h1>Visualisation</h1>\n";
		echo "</header>\n";

		echo "<section>\n";
		echo "	<table id=\"etudiantsTable\">\n";
		echo "	<thead id=\"tableHeader\">\n";
		echo "		<tr>\n";
		echo "			<th>Code NIP</th>\n";
		echo "			<th>Nom</th>\n";
		echo "			<th>Prénom</th>\n";
		echo "			<th>Cursus</th>\n";
		echo "			<th>Parcours</th>\n";
		echo "			<th>Apprentissage</th>\n";
		echo "			<th>Avis Ingénieur</th>\n";
		echo "			<th>Avis Master</th>\n";
		echo "			<th>Commentaire</th>\n";
		echo "			<th>Mobilité étrangère</th>\n";
		echo "		</tr>\n";
		echo "	</thead>\n";
		echo "	<tbody id=\"tableBody\">\n";

		foreach ($t as &$v) {
			echo "		<tr>\n";
			echo "			<td>" . $v->getCode() . "</td>\n";
			echo "			<td>" . $v->getNom() . "</td>\n";
			echo "			<td>" . $v->getPrenom() . "</td>\n";
			echo "			<td>" . $v->getCursus() . "</td>\n";
			echo "			<td>" . $v->getParcours() . "</td>\n";
			echo "			<td>" . $v->getApprentissage() . "</td>\n";
			echo "			<td>" . $v->getAvisInge() . "</td>\n";
			echo "			<td>" . $v->getAvisMaster() . "</td>\n";
			echo "			<td>" . $v->getCommentaire() . "</td>\n";
			echo "			<td>" . $v->getMobEtrang() . "</td>\n";
			echo "			<td class=\"colonneBtn\"><button type='button' class='edit-button' onclick='toggleEditMode(this)'>Modifier</button><button type='button' class='cancel-button' style='display:none;' onclick='reloadPage()'>Annuler</button></td>\n"; // Bouton pour activer le mode édition
			echo "		</tr>\n";
		}

		echo "	</tbody>\n";
		echo "	</table>\n";
		echo "</section>\n";
		echo "<footer>\n";
		echo "	<input type=\"text\" id=\"searchInput\" placeholder=\"Rechercher...\">\n";
		echo "</footer>\n";

		echo '<script src="../JS/ModifEtu.js"></script>' . "\n";
	}
?>
