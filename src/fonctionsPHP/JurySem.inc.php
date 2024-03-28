<?php

/*classe permettant de representer les tuples de la table client */
class JurySem {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idSem;
	  private $moySem;
	  private $UE;
	  private $rang;
	  private $bonus;
      
      public function __construct($c="",$id="",$mo="",$r="",$d="",$rang="") {
            $this->codeNip = $c;
	      $this->idSem = $id;
		  $this->moySem = $mo;
		  $this->UE = $r;
		  $this->bonus = $d;
		  $this->rang = $rang;
      }

      public function getCode() { return $this->codeNip; }
      public function getIdSem() { return $this->idSem;}
	  public function getMoySem() { return $this->moySem;}
	  public function getUE() { return $this->UE;}
	  public function getBonus() { return $this->bonus;}
	  public function getRang() { return $this->rang;}

      public function __toString() {
	     $res = "<br/>";
	     return $res;
	     
      }
}
?>
