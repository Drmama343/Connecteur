<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/acceuil.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	contenu();
	echo pied();

	function contenu () {
		echo "	<h2>Déposer un fichier</h2>\n";
	}
?>
