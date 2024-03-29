<?php

/*classe permettant de representer les tuples de la table client */
class Etudiant {
    /*avec PDO, il faut que les noms attributs soient les mêmes que ceux de la table*/
    private $codenip;
    private $nom;
    private $prenom;
    private $cursus;
    private $parcours;
    private $apprentissage;
    private $avisinge;
    private $avismaster;
    private $commentaire;
    private $absinjust;
    
    public function __construct($i=-1,$n="",$pr="",$c="",$pa="",$ap="",$ai="",$am="",$com="",$abs=-1) {
        $this->codenip = $i;
        $this->nom = $n;
        $this->prenom = $pr;
        $this->cursus = $c;
        $this->parcours = $pa;
        $this->apprentissage = $ap;
        $this->avisinge = $ai;
        $this->avismaster = $am;
        $this->commentaire = $com;
        $this->absinjust = $abs;
    }

    public function getCode() { return $this->codenip; }
    public function getNom() { return $this->nom;}
    public function getPrenom() { return $this->prenom; }
    public function getCursus() { return $this->cursus;}
    public function getParcours() { return $this->parcours;}
    public function getApprentissage() { return $this->apprentissage; }
    public function getAvisInge() { return $this->avisinge;}
    public function getAvisMaster() { return $this->avismaster;}
    public function getCommentaire() { return $this->commentaire;}
    public function getAbsInjust() { return $this->absinjust;}

    public function __toString() {
        $res = "Code NIP: " . $this->codenip . "\n";
        $res .= "Nom: " . $this->nom . "\n";
        $res .= "Prénom: " . $this->prenom . "\n";
        $res .= "Cursus: " . $this->cursus . "\n";
        $res .= "Parcours: " . $this->parcours . "\n";
        $res .= "Apprentissage: " . $this->apprentissage . "\n";
        $res .= "Avis Ingénieur: " . $this->avisinge . "\n";
        $res .= "Avis Master: " . $this->avismaster . "\n";
        $res .= "Commentaire: " . $this->commentaire . "\n";
        $res .= "Absences Injustifiées: " . $this->absinjust . "\n";
        return $res;
    }    
}
?>
