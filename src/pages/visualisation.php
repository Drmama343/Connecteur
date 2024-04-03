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
        echo "<td><button type='button' class='edit-button' onclick='toggleEditMode(this)'>Modifier</button><button type='button' class='cancel-button' style='display:none;' onclick='reloadPage()'>Annuler</button></td>"; // Bouton pour activer le mode édition
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
			// Fonction pour recharger la page
			function reloadPage() {
				location.reload();
			}

			// Fonction pour valider et soumettre les modifications
			function validateChanges() {
				const rows = document.querySelectorAll("#etudiantsTable tbody tr");
				for (let i = 0; i < rows.length; i++) {
					const inputs = rows[i].querySelectorAll("input[type=text]");
					for (let j = 0; j < inputs.length; j++) {
						if (inputs[j].value === "") {
							alert("Veuillez remplir tous les champs avant de valider.");
							return;
						}
					}
				}

				// Tous les champs sont remplis, vous pouvez maintenant soumettre les modifications
				// Collecter les données et appeler la fonction updateEtudiant
				for (let i = 0; i < rows.length; i++) {
					const cells = rows[i].querySelectorAll("td:not(:last-child)");
					const codeNip = cells[0].textContent;
					const cursus = cells[3].textContent;
					const parcours = cells[4].textContent;
					const apprentissage = cells[5].textContent;
					const avisInge = cells[6].textContent;
					const avisMaster = cells[7].textContent;
					const commentaire = cells[8].textContent;
					const etranger = cells[9].textContent;

					// Appeler la fonction updateEtudiant avec les valeurs récupérées
					updateEtudiant(codeNip, cursus, parcours, apprentissage, avisInge, avisMaster, commentaire, etranger);
				}

				// Recharger la page après la validation
				reloadPage();
			}

			// Fonction pour basculer entre le mode d édition et de validation
			function toggleEditMode(button) {
				const row = button.parentNode.parentNode;
				const cells = row.querySelectorAll("td:not(:last-child)");
				const isEditing = button.textContent === "Valider";
				const cancelButton = row.querySelector(".cancel-button");

				if (isEditing) {
					// Valider les modifications
					for (let i = 1; i < cells.length; i++) {
						const input = cells[i].querySelector("input[type=text]");
						cells[i].textContent = input.value;
					}
					button.textContent = "Modifier";
					cancelButton.style.display = "none"; // Cacher le bouton "Annuler"
				} else {
					// Activer le mode édition
					for (let i = 1; i < cells.length; i++) {
						const text = cells[i].textContent;
						cells[i].innerHTML = "<input type=\'text\' value=\'" + text + "\'>";
					}
					button.textContent = "Valider";
					cancelButton.style.display = ""; // Afficher le bouton "Annuler"
				}
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
