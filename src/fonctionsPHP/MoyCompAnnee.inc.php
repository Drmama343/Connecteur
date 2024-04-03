<?php

/*classe permettant de representer les tuples de la table client */
class MoyCompAnnee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codenip;
      private $numcomp;
      private $nomannee;
	  private $moycompannee;
	  private $avis;
	  private $rang;
	
	public function __construct($i="",$n="",$a="",$m="",$av="",$ra=0) {
		$this->codenip = $i;
		$this->numcomp = $n;
		$this->nomannee = $a;
		$this->moycompannee = $m;
		$this->avis = $av;
		$this->rang = $ra;
	}

      public function getCodeNip() { return $this->codenip; }
      public function getNumComp() { return $this->numcomp;}
      public function getNomAnnee() { return $this->nomannee; }
	  public function getMoyCompAnnee() { return $this->moycompannee; }
	  public function getAvis() { return $this->avis; }
	  public function getRang() { return $this->rang; }

      public function __toString() {
		$res = "<br/>";
		return $res;
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
