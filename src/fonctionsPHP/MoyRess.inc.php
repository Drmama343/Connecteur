<?php

/*classe permettant de representer les tuples de la table client */
class MoyRess {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codenip;
      private $idress;
	  private $moyress;
	
	public function __construct($i="",$n="",$a="",$m="") {
		$this->codenip = $i;
		$this->idress = $n;
		$this->moyress = $a;
	}

      public function getCodeNip() { return $this->codenip; }
      public function getIdRess() { return $this->idress;}
	  public function getMoyRess() { return $this->moyress; }

      public function __toString() {
		$res = "<br/>";
		return $res;
	     
      }
}
?>
