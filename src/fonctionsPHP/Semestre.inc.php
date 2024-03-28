<?php

/*classe permettant de representer les tuples de la table client */
class Semestre {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $idSem;
      private $nomSem;
	  private $idAnnee;
	
	public function __construct($i="",$n="",$a="") {
		$this->idSem = $i;
		$this->nomSem = $n;
		$this->idAnnee = $a;
	}

      public function getIdSem() { return $this->idSem; }
      public function getNomSem() { return $this->nomSem;}
	  public function getIdAnnee() { return $this->idAnnee; }

      public function __toString() {
		$res = "idSem:".$this->idSem."\n";
		$res = $res ."nomSem:".$this->nomSem."\n";
		$res = $res ."idAnnee:".$this->idAnnee."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
