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
		$string = "<section><h1>Téléchargement de fichiers</h1></section>";

		$string .=
		"<section>".
		"<form action=\"../fonctionsPHP/telechargementCommission.php\" method=\"get\">".

			"<label>Commission</label><br><br>".

			"<input type=\"text\" id=\"year\" name=\"year\" placeholder=\"Année (ex: 2022-2023)\"><br>".

			"<label for=\"semester\">Semestre :</label>".
			"<input type=\"radio\" id=\"semester1\" name=\"semester\" value=\"1\">".
			"<label for=\"semester1\">S1</label>".
			"<input type=\"radio\" id=\"semester2\" name=\"semester\" value=\"2\">".
			"<label for=\"semester2\">S2</label>".
			"<input type=\"radio\" id=\"semester3\" name=\"semester\" value=\"3\">".
			"<label for=\"semester3\">S3</label>".
			"<input type=\"radio\" id=\"semester4\" name=\"semester\" value=\"4\">".
			"<label for=\"semester4\">S4</label>".
			"<input type=\"radio\" id=\"semester5\" name=\"semester\" value=\"5\">".
			"<label for=\"semester5\">S5</label>".
			"<input type=\"radio\" id=\"semester6\" name=\"semester\" value=\"6\">".
			"<label for=\"semester6\">S6</label>".

			"<br>".
			"<p>$infoCommission</p>".
			"<br>".

			"<button type=\"submit\">Télécharger</button>".
		"</form>".
		"</section>";

		$string .=
		"<section>".
		"<form action=\"../fonctionsPHP/telechargementJury.php\" method=\"get\">".

			"<label>Jury</label><br><br>".

			"<input type=\"text\" id=\"year\" name=\"year\" placeholder=\"Année (ex: 2022-2023)\"><br>".

			"<label for=\"semester\">Semestre :</label>".
			"<input type=\"radio\" id=\"semester1\" name=\"semester\" value=\"1\">".
			"<label for=\"semester1\">S1</label>".
			"<input type=\"radio\" id=\"semester2\" name=\"semester\" value=\"2\">".
			"<label for=\"semester2\">S2</label>".
			"<input type=\"radio\" id=\"semester3\" name=\"semester\" value=\"3\">".
			"<label for=\"semester3\">S3</label>".
			"<input type=\"radio\" id=\"semester4\" name=\"semester\" value=\"4\">".
			"<label for=\"semester4\">S4</label>".
			"<input type=\"radio\" id=\"semester5\" name=\"semester\" value=\"5\">".
			"<label for=\"semester5\">S5</label>".
			"<input type=\"radio\" id=\"semester6\" name=\"semester\" value=\"6\">".
			"<label for=\"semester6\">S6</label>".

			"<br>".
			"<p>$infoJury</p>".
			"<br>".

			"<button type=\"submit\">Télécharger</button>".
		"</form>".
		"</section>";

		$string .=
		"<section>".
		"<form action=\"../fonctionsPHP/telechargementPoursuite.php\" method=\"get\">".

			"<label>Fiche de poursuite d'études</label><br>".
			"<br>".
			"<p>$infoPoursuite</p>".
			"<br>".
			"<button type=\"submit\">Télécharger</button>".
		"</form>".
		"</section>";

		return $string;
	}
?>
