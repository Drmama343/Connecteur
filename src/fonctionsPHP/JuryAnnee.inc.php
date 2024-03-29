<?php

/*classe permettant de representer les tuples de la table client */
class JuryAnnee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codenip;
      private $idannee;
	  private $moyannee;
	  private $rcue;
	  private $decision;
	  private $rang;
	  private $anneePromo;
      
      public function __construct($c="",$id="",$mo="",$r="",$d="",$rang="") {
            $this->codenip = $c;
	      $this->idannee = $id;
		  $this->moyannee = $mo;
		  $this->rcue = $r;
		  $this->decision = $d;
		  $this->rang = $rang;
		  $this->anneePromo = $anneePromo;
      }

      public function getCode() { return $this->codenip; }
      public function getIdAnnee() { return $this->idannee;}
	  public function getMoyAnnee() { return $this->moyannee;}
	  public function getRCUE() { return $this->rcue;}
	  public function getDecision() { return $this->decision;}
	  public function getRang() { return $this->rang;}
	  public function getAnneePromo() { return $this->anneePromo;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
