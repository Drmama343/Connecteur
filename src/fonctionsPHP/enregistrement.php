<?php
// Paramètres de connexion à la base de données PostgreSQL
$host = "woody"; // ou l'adresse IP de votre serveur PostgreSQL
$port = "5432"; // port par défaut pour PostgreSQL
$dbname = "ca220584";
$user = "ca220584";
$password = "Miss10sur10!";

// Connexion à la base de données PostgreSQL
try {
	$conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
	// Définir le mode d'erreur PDO sur Exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "Connexion à la base de données PostgreSQL réussie.";
} catch (PDOException $e) {
	echo "Erreur de connexion à la base de données PostgreSQL : " . $e->getMessage();
}

// Vérifier si le formulaire a été soumis et le fichier téléchargé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
	// Obtenir les détails sur le fichier téléchargé
	$fileName = basename($_FILES["file"]["name"]);
	$fileType = pathinfo($fileName, PATHINFO_EXTENSION);

	// Vérifier si le fichier est un fichier réel et avec l'extension .xlsx
	if ($fileType == "xlsx") {
		// Lire le contenu du fichier Excel
		require '../../vendor/autoload.php'; // Charge la bibliothèque PhpSpreadsheet
		$reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($_FILES["file"]["tmp_name"]);
		$worksheet = $spreadsheet->getActiveSheet();
		
		// Parcourir les lignes du fichier Excel à partir de la deuxième ligne (pour éviter la ligne d'en-tête)
		foreach ($worksheet->getRowIterator() as $row) {
			// Ignorer la première ligne (en-tête)
			if ($row->getRowIndex() == 1) {
				continue;
			}
			
			$rowData = [];
			foreach ($row->getCellIterator() as $cell) {
				$rowData[] = $cell->getValue();
			}

			// Insérer les données dans la base de données
			$sql = "INSERT INTO nom_de_votre_table (colonne1, colonne2, colonne3) VALUES (?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->execute($rowData);
		}

		echo "Les données du fichier $fileName ont été insérées avec succès dans la base de données.";
	} else {
		echo "Seuls les fichiers .xlsx sont autorisés.";
	}
}

// Fermer la connexion à la base de données
$conn = null;
?>
