<?php
// Vos informations d'identification (à remplacer par votre propre logique de gestion des utilisateurs)
$identifiant_valide = "Frizoks";
$mot_de_passe_valide = "equipe7";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $identifiant = $_POST["username"];
    $mot_de_passe = $_POST["password"];

    // Vérifier si les informations d'identification sont correctes
    if ($identifiant == $identifiant_valide && $mot_de_passe == $mot_de_passe_valide) {
        // Redirection vers la page d'accueil si les informations sont correctes
        header("Location: accueil.php");
        exit;
    } else {
        // Afficher un message d'erreur si les informations d'identification sont incorrectes
        echo "<script>alert('Identifiant ou mot de passe incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /* Votre CSS ici */
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
            <input type="password" name="password" placeholder="Mot de passe" required><br>
            <input type="submit" value="Se connecter">
        </form>
    </div>
</body>
</html>