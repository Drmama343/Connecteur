<?php

/*classe permettant de representer les tuples de la table client */
class Etudiant {
    /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
    private $codeNip;
    private $nom;
    private $prenom;
    private $cursus;
    private $parcours;
    private $apprentissage;
    private $avisInge;
    private $avisMaster;
    private $commentaire;
    private $absInjust;
    
    public function __construct($i=-1,$n="",$pr="",$c="",$pa="",$ap="",$ai="",$am="",$com="",$abs=-1) {
        $this->codeNip = $i;
        $this->nom = $n;
        $this->prenom = $pr;
        $this->cursus = $c;
        $this->parcours = $pa;
        $this->apprentissage = $ap;
        $this->avisInge = $ai;
        $this->avisMaster = $am;
        $this->commentaire = $com;
        $this->absInjust = $abs;
    }

    public function getCode() { return $this->codeNip; }
    public function getNom() { return $this->nom;}
    public function getPrenom() { return $this->prenom; }
    public function getCursus() { return $this->cursus;}
    public function getParcours() { return $this->parcours;}
    public function getApprentissage() { return $this->apprentissage; }
    public function getAvisInge() { return $this->avisInge;}
    public function getAvisMaster() { return $this->avisMaster;}
    public function getCommentaire() { return $this->commentaire;}
    public function getAbsInjust() { return $this->absInjust;}

    public function __toString() {
        $res = "" . $this->codeNip;
        return $res;
        
    }
}
?>
