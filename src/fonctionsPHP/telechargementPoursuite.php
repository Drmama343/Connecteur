<?php

session_start();
require ("DB.inc.php");

$db = DB::getInstance();
if ($db == null) {
	$_SESSION['info_poursuite'] = "Connexion à la base de données impossible";
	header("Location: ../pages/export.php");
}

// Vérifier si le paramètre "fileType" est présent dans la requête
if(isset($_GET['fileType'])) {
	// Récupérer l'extension sélectionnée depuis la requête
	$fileType = $_GET['fileType'];

	// Définir le chemin vers le répertoire où se trouvent les fichiers
	$directory = 'chemin/vers/le/repertoire/';

	// Définir le nom du fichier à télécharger en fonction de l'extension sélectionnée
	$fileName = 'nom_du_fichier' . $fileType;

	// Chemin complet vers le fichier à télécharger
	$filePath = $directory . $fileName;

	// Vérifier si le fichier existe
	if(file_exists($filePath)) {
		// Définir les en-têtes HTTP pour le téléchargement
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filePath));

		// Lire le fichier et le transmettre en sortie
		readfile($filePath);
		exit;
	} else {
		// Si le fichier n'existe pas, afficher un message d'erreur
		echo "Le fichier n'existe pas.";
	}
} else {
	// Si le paramètre "fileType" n'est pas présent dans la requête, afficher un message d'erreur
	echo "Paramètre manquant : 'fileType'.";
}
?>