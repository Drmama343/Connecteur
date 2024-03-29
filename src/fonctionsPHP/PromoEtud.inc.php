<?php

/*classe permettant de representer les tuples de la table client */
class PromoEtud {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $anneepromo;
      private $codenip;
	
	public function __construct($n="",$i="") {
		$this->anneepromo = $n;
		$this->codenip = $i;
	}

      public function getCodeNip() { return $this->codenip; }
      public function getAnneePromo() { return $this->anneepromo;}

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}
?>
