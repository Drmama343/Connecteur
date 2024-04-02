<?php

/*classe permettant de representer les tuples de la table client */
class MoyCompSem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codenip;
      private $idcomp;
      private $idsem;
	  private $moycompsem;
	  private $avis;
	
	public function __construct($i="",$n="",$a="",$m="",$av="") {
		$this->codenip = $i;
		$this->idcomp = $n;
		$this->idsem = $a;
		$this->moycompsem = $m;
		$this->avis = $av;
	}

      public function getCodeNip() { return $this->codenip; }
      public function getIdComp() { return $this->idcomp;}
      public function getIdSem() { return $this->idsem; }
	  public function getMoyCompSem() { return $this->moycompsem; }
	  public function getAvis() { return $this->avis; }

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}
?>
