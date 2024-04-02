<?php

	include ("../fonctionsPHP/fctAux.inc.php");
	require ("../fonctionsPHP/DB.inc.php");

	session_start();
	
	echo enTete("Visualisation",["../styles/classique.css", "../styles/virtualisation.css"]);
	echo menu($_SESSION['nom'], $_SESSION['droitAcces']);
	if ( $_SESSION['droitAcces'] === 2 )
		contenuAdmin();
	else
		contenuUser();
	echo pied();

	function contenuUser() {
		$db = DB::getInstance();
		if ($db == null) {
			echo "Impossible de se connecter";
		}
		else {
			try {
				$t = $db->getEtudiants();
			} //fin try
			catch (Exception $e) {
				echo $e->getMessage();
			}  
			$db->close();
		} //fin du else connexion reussie

		echo "<header>";
		echo "<h1>Visualisation</h1>";
		echo "</header>";

		echo "		<section>
			<table>
				<thead>
					<tr>
						<th>Code NIP</th>
						<th>Nom</th>
						<th>Prénom</th>
						<th>Cursus</th>
						<th>Parcours</th>
						<th>Apprentissage</th>
						<th>Avis Ingénieur</th>
						<th>Avis Master</th>
						<th>Commentaire</th>
						<th>Mobilité étrangère</th>
					</tr>
				</thead>
				<tbody>\n";

		foreach ($t as &$v) {

			echo "<td>" . $v->getCode() . "</td>\n";
			echo "<td>" . $v->getNom() . "</td>\n";
			echo "<td>" . $v->getPrenom() . "</td>\n";
			echo "<td>" . $v->getCursus() . "</td>\n";
			echo "<td>" . $v->getParcours() . "</td>\n";
			echo "<td>" . $v->getApprentissage() . "</td>\n";
			echo "<td>" . $v->getAvisInge() . "</td>\n";
			echo "<td>" . $v->getAvisMaster() . "</td>\n";
			echo "<td>" . $v->getCommentaire() . "</td>\n";
			echo "<td>" . $v->getMobEtrang() . "</td>\n";

			echo "</tr>\n";
		}

		echo "
					</tbody>
				</table>
			</section>\n";
	}

	function contenuAdmin () {
		$db = DB::getInstance();
		if ($db == null) {
			echo "Impossible de se connecter";
		}
		else {
			try {
				$t = $db->getEtudiants();
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}  
			$db->close();
		}
	
		echo "<header>";
		echo "<h1>Visualisation</h1>";
		echo "</header>";
	
		echo "<form method='post' action='valider_modifications.php'>"; // Formulaire pour soumettre les modifications
    echo "<section>";
    echo "<table id=\"etudiantsTable\">";
    echo "<thead id=\"tableHeader\">";
    echo "<tr>";
    echo "<th>Code NIP</th>";
    echo "<th>Nom</th>";
    echo "<th>Prénom</th>";
    echo "<th>Cursus</th>";
    echo "<th>Parcours</th>";
    echo "<th>Apprentissage</th>";
    echo "<th>Avis Ingénieur</th>";
    echo "<th>Avis Master</th>";
    echo "<th>Commentaire</th>";
    echo "<th>Mobilité étrangère</th>";
    echo "<th>Modifier</th>"; // Ajout d'une colonne pour le bouton Modifier
    echo "</tr>";
    echo "</thead>";
    echo "<tbody id=\"tableBody\">\n";

    foreach ($t as &$v) {
        echo "<tr>";
        echo "<td>" . $v->getCode() . "</td>";
        echo "<td>" . $v->getNom() . "</td>";
        echo "<td>" . $v->getPrenom() . "</td>";
        echo "<td>" . $v->getCursus() . "</td>";
        echo "<td>" . $v->getParcours() . "</td>";
        echo "<td>" . $v->getApprentissage() . "</td>";
        echo "<td>" . $v->getAvisInge() . "</td>";
        echo "<td>" . $v->getAvisMaster() . "</td>";
        echo "<td>" . $v->getCommentaire() . "</td>";
        echo "<td>" . $v->getMobEtrang() . "</td>";
        echo "<td><button type='button' onclick='toggleEditMode(this)'>Modifier</button></td>"; // Bouton pour activer le mode édition
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</section>";
    echo "<footer>";
    echo "<input type=\"text\" id=\"searchInput\" placeholder=\"Rechercher...\">";
    echo "<input type='reset' name='valider' value='Valider'>"; // Bouton pour valider les modifications
    echo "</footer>";
    echo "</form>";

		echo '
		<script>
		function toggleEditMode(button) {
			const row = button.parentNode.parentNode;
			const cells = row.querySelectorAll("td:not(:last-child)");
			const isEditing = button.textContent === "Valider";
			const cancelButton = row.querySelector(".cancel-button");
	
			if (isEditing) {
				// Valider les modifications
				for (let i = 0; i < cells.length; i++) {
					const input = cells[i].querySelector("input[type=text]");
					cells[i].textContent = input.value;
				}
				button.textContent = "Modifier";
				cancelButton.style.display = "none"; // Cacher le bouton "Annuler"
			} else {
				// Activer le mode édition
				for (let i = 3; i < cells.length; i++) {
					const text = cells[i].textContent;
					cells[i].innerHTML = "<input type=\'text\' value=\'" + text + "\'>";
					// Fixer la largeur des cellules
					cells[i].style.width = cells[i].offsetWidth + "px";
				}
				button.textContent = "Valider";
				cancelButton.style.display = ""; // Afficher le bouton "Annuler"
			}
		}
	
		// Fonction pour annuler les modifications
		function cancelEdit(button) {
			const row = button.parentNode.parentNode;
			const cells = row.querySelectorAll("td:not(:last-child)");
			const editButton = row.querySelector(".edit-button");
	
			for (let i = 0; i < cells.length; i++) {
				const text = cells[i].querySelector("input[type=text]").value;
				cells[i].textContent = text;
			}
	
			editButton.textContent = "Modifier";
			button.style.display = "none"; // Cacher le bouton "Annuler"
		}

			// Script JavaScript pour le tri et la recherche
			document.addEventListener("DOMContentLoaded", function () {
			const table = document.getElementById("etudiantsTable");
			const tableHeader = document.getElementById("tableHeader");
			const tableBody = document.getElementById("tableBody");
			const searchInput = document.getElementById("searchInput");
	
			// Fonction de recherche
			function search() {
				const filter = searchInput.value.toUpperCase();
				const rows = tableBody.getElementsByTagName("tr");
	
				for (let i = 0; i < rows.length; i++) {
					const cells = rows[i].getElementsByTagName("td");
					let found = false;
	
					for (let j = 0; j < cells.length; j++) {
						const cell = cells[j];
						if (cell) {
							const textValue = cell.textContent || cell.innerText;
							if (textValue.toUpperCase().indexOf(filter) > -1) {
								found = true;
								break;
							}
						}
					}
	
					if (found) {
						rows[i].style.display = "";
					} else {
						rows[i].style.display = "none";
					}
				}
			}
	
			searchInput.addEventListener("keyup", search);
	
			// Fonction de tri
			function sortTable(n) {
				const rows = tableBody.rows;
				const switching = true;
				let shouldSwitch;
	
				while (switching) {
					switching = false;
					for (let i = 0; i < (rows.length - 1); i++) {
						shouldSwitch = false;
						const x = rows[i].getElementsByTagName("td")[n];
						const y = rows[i + 1].getElementsByTagName("td")[n];
						if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
							shouldSwitch = true;
							break;
						}
					}
					if (shouldSwitch) {
						rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						switching = true;
					}
				}
			}
	
			const headers = tableHeader.getElementsByTagName("th");
			for (let i = 0; i < headers.length; i++) {
				headers[i].addEventListener("click", function () {
					sortTable(i);
				});
			}
	
			window.onscroll = function () {
				if (window.pageYOffset > table.offsetTop) {
					tableHeader.style.position = "sticky";
					tableHeader.style.top = "0";
					tableHeader.style.zIndex = "1";
				} else {
					tableHeader.style.position = "static";
				}
			};
		});
	</script>' . "\n";
	}
?>
