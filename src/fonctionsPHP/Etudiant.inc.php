<?php
class Etudiant {
    private int $codeNip;
    private string $nom;
    private string $prenom;
    private string $cursus;
    private ?string $parcours;
    private ?string $apprentissage;
    private ?string $avisInge;
    private ?string $avisMaster;
    private ?string $commentaire;
    private int $absInjust;
    
    public function __construct(int $codeNip=2, string $nom = "", string $prenom = "", string $cursus = "", ?string $parcours = null, ?string $apprentissage = null, ?string $avisInge = null, ?string $avisMaster = null, int $absInjust = 0, ?string $commentaire = null) {
        $this->codeNip = $codeNip;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->cursus = $cursus;
        $this->parcours = $parcours;
        $this->apprentissage = $apprentissage;
        $this->avisInge = $avisInge;
        $this->avisMaster = $avisMaster;
        $this->commentaire = $commentaire;
        $this->absInjust = $absInjust;
    }

    // Getters
    public function getCode(): int { return $this->codeNip; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getCursus(): string { return $this->cursus; }
    public function getParcours(): ?string { return $this->parcours; }
    public function getApprentissage(): ?string { return $this->apprentissage; }
    public function getAvisInge(): ?string { return $this->avisInge; }
    public function getAvisMaster(): ?string { return $this->avisMaster; }
    public function getCommentaire(): ?string { return $this->commentaire; }
    public function getAbsInjust(): int { return $this->absInjust; }

    public function __toString(): string {
        return "<br/>";
    }
}
?>
