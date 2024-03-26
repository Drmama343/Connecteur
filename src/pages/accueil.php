<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
	echo enTete("Accueil","../styles/acceuil.css");
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	contenu();
	echo pied();

	function contenu () {
		echo "\n<div class=\"container\">\n";
		echo "	<div class=\"content\">";
		echo "	<h1>Bonjour chef</h1>";
		echo "	</div>\n";
		echo "</div>\n";
	}
?>
