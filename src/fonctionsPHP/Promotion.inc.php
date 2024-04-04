<?php

/*classe permettant de representer les tuples de la table client */
class Promotion {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $anneepromo;
	
	public function __construct($i="") {
		$this->anneepromo = $i;
	}

      public function getAnneePromo() { return $this->anneepromo; }

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}
?>
