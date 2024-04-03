<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
	echo enTete("Export",["../styles/classique.css", "../styles/import.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);

	$commission = isset($_SESSION['info_commission'])	? $_SESSION['info_commission']	: "";
	$jury 		= isset($_SESSION['info_jury']) 		? $_SESSION['info_jury'] 		: "";
	$poursuite	= isset($_SESSION['info_poursuite']) 	? $_SESSION['info_poursuite']	: "";

	unset($_SESSION['info_commission']);
	unset($_SESSION['info_jury']);
	unset($_SESSION['info_poursuite']);

	echo contenu($commission, $jury, $poursuite);
	echo pied();

	function contenu($infoCommission, $infoJury, $infoPoursuite) {
		$string = "<header><h1>Téléchargement de fichiers</h1></header>";

		$string .=
		"<section>\n".
		"<article>\n".
		"<form action=\"../fonctionsPHP/ExportCommission.php\" method=\"get\">\n".

			"<label>Commission</label><br><br>\n".

			"<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>\n".

			"<label for=\"semestre\">Semestre :</label>\n".
			"<input type=\"radio\" id=\"semestre1\" name=\"semestre\" value=\"1\">\n".
			"<label for=\"semestre1\">S1</label>\n".
			"<input type=\"radio\" id=\"semestre2\" name=\"semestre\" value=\"2\">\n".
			"<label for=\"semestre1\">S2</label>\n".
			"<input type=\"radio\" id=\"semestre3\" name=\"semestre\" value=\"3\">\n".
			"<label for=\"semestre1\">S3</label>\n".
			"<input type=\"radio\" id=\"semestre4\" name=\"semestre\" value=\"4\">\n".
			"<label for=\"semestre1\">S4</label>\n".
			"<input type=\"radio\" id=\"semestre5\" name=\"semestre\" value=\"5\">\n".
			"<label for=\"semestre1\">S5</label>\n".
			"<input type=\"radio\" id=\"semestre6\" name=\"semestre\" value=\"6\">\n".
			"<label for=\"semestre1\">S6</label>\n".

			"<br>\n".
			"<p>$infoCommission</p>\n".
			"<br>\n".

			"<button type=\"submit\">Télécharger</button>\n".
		"</form>\n".
		"</article>\n";

		$string .=
		"<article>\n".
		"<form action=\"../fonctionsPHP/ExportJury.php\" method=\"get\">\n".

			"<label>Jury</label><br><br>\n".

			"<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>\n".

			"<label for=\"semestre\">Semestre :</label>\n".
			"<input type=\"radio\" id=\"semestre1\" name=\"semestre\" value=\"1\">\n".
			"<label for=\"semestre1\">S1</label>\n".
			"<input type=\"radio\" id=\"semestre2\" name=\"semestre\" value=\"2\">\n".
			"<label for=\"semestre1\">S2</label>\n".
			"<input type=\"radio\" id=\"semestre3\" name=\"semestre\" value=\"3\">\n".
			"<label for=\"semestre1\">S3</label>\n".
			"<input type=\"radio\" id=\"semestre4\" name=\"semestre\" value=\"4\">\n".
			"<label for=\"semestre1\">S4</label>\n".
			"<input type=\"radio\" id=\"semestre5\" name=\"semestre\" value=\"5\">\n".
			"<label for=\"semestre1\">S5</label>\n".
			"<input type=\"radio\" id=\"semestre6\" name=\"semestre\" value=\"6\">\n".
			"<label for=\"semestre1\">S6</label>\n".

			"<br>\n".
			"<p>$infoJury</p>\n".
			"<br>\n".

			"<button type=\"submit\">Télécharger</button>\n".
		"</form>\n".
		"</article>\n";

		$string .=
			"<article>\n".
				"<form action=\"../fonctionsPHP/ExportPoursuite.php\" method=\"get\">\n".

					"<label>Fiche de poursuite d'études</label><br>\n".
					"<br>\n".
					"<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\">\n".
					"<br>\n".
					"<p>$infoPoursuite</p>\n".
					"<br>\n".
					"<button type=\"submit\">Télécharger</button>\n".
				"</form>\n".
			"</article>\n".
		"</section>\n";

		return $string;
	}
?>
