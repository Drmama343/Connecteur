<?php

/*classe permettant de representer les tuples de la table client */
class Test {
      /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
      private $nom;
      private $prenom;
      private $moyenne;
      
      public function __construct($i="",$n="",$a="") {
            $this->nom = $i;
	      $this->prenom = $n;
	      $this->moyenne = $a;
      }

      public function getNom() { return $this->nom; }
      public function getPrenom() { return $this->prenom;}
      public function getMoy() { return $this->moyenne; }

      public function __toString() {
      	     $res = "nom:".$this->ncli."\n";
	     $res = $res ."prenom:".$this->np."\n";
	     $res = $res ."moyenne:".$this->qa."\n";
	     $res = $res ."<br/>";
	     return $res;
	     
      }
}

//test
//$unclient = new Client(5,'Dupont','Le Havre');
//echo $unclient;
?>
