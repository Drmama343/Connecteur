<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
	echo enTete("Import",["../styles/import.css", "../styles/classique.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	try{
		contenu($_SESSION['info_import']);
		$_SESSION['info_import'] = "";}
	catch(Exception $e) {contenu("");}
	echo pied();

	function contenu ($info) {
		echo "<form action=\"../fonctionsPHP/enregistrement.php\" method=\"post\" enctype=\"multipart/form-data\">";
		echo "	<h2>Déposer un fichier</h2>";
		echo "	<input type=\"file\" name=\"file\" id=\"file\" required><br>";
		echo "	<input type=\"submit\" value=\"Importer\">";
		echo "<p>$info</p>";
		echo "</form>";
	}
?>
