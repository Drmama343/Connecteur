<?php
	include("../fonctionsPHP/fctAux.inc.php");

	session_start();
	
echo enTete("Accueil",["../styles/classique.css", "../styles/accueil.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	echo contenu();
	echo pied();

	function contenu () {
		$string = '
        <header>
            <h1>Bienvenue sur XlsxHub</h1>
        </header>

        <section>
            <h2>Fonctionnalités principales :</h2>
            <ol>
                <li><strong>Importation de fichiers .xlsx :</strong> Importez facilement vos fichiers Excel pour traiter vos données.</li>
                <li><strong>Visualisation des données :</strong> Explorez vos données de manière intuitive grâce à notre interface conviviale.</li>
                <li><strong>Exportation en PDF ou Excel :</strong> Exportez vos données sous forme de PDF pour une présentation professionnelle ou sous forme Excel pour un traitement ultérieur.</li>
            </ol>
        </section>

        <footer>
            <h3>Suivez-nous sur les réseaux sociaux pour les dernières mises à jour :</h3>
            <!-- Liens vers les réseaux sociaux -->
            <ul><!-- Vos liens vers les réseaux sociaux ici --></ul>
            <p>© XlsxHub - Tous droits réservés.</p>
        </footer>' . "\n";

	return $string;
	}
?>
