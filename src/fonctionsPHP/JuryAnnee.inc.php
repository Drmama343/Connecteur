<?php

/*classe permettant de representer les tuples de la table client */
class JuryAnnee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codenip;
	  private $anneepromo;
      private $nomannee;
	  private $moyannee;
	  private $rcue;
	  private $decision;
	  private $rang;
	  private $absinjust;
      
      public function __construct($c="",$anneepromo="",$nom="",$mo="",$r="",$d="",$rang="", $absinjust=0) {
			$this->codenip = $c;
			$this->anneepromo = $anneepromo;
			$this->nomannee = $nom;
			$this->moyannee = $mo;
			$this->rcue = $r;
			$this->decision = $d;
			$this->rang = $rang;
			$this->absinjust = $absinjust;
      }

      public function getCode() { return $this->codenip; }
      public function getNomAnnee() { return $this->nomannee;}
	  public function getMoyAnnee() { return $this->moyannee;}
	  public function getRCUE() { return $this->rcue;}
	  public function getDecision() { return $this->decision;}
	  public function getRang() { return $this->rang;}
	  public function getAnneePromo() { return $this->anneepromo;}
	  public function getAbsInjust() { return $this->absinjust;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
