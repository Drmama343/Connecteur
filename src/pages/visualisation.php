<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/virtualisation.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	/*if ( $_SESSION['droitAcces'] === 2 )
		contenuAdmin();
	else
		contenuUser();*/
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
				if (isset($_POST['moy']) && !empty($_POST))
				{
					if (isset($_POST['valider']))
					{
						$db->updateMoy($_GET['updateCli'], $_POST['moy']);
					}
					$_GET['updateCli'] = $_GET['updateNp'] = null;
				}
				$t = $db->getEtudiants();
			} //fin try
			catch (Exception $e) { 
				echo $e->getMessage();
			}  
			$db->close();
		} //fin du else connexion reussie

		echo "		<header>\n";
		echo "			<h1>Visualisation</h1>\n";
		echo "		</header>\n";

		echo "		<section>
			<table>
				<thead>
					<tr>
						<th>nom</th>
						<th>prenom</th>
						<th>moyenne</th>
						<th class=\"colonneBtn\"></th>
					</tr>
				</thead>
				<tbody>
					<tr>\n";

		foreach ($t as &$v) {
			$code = $v->getCode();
			$nom = $v->getNom();
			$prenom = $v->getPrenom();
			$cursus = $v->getCursus();
			$parcours = $v->getParcours();
			$apprentissage = $v->getApprentissage();
			$avisInge = $v->getAvisInge();
			$avisMaster = $v->getAvisMaster();
			$commentaire = $v->getCommentaire();
			$abs = $v->getAbsInjust();

			if ( isset($_GET['updateCli'], $_GET['updateNp']) && $_GET['updateCli'] == $nom && $_GET['updateNp'] == $prenom)
			{
				echo "						<td>$nom</td>\n";
				echo "						<td>$prenom</td>\n";

				echo "						<form action=\"visualisation.php?updateCli=".$nom."&updateNp=".$prenom."\" method=\"post\">\n";
				echo "							<td><input type=\"text\" name=\"moy\" value=\"$moy\" required></td>\n";
				echo "							<td class=\"colonneBtn\"><input name=\"annuler\" type=\"submit\" value=\"annuler\"><input name=\"valider\" type=\"submit\" value=\"valider\"></td>\n";
				echo "						</form>\n";
			}
			else
			{
				echo "					<tr>\n";
				echo "						<td>$nom</td>\n";
				echo "						<td>$prenom</td>\n";
				echo "						<td>$moy</td>\n";
			}
			echo "					</tr>\n";
		}

		echo "					</tbody>
				</table>
			</section>\n";

		
		echo "		<footer><a class=\"btnAJour\" href='visualisation.php?updateCli=".$nom."&updateNp=".$prenom."'>mettre à jour</a></footer>\n";
	}
	
?>
