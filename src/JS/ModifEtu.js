// Fonction pour recharger la page
function reloadPage() {
	location.reload();
}

// Fonction pour valider et soumettre les modifications
function validateChanges() {
	const rows = document.querySelectorAll("#etudiantsTable tbody tr");
	let modifiedRowFound = false; // Variable pour vérifier si une ligne est en mode d édition

	// Vérifier si une ligne est en mode d édition
	for (let i = 0; i < rows.length; i++) {
		if (rows[i].classList.contains("edit-mode")) {
			modifiedRowFound = true;
			break;
		}
	}

	// Si aucune ligne n est en mode d édition, afficher un message d erreur
	if (!modifiedRowFound) {
		alert("Veuillez sélectionner une ligne à modifier avant de valider.");
		return;
	}

	// Tous les champs sont remplis, vous pouvez maintenant soumettre les modifications
	// Créer un formulaire et ajouter les données modifiées
	const form = document.createElement("form");
	form.method = "post";
	form.action = "../fonctionsPHP/ModifierVisuEtu.php";

	for (let i = 0; i < rows.length; i++) {
		if (rows[i].classList.contains("edit-mode")) {
			const cells = rows[i].querySelectorAll("td:not(:last-child)");
            const codeNip = cells[0].textContent;
            const nom = cells[1].querySelector('input').value;
            const prenom = cells[2].querySelector('input').value;
            const cursus = cells[3].querySelector('input').value;
            const parcours = cells[4].querySelector('input').value;
            const apprenti = cells[5].querySelector('input').value;
            const avisI = cells[6].querySelector('select').value; // Récupérer la valeur de la liste déroulante
            const avisM = cells[7].querySelector('select').value; // Récupérer la valeur de la liste déroulante
            const commentaire = cells[8].querySelector('input').value;
            const mobilite = cells[9].querySelector('input').value;

			const inputCodeNip = document.createElement("input");
			inputCodeNip.type = "hidden";
			inputCodeNip.name = "codeNip[]"; // Correspond à l'identifiant unique de l'étudiant
			inputCodeNip.value = codeNip;
			form.appendChild(inputCodeNip);

			const inputNom = document.createElement("input");
			inputNom.type = "hidden";
			inputNom.name = "nom[]"; // Correspond au nom de l'étudiant
			inputNom.value = nom; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputNom);

			const inputPrenom = document.createElement("input");
			inputPrenom.type = "hidden";
			inputPrenom.name = "prenom[]"; // Correspond au prénom de l'étudiant
			inputPrenom.value = prenom; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputPrenom);

			const inputCursus = document.createElement("input");
			inputCursus.type = "hidden";
			inputCursus.name = "cursus[]"; // Correspond au cursus de l'étudiant
			inputCursus.value = cursus;
			form.appendChild(inputCursus);

			const inputParcours = document.createElement("input");
			inputParcours.type = "hidden";
			inputParcours.name = "parcours[]"; // Correspond au parcours de l'étudiant
			inputParcours.value = parcours; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputParcours);

			const inputApprenti = document.createElement("input");
			inputApprenti.type = "hidden";
			inputApprenti.name = "apprenti[]"; // Correspond au statut d'apprenti de l'étudiant
			inputApprenti.value = apprenti; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputApprenti);

			const inputAvisI = document.createElement("input");
			inputAvisI.type = "hidden";
			inputAvisI.name = "avisI[]"; // Correspond à l'avis de l'enseignant I
			inputAvisI.value = avisI; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputAvisI);

			const inputAvisM = document.createElement("input");
			inputAvisM.type = "hidden";
			inputAvisM.name = "avisM[]"; // Correspond à l'avis de l'enseignant M
			inputAvisM.value = avisM; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputAvisM);

			const inputComm = document.createElement("input");
			inputComm.type = "hidden";
			inputComm.name = "commentaire[]"; // Correspond au commentaire sur l'étudiant
			inputComm.value = commentaire; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputComm);

			const inputMob = document.createElement("input");
			inputMob.type = "hidden";
			inputMob.name = "mobilite[]"; // Correspond à la mobilité de l'étudiant
			inputMob.value = mobilite; // Assurez-vous de définir la valeur appropriée ici
			form.appendChild(inputMob);
		}
	}

	// Ajouter le formulaire à la page et le soumettre
	document.body.appendChild(form);
	form.submit();
}

// Fonction pour activer/désactiver le mode d édition pour une ligne
function toggleEditMode(button) {
    const row = button.parentNode.parentNode;
    const tableBody = row.parentNode;
    const rows = tableBody.querySelectorAll("tr");

    // Si le bouton a la valeur "Valider", appeler validateChanges()
    if (button.textContent.trim() === "Valider") {
        validateChanges();
        return;
    }

    // Désactiver le mode d édition pour toutes les autres lignes
    for (let i = 0; i < rows.length; i++) {
        rows[i].classList.remove("edit-mode");
        disableEditMode(rows[i]);
    }

    row.classList.add("edit-mode");
    enableEditMode(row);
}

// Fonction pour activer le mode d'édition pour une ligne
function enableEditMode(row) {
    const cells = row.querySelectorAll("td:not(:last-child)");
    const button = row.querySelector(".edit-button");
    const cancelButton = row.querySelector(".cancel-button");

    // Stocker les valeurs d'origine des cellules
    originalCellValues = Array.from(cells).map(cell => cell.textContent);

    for (let i = 1; i < cells.length; i++) {
        // Vérifiez si la colonne est "avisInge" ou "avisMaster"
        if (i === 6 || i === 7) { // 6 pour avisInge, 7 pour avisMaster
            const text = cells[i].textContent;
            // Créer une liste déroulante avec les options spécifiées
            const selectList = document.createElement("select");
            const options = ['Tres Favorable', 'Favorable', 'Assez Favorable', 'Sans Avis', 'Reserve', ''];
            options.forEach(option => {
                const optionElement = document.createElement("option");
                optionElement.value = option;
                optionElement.text = option;
                selectList.appendChild(optionElement);
            });
            selectList.value = text; // Sélectionner la valeur actuelle
            cells[i].innerHTML = ""; // Nettoyer la cellule
            cells[i].appendChild(selectList); // Ajouter la liste déroulante
        } else {
            const text = cells[i].textContent;
            cells[i].innerHTML = "<input type='text' value='" + text + "'>";
        }
    }
    button.textContent = "Valider";
    cancelButton.style.display = "inline-block";
}

// Fonction pour désactiver le mode d'édition pour une ligne
function disableEditMode(row) {
    const cells = row.querySelectorAll("td:not(:last-child)");
    const button = row.querySelector(".edit-button");
    const cancelButton = row.querySelector(".cancel-button");

    for (let i = 1; i < cells.length; i++) {
        // Vérifiez si la colonne est "avisInge" ou "avisMaster"
        if (i === 6 || i === 7) { // 6 pour avisInge, 7 pour avisMaster
            const selectList = cells[i].querySelector("select");
            if (selectList !== null) {
                cells[i].textContent = selectList.value; // Rétablir la valeur sélectionnée
            }
        } else {
            const input = cells[i].querySelector("input[type=text]");
            if (input !== null) {
                cells[i].textContent = originalCellValues[i - 1]; // Rétablir la valeur d'origine
            }
        }
    }
    button.textContent = "Modifier";
    cancelButton.style.display = "none"; // Cacher le bouton Annuler
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