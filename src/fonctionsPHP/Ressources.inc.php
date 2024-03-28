<?php

/*classe permettant de representer les tuples de la table client */
class Ressources {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idRess;
      private $nomRess;
	
	public function __construct($i="",$n="",$a="") {
		$this->idRess = $i;
		$this->nomRess = $n;
	}

      public function getIdRess() { return $this->idRess; }
      public function getNomRess() { return $this->nomRess;}

      public function __toString() {
		$res = "idRess:".$this->idRess."\n";
		$res = $res ."nomRess:".$this->nomRess."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
