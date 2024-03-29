<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
	echo enTete("Import",["../styles/import.css", "../styles/classique.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);

	$infoMoyennes	= isset($_SESSION['info_import_moyennes']) 	? $_SESSION['info_import_moyennes'] : "";
	$infoJury		= isset($_SESSION['info_import_jury']) 		? $_SESSION['info_import_jury']		: "";

	unset($_SESSION['info_import_moyennes']);
	unset($_SESSION['info_import_jury']);

	contenu($infoMoyennes, $infoJury);
	echo pied();

	function contenu ($infoMoyennes, $infoJury) {
		echo "		<section>\n";
		echo "			<form action=\"../fonctionsPHP/enregistrementMoyennes.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "				<h2>Déposer un fichier Moyennes</h2>\n";
		echo "				<input type=\"file\" name=\"file\" id=\"file\" required><br>\n";
		echo "				<input type=\"submit\" value=\"Importer\">\n";
		echo "				<p>$infoMoyennes</p>\n";
		echo "			</form>\n";
		echo "		</section>\n";

		echo "		<section>\n";
		echo "			<form action=\"../fonctionsPHP/enregistrementJury.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "				<h2>Déposer un fichier Jury</h2>\n";
		echo "				<input type=\"file\" name=\"file\" id=\"file\" required><br>\n";
		echo "				<input type=\"submit\" value=\"Importer\" onclick=\"showLoader('loader-jury')\">\n";
		echo "				<p>$infoJury</p>\n";
		echo "			</form>\n";
		echo "		</section>\n";

		echo "<script>
			function showLoader(loaderId) {
			var loader = document.createElement('div');
			loader.className = 'loader';
			loader.id = loaderId;
			loader.innerHTML = '<img src=\"../images/loadingBar.png\" alt=\"Loading...\">';
			document.getElementById(loaderId).appendChild(loader);
			}
			</script>";
	}



?>
