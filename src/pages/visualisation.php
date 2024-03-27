<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/virtualisation.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	if ( $_SESSION['droitAcces'] === 2 )
		contenuAdmin();
	else
		contenu();
	echo pied();

	function contenu() {
		$db = DB::getInstance();
		if ($db == null) {
			echo "Impossible de se connecter";
		}
		else {
			try {
				$t = $db->getTout();
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
						<th>nom</th>
						<th>prenom</th>
						<th>moyenne</th>
					</tr>
				</thead>
				<tbody>\n";

		foreach ($t as &$v) {
			$nom = $v->getNom();
			$prenom = $v->getPrenom();
			$moy = $v->getMoy();

			echo "<td>$nom</td>\n";
			echo "<td>$prenom</td>\n";
			echo "<td>$moy</td>\n";

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
				$t = $db->getTout();
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
			$nom = $v->getNom();
			$prenom = $v->getPrenom();
			$moy = $v->getMoy();

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

		
		echo "		<aside><a class=\"btnAJour\" href='visualisation.php?updateCli=".$nom."&updateNp=".$prenom."'>mettre à jour</a></aside>\n";
	}
	
?>
