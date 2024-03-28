<?php

/*classe permettant de representer les tuples de la table client */
class MoyCompSem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idComp;
      private $idSem;
	  private $moyCompSem;
	  private $avis;
	
	public function __construct($i="",$n="",$a="",$m="",$av="") {
		$this->codeNip = $i;
		$this->idComp = $n;
		$this->idSem = $a;
		$this->moyCompSem = $m;
		$this->avis = $av;
	}

      public function getCodeNip() { return $this->codeNip; }
      public function getIdComp() { return $this->idComp;}
      public function getIdSem() { return $this->idSem; }
	  public function getMoyCompSem() { return $this->moyCompSem; }
	  public function getAvis() { return $this->avis; }

      public function __toString() {
		$res = "codeNip:".$this->codeNip."\n";
		$res = $res ."idComp:".$this->idComp."\n";
		$res = $res ."idSem:".$this->idSem."\n";
		$res = $res ."moyCompSem:".$this->moyCompSem."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
