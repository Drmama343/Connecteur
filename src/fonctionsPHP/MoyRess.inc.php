<?php

/*classe permettant de representer les tuples de la table client */
class MoyRess {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $codeNip;
      private $idRess;
	  private $moyRess;
	
	public function __construct($i="",$n="",$a="",$m="") {
		$this->codeNip = $i;
		$this->idRess = $n;
		$this->moyRess = $a;
	}

      public function getCodeNip() { return $this->codeNip; }
      public function getIdRess() { return $this->idRess;}
	  public function getMoyRess() { return $this->moyRess; }

      public function __toString() {
		$res = "codeNip:".$this->codeNip."\n";
		$res = $res ."idRess:".$this->idRess."\n";
		$res = $res ."moyRess:".$this->moyRess."\n";
		$res = $res ."<br/>";
		return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
