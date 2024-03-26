-- Table Promotion
CREATE TABLE Promotion (
    idPromo INT PRIMARY KEY,
    nbEtud INT NOT NULL
);

-- Table Etudiant
CREATE TABLE Etudiant (
    codeNip INT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    cursus VARCHAR(50) NOT NULL,
    rang INT NOT NULL,
    parcours VARCHAR(50)
);

-- Table Semestre
CREATE TABLE Semestre (
    idSem INT PRIMARY KEY,
    nomSem VARCHAR(50) NOT NULL
);

-- Table Competences
CREATE TABLE Competences (
    idComp INT PRIMARY KEY,
    nomComp VARCHAR(50) NOT NULL
);

-- Table Ressources
CREATE TABLE Ressources (
    idRess INT PRIMARY KEY,
    nomRess VARCHAR(50) NOT NULL
);

-- Table Annee
CREATE TABLE Annee (
    idAnnee INT PRIMARY KEY,
    nomAnnee VARCHAR(50) NOT NULL,
    Sem1 INT NOT NULL,
    Sem2 INT,
    FOREIGN KEY (Sem1) REFERENCES Semestre(idSem),
    FOREIGN KEY (Sem2) REFERENCES Semestre(idSem)
);

-- Table PromoEtud
CREATE TABLE PromoEtud (
    idPromo INT,
    codeNip INT,
    FOREIGN KEY (idPromo) REFERENCES Promotion(idPromo),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    PRIMARY KEY (idPromo, codeNip)
);

-- Table PromoAnnee
CREATE TABLE PromoAnnee (
    idPromo INT,
    idAnnee INT,
    FOREIGN KEY (idPromo) REFERENCES Promotion(idPromo),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee),
    PRIMARY KEY (idPromo, idAnnee)
);

-- Table CompRess
CREATE TABLE CompRess (
    idComp INT,
    idRess INT,
    coeff INT NOT NULL,
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idRess) REFERENCES Ressources(idRess),
    PRIMARY KEY (idComp, idRess)
);

-- Table CompSem
CREATE TABLE CompSem (
    idComp INT,
    idSem INT,
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem),
    PRIMARY KEY (idComp, idSem)
);

-- Table MoyRess
CREATE TABLE MoyRess (
    codeNip INT,
    idRess INT,
    moyRess FLOAT NOT NULL,
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idRess) REFERENCES Ressources(idRess),
    PRIMARY KEY (codeNip, idRess)
);

-- Table JurySem
CREATE TABLE JurySem (
    codeNip INT,
    idSem INT,
    moySem FLOAT NOT NULL,
    UE VARCHAR(50) NOT NULL,
    bonus FLOAT,
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem),
    PRIMARY KEY (codeNip, idSem)
);

-- Table JuryAnnee
CREATE TABLE JuryAnnee (
    codeNip INT,
    idAnnee INT,
    moyAnnee FLOAT,
    RCUE VARCHAR(50),
    decision VARCHAR(50),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee),
    PRIMARY KEY (codeNip, idAnnee)
);

-- Table MoyCompSem
CREATE TABLE MoyCompSem (
    codeNip INT,
    idComp INT,
    idSem INT,
    moyCompSem FLOAT NOT NULL,
    avis VARCHAR(50),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem),
    PRIMARY KEY (codeNip, idComp, idSem)
);

-- Table MoyCompAnnee
CREATE TABLE MoyCompAnnee (
    codeNip INT,
    idComp INT,
    idAnnee INT,
    moyCompAnnee FLOAT,
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee),
    PRIMARY KEY (codeNip, idComp, idAnnee)
);
