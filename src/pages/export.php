<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
	echo enTete("Export",["../styles/classique.css", "../styles/import.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	echo contenu();
	echo pied();

	function contenu () {
		$string =
		"<h1>Téléchargement de fichiers</h1>".
		"<form id=\"downloadForm\" action=\"download.php\" method=\"get\">".

			"<label>Choisir le fichier à télécharger :</label><br>".

			"<label for=\"semester\">Semestre :</label>".
			"<input type=\"radio\" id=\"semester1\" name=\"semester\" value=\"S1\">".
			"<label for=\"semester1\">S1</label>".
			
			"<input type=\"radio\" id=\"semester2\" name=\"semester\" value=\"S2\">".
			"<label for=\"semester2\">S2</label>".
			
			"<br><br>".
			"<label for=\"file1\">Fichier 1 :</label>".
			"<input type=\"radio\" id=\"file1\" name=\"fileType\" value=\"file1.txt\">".
			"<label for=\"file1\">Fichier 1 (.txt)</label>".
			
			"<label for=\"file2\">Fichier 2 :</label>".
			"<input type=\"radio\" id=\"file2\" name=\"fileType\" value=\"file2.pdf\">".
			"<label for=\"file2\">Fichier 2 (.pdf)</label>".
			
			"<label for=\"file3\">Fichier 3 :</label>".
			"<input type=\"radio\" id=\"file3\" name=\"fileType\" value=\"file3.doc\">".
			"<label for=\"file3\">Fichier 3 (.doc)</label>".

			"<br>".
			"<button type=\"submit\">Télécharger</button>".
		"</form>";

		return $string;
	}
?>
