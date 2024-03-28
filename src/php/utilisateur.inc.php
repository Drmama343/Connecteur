<?php
	class Utilisateur {
		public $nom;
		public $droitAcces;

		// Constructeur pour initialiser l'objet Utilisateur
		public function __construct($nom, $droitAcces) {
			$this->nom = $nom;
			$this->droitAcces = $droitAcces;
		}

		// Getter pour le nom
		public function getNom() {
			return $this->nom;
		}

		// Getter pour le droit d'accès
		public function getDroitAcces() {
			return $this->droitAcces;
		}

		// Vérifie si le nom d'utilisateur et le mot de passe sont corrects
		public static function verification($nom, $pass) {
			if ((($nom == 'admin') && ($pass == 'admpwd')) ||
				(($nom == 'user') && ($pass == 'usrpwd'))) {
				return true;
			} else {
				return false;
			}
		}

		// Obtient le niveau de droit en fonction du nom d'utilisateur
		public static function niveauDroit($nom) {
			if ($nom == 'user') return 1; // droit de consultation
			if ($nom == 'admin') return 2; // droit de modification
			return 0; // aucun droit
		}
	}
?>