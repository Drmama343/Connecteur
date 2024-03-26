<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/virtualisation.css"]);
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
				$t = $db->getTout();
			} //fin try
			catch (Exception $e) {
				echo $e->getMessage();
			}  
			$db->close();
		} //fin du else connexion reussie

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
	
?>
