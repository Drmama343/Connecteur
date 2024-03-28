<?php

/*classe permettant de representer les tuples de la table client */
class JuryAnnee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idAnnee;
	  private $moyAnnee;
	  private $RCUE;
	  private $decision;
      
      public function __construct($c="",$id="",$mo="",$r="",$d="") {
            $this->codeNip = $c;
	      $this->idAnnee = $id;
		  $this->moyAnnee = $mo;
		  $this->RCUE = $r;
		  $this->decision = $d;
      }

      public function getCode() { return $this->codeNip; }
      public function getIdAnnee() { return $this->idAnnee;}
	  public function getMoyAnnee() { return $this->moyAnnee;}
	  public function getRCUE() { return $this->RCUE;}
	  public function getDecision() { return $this->decision;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
