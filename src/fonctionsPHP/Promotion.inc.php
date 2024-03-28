<?php

/*classe permettant de representer les tuples de la table client */
class Promotion {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $anneePromo;
      private $nbEtud;
	
	public function __construct($i="",$n="") {
		$this->anneePromo = $i;
		$this->nbEtud = $n;
	}

      public function getAnneePromo() { return $this->anneePromo; }
      public function getNbEtud() { return $this->nbEtud;}

      public function __toString() {
		$res = "anneePromo:".$this->anneePromo."\n";
		$res = $res ."nbEtud:".$this->nbEtud."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
