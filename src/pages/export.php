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
		"<section>".
		"<form action=\"../fonctionsPHP/ExportCommission.php\" method=\"get\">".

			"<label>Commission</label><br><br>".

			"<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>".

			"<label for=\"semestre\">Semestre :</label>".
			"<input type=\"radio\" id=\"semestre1\" name=\"semestre\" value=\"1\">".
			"<label for=\"semestre1\">S1</label>".
			"<input type=\"radio\" id=\"semestre2\" name=\"semestre\" value=\"2\">".
			"<label for=\"semestre1\">S2</label>".
			"<input type=\"radio\" id=\"semestre3\" name=\"semestre\" value=\"3\">".
			"<label for=\"semestre1\">S3</label>".
			"<input type=\"radio\" id=\"semestre4\" name=\"semestre\" value=\"4\">".
			"<label for=\"semestre1\">S4</label>".
			"<input type=\"radio\" id=\"semestre5\" name=\"semestre\" value=\"5\">".
			"<label for=\"semestre1\">S5</label>".
			"<input type=\"radio\" id=\"semestre6\" name=\"semestre\" value=\"6\">".
			"<label for=\"semestre1\">S6</label>".

			"<br>".
			"<p>$infoCommission</p>".
			"<br>".

			"<button type=\"submit\">Télécharger</button>".
		"</form>".
		"</section>";

		$string .=
		"<section>".
		"<form action=\"../fonctionsPHP/ExportJury.php\" method=\"get\">".

			"<label>Jury</label><br><br>".

			"<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\"><br>".

			"<label for=\"semestre\">Semestre :</label>".
			"<input type=\"radio\" id=\"semestre1\" name=\"semestre\" value=\"1\">".
			"<label for=\"semestre1\">S1</label>".
			"<input type=\"radio\" id=\"semestre2\" name=\"semestre\" value=\"2\">".
			"<label for=\"semestre1\">S2</label>".
			"<input type=\"radio\" id=\"semestre3\" name=\"semestre\" value=\"3\">".
			"<label for=\"semestre1\">S3</label>".
			"<input type=\"radio\" id=\"semestre4\" name=\"semestre\" value=\"4\">".
			"<label for=\"semestre1\">S4</label>".
			"<input type=\"radio\" id=\"semestre5\" name=\"semestre\" value=\"5\">".
			"<label for=\"semestre1\">S5</label>".
			"<input type=\"radio\" id=\"semestre6\" name=\"semestre\" value=\"6\">".
			"<label for=\"semestre1\">S6</label>".

			"<br>".
			"<p>$infoJury</p>".
			"<br>".

			"<button type=\"submit\">Télécharger</button>".
		"</form>".
		"</section>";

		$string .=
		"<section>".
		"<form action=\"../fonctionsPHP/ExportPoursuite.php\" method=\"get\">".

			"<label>Fiche de poursuite d'études</label><br>".
			"<br>".
			"<input type=\"text\" id=\"annee\" name=\"annee\" placeholder=\"Année (ex: 2022-2023)\">".
			"<br>".
			"<p>$infoPoursuite</p>".
			"<br>".
			"<button type=\"submit\">Télécharger</button>".
		"</form>".
		"</section>";

		return $string;
	}
?>
