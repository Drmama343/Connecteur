<?php

/*classe permettant de representer les tuples de la table client */
class MoyCompAnnee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idComp;
      private $idAnnee;
	  private $moyCompAnnee;
	  private $avis;
	
	public function __construct($i="",$n="",$a="",$m="",$av="") {
		$this->codeNip = $i;
		$this->idComp = $n;
		$this->idAnnee = $a;
		$this->moyCompAnnee = $m;
		$this->avis = $av;
	}

      public function getCodeNip() { return $this->codeNip; }
      public function getIdComp() { return $this->idComp;}
      public function getIdAnnee() { return $this->idAnnee; }
	  public function getMoyCompAnnee() { return $this->moyCompAnnee; }
	  public function getAvis() { return $this->avis; }

      public function __toString() {
		$res = "codeNip:".$this->codeNip."\n";
		$res = $res ."idComp:".$this->idComp."\n";
		$res = $res ."idAnnee:".$this->idAnnee."\n";
		$res = $res ."moyCompAnnee:".$this->moyCompAnnee."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
