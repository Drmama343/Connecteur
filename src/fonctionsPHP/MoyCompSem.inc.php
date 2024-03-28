<?php

/*classe permettant de representer les tuples de la table client */
class moyCompSem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idComp;
      private $idAnnee;
	  private $moyCompSem;
	
	public function __construct($i="",$n="",$a="",$m="") {
		$this->codeNip = $i;
		$this->idComp = $n;
		$this->idAnnee = $a;
		$this->moyCompSem = $m;
	}

      public function getCodeNip() { return $this->codeNip; }
      public function getIdComp() { return $this->idComp;}
      public function getIdAnnee() { return $this->idAnnee; }
	  public function getMoyCompSem() { return $this->moyCompSem; }

      public function __toString() {
		$res = "codeNip:".$this->codeNip."\n";
		$res = $res ."idComp:".$this->idComp."\n";
		$res = $res ."idAnnee:".$this->idAnnee."\n";
		$res = $res ."moyCompSem:".$this->moyCompSem."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
