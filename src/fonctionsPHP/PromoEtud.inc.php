<?php

/*classe permettant de representer les tuples de la table client */
class PromoEtud {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $anneePromo;
      private $codeNip;
	
	public function __construct($n="",$i="") {
		$this->anneePromo = $n;
		$this->codeNip = $i;
	}

      public function getCodeNip() { return $this->codeNip; }
      public function getAnneePromo() { return $this->anneePromo;}

      public function __toString() {
		$res = "codeNip:".$this->codeNip."\n";
		$res = $res ."anneePromo:".$this->anneePromo."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
