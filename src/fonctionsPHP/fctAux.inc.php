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

		$string .= "
	</head>
	<body>\n";

		return $string;
	}

	function pied() {
		$string  ="	</body>
</html>";

		return $string;
	}

	function menu($nom, $droit) {
		if ($droit === 2) {
			$string = "<nav>
				<div class=\"id\">
					<p>$nom</p>
					<p>Mode : Administrateur</p>
				</div>
	
				<a href=\"./accueil.php\">Accueil</a>
				<hr class=\"hrmenu\">
				<a href=\"./import.php\">Import</a>
				<hr class=\"hrmenu\">
				<div class=\"sous-menu-wrapper\" onmouseover=\"afficherSousMenu('sousMenuUser')\" onmouseout=\"masquerSousMenu('sousMenuUser')\">
					<p>Visualisation</p>
					<div id=\"sousMenuUser\" style=\"display: none;\">
						<a href=\"./visualisation.php\">Par Etudiant</a>
						<a href=\"#\">Par Notes</a>
					</div>
				</div>
				<hr class=\"hrmenu\">
				<a href=\"./export.php\">Export</a>";
		} else {
			$string = "<nav>
				<div class=\"id\">
					<p>$nom</p>
					<p>Mode : Utilisateur</p>
				</div>
	
				<a href=\"./accueil.php\">Accueil</a>
				<hr class=\"hrmenu\">
				<div class=\"sous-menu-wrapper\" onmouseover=\"afficherSousMenu('sousMenuUser')\" onmouseout=\"masquerSousMenu('sousMenuUser')\">
					<p>Visualisation</p>
					<div id=\"sousMenuUser\" style=\"display: none;\">
						<a href=\"./visualisation.php\">Par Etudiant</a>
						<a href=\"#\">Par Notes</a>
					</div>
				</div>
				<hr class=\"hrmenu\">
				<a href=\"./export.php\">Export</a>";
		}
	
		$string .= '
				<a class="logout" href="../connexion.php"> <img src="../images/logout.png" alt="logout" width="50" height="40"></a>
			</nav>' . "\n";
	
		$string .= "<script>
			function afficherSousMenu(id) {
				document.getElementById(id).style.display = 'block';
			}
	
			function masquerSousMenu(id) {
				document.getElementById(id).style.display = 'none';
			}
		</script>";
	
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