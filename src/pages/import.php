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

		echo "		<header><h1>Importer des Données</h1></header>\n";
		echo "		<section>\n";
		
		if ( isset($_SESSION['alerteErreur']) ) {
<<<<<<< HEAD
			echo "			<article>\n";
			echo "				<form action=\"../fonctionsPHP/ImportMoyennes.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
			echo "					<h2>Duplication de tuples</h2>\n";
			echo "					<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>\n";
			echo "					<input type=\"file\" name=\"file\" id=\"file\" required><br>\n";
			echo "					<p>Le remplissage de la base de données a été intérompu car des données apparaissent plusieurs fois.</p>\n";
			echo "					<p>Voulez vous :</p>\n";
			echo "					<input type=\"reset\" name=\"reset\" value=\"Annuler\" onclick=\"redirect()\">\n";
			echo "					<input type=\"submit\" name=\"submit\" value=\"Ecraser\">\n";
			echo "				</form>\n";
			echo "			</article>\n";
=======
			echo "		<section>\n";
			echo "			<form action=\"../fonctionsPHP/Import".$_SESSION['alerteErreur'].".php\" method=\"post\" enctype=\"multipart/form-data\">\n";
			echo "				<h2>Duplication de tuples</h2>\n";
			echo "				<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>\n";
			echo "				<input type=\"file\" name=\"file\" id=\"file\" required><br>\n";
			echo "				<p>Le remplissage de la base de données a été intérompu car des données apparaissent plusieurs fois.</p>\n";
			echo "				<p>Voulez vous :</p>\n";
			echo "				<input type=\"reset\" name=\"reset\" value=\"Annuler\" onclick=\"redirect()\">\n";
			echo "				<input type=\"submit\" name=\"submit\" value=\"Ecraser\">\n";
			echo "			</form>\n";
			echo "		</section>\n";
>>>>>>> 344f26d24c849397b2d69020b325de7cd488b21b
			unset($_SESSION['alerteErreur']);

			echo "<script>
						// Fonction pour effectuer la redirection
						function redirect() {
							// Redirection vers la page spécifiée
							window.location.href = \"import.php\";
						}
					</script>";
		}
		else {
			echo "			<article>\n";
			echo "				<form action=\"../fonctionsPHP/ImportMoyennes.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
			echo "					<h2>Déposer un fichier Moyennes</h2>\n";
			echo "					<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>\n";
			echo "					<input type=\"file\" name=\"file\" id=\"file\" required><br>\n";
			echo "					<input type=\"submit\" value=\"Importer\">\n";
			echo "					<p>$infoMoyennes</p>\n";
			echo "				</form>\n";
			echo "			</article>\n";

			echo "			<article>\n";
			echo "				<form action=\"../fonctionsPHP/ImportJury.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
			echo "					<h2>Déposer un fichier Jury</h2>\n";
			echo "					<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>\n";
			echo "					<input type=\"file\" name=\"file\" id=\"file\" required><br>\n";
			echo "					<input type=\"submit\" value=\"Importer\">\n";
			echo "					<p>$infoJury</p>\n";
			echo "				</form>\n";
			echo "			</article>\n";
		}

		echo "		</section>\n";
	}
?>
