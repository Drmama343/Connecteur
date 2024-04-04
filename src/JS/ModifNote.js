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

window.addEventListener('scroll', function() {
    var table = document.getElementById('etudiantsTable');
    var tableHeader = document.getElementById('tableHeader');
    if (table && tableHeader) {
        tableHeader.style.transform = 'translateY(' + window.pageYOffset + 'px)';
    }
});

window.addEventListener('scroll', function() {
    var table = document.getElementById('etudiantsTable');
    var colonne = document.getElementById('code');
    if (table && colonne) {
        colonne.style.transform = 'translateX(' + window.pageXOffset + 'px)';
    }
});