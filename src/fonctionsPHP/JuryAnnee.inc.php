<?php

/*classe permettant de representer les tuples de la table client */
class JuryAnnee {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idAnnee;
	  private $moyAnnee;
	  private $RCUE;
	  private $decision;
	  private $rang;
      
      public function __construct($c="",$id="",$mo="",$r="",$d="",$rang="") {
            $this->codeNip = $c;
	      $this->idAnnee = $id;
		  $this->moyAnnee = $mo;
		  $this->RCUE = $r;
		  $this->decision = $d;
		  $this->rang = $rang;
      }

      public function getCode() { return $this->codeNip; }
      public function getIdAnnee() { return $this->idAnnee;}
	  public function getMoyAnnee() { return $this->moyAnnee;}
	  public function getRCUE() { return $this->RCUE;}
	  public function getDecision() { return $this->decision;}
	  public function getRang() { return $this->rang;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
