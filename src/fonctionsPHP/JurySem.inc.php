<?php

/*classe permettant de representer les tuples de la table client */
class JurySem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codenip;
      private $idsem;
	  private $moysem;
	  private $ue;
	  private $rang;
	  private $bonus;
      
      public function __construct($c="",$id="",$mo="",$r="",$d="",$rang="") {
            $this->codenip = $c;
	      $this->idsem = $id;
		  $this->moysem = $mo;
		  $this->ue = $r;
		  $this->bonus = $d;
		  $this->rang = $rang;
      }

      public function getCode() { return $this->codenip; }
      public function getIdSem() { return $this->idsem;}
	  public function getMoySem() { return $this->moysem;}
	  public function getUE() { return $this->ue;}
	  public function getBonus() { return $this->bonus;}
	  public function getRang() { return $this->rang;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
