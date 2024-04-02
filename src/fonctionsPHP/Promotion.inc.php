<?php

/*classe permettant de representer les tuples de la table client */
class Promotion {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $anneepromo;
      private $nbetud;
	
	public function __construct($i="",$n="") {
		$this->anneepromo = $i;
		$this->nbetud = $n;
	}

      public function getAnneePromo() { return $this->anneepromo; }
      public function getNbEtud() { return $this->nbetud;}

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}
?>
