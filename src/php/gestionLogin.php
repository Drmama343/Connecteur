<?php

	include("utilisateur.inc.php");

	$nom = $_POST['username'];
	$password = $_POST['password'];

	if (Utilisateur::verification($nom, $password)) {
		session_start();

		$utilisateur = new Utilisateur($nom, Utilisateur::niveauDroit($nom));

		$_SESSION['utilisateur'] = serialize($utilisateur);

		header("Location: ../pages/accueil.html");
		exit();
	} else {
		header("Location: ../index.html");
		exit();
	}
?>
