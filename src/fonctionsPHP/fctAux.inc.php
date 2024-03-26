<?php
	function enTete($titre,$css) {
		$string  = "<!DOCTYPE html>
		<html lang=\"fr\">
		<head>
			<meta charset=\"UTF-8\">
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
			<title>". $titre ."</title>";

			for ($i = 0; $i < count($css); $i++) {
				$string .= "<link rel=\"stylesheet\" href=\"". $css[$i] ."\">";
			}

		$string .= "</head>
		<body>";

		return $string;
	}

	function pied() {
		$string  ="</body>
		</html>";

		return $string;
	}

	function menu($nom, $droit) {
		if ( $droit === 2 ) {
			$string  ="<div class=\"menu\">
		<div class=\"id\">
		<p>$nom</p>\n
		<p>Mode : Administrateur</p>\n
		</div>
		<a href=\"./accueil.php\">Accueil</a>
		<hr class=\"hrmenu\">
		<a href=\"./import.php\">Import</a>
		<hr class=\"hrmenu\">
		<a href=\"#\">Visualisation</a>
		<hr class=\"hrmenu\">
		<a href=\"#\">Export</a>
	</div>";
		}
		else {
			$string  ="<div class=\"menu\">
		<div class=\"id\">
		<p>$nom</p>\n
		<p>Mode : Utilisateur</p>\n
		</div>
		<a href=\"./accueil.php\">Accueil</a>
		<hr class=\"hrmenu\">
		<a href=\"#\">Visualisation</a>
		<hr class=\"hrmenu\">
		<a href=\"#\">Export</a>
	</div>";
		}

		return $string;
	}

	function isLoginOK($login) {
		$tab = ["user"=>"0","admin"=>"0"];
		foreach ($tab as $key=>$value) {
			if ($login === $key)
				{
					return true;
				}
		}
		return false;
	}

	function isMotDePasseOK ($login,$mdp) {
		$tab = ["user"=>"0","admin"=>"0"];
		foreach ($tab as $key=>$value) {
			if ($login === $key && $mdp === $value)
				return true;
		}
		return false;
	}
?>