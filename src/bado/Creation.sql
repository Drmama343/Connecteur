DROP TABLE IF EXISTS MoyCompAnnee;
DROP TABLE IF EXISTS MoyCompSem;
DROP TABLE IF EXISTS JuryAnnee;
DROP TABLE IF EXISTS JurySem;
DROP TABLE IF EXISTS MoyRess;
DROP TABLE IF EXISTS CompSem;
DROP TABLE IF EXISTS CompRess;
DROP TABLE IF EXISTS PromoEtud;
DROP TABLE IF EXISTS Etudiant;
DROP TABLE IF EXISTS Promotion;
DROP TABLE IF EXISTS Ressources;
DROP TABLE IF EXISTS Competences;
DROP TABLE IF EXISTS Semestre;
DROP TABLE IF EXISTS Annee;

-- Table Annee
CREATE TABLE Annee (
    idAnnee INT PRIMARY KEY,
    nomAnnee VARCHAR(50)
);

-- Table Semestre
CREATE TABLE Semestre (
    idSem INT PRIMARY KEY,
    nomSem VARCHAR(50),
    idAnnee INT,
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);

-- Table Competences
CREATE TABLE Competences (
    idComp INT PRIMARY KEY,
    nomComp VARCHAR(50)
);

-- Table Ressources
CREATE TABLE Ressources (
    idRess INT PRIMARY KEY,
    nomRess VARCHAR(50)
);

-- Table Promotion
CREATE TABLE Promotion (
    anneePromo VARCHAR(50),
    nbEtud INT,
    idAnnee INT,
    PRIMARY KEY (anneePromo, idAnnee),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);

-- Table Etudiant
CREATE TABLE Etudiant (
    codeNip INT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    cursus VARCHAR(50) NOT NULL,
    rang INT NOT NULL,
    parcours VARCHAR(50),
    apprentissage VARCHAR(50),
    avisInge VARCHAR(50),
    avisMaster VARCHAR(50),
    commentaire VARCHAR(50)
);

-- Table PromoEtud
CREATE TABLE PromoEtud (
    anneePromo VARCHAR(50),
    codeNip INT,
    PRIMARY KEY (anneePromo, codeNip),
    FOREIGN KEY (anneePromo) REFERENCES Promotion(anneePromo),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip)
);

-- Table CompRess
CREATE TABLE CompRess (
    idComp INT,
    idRess INT,
    coeff INT,
    PRIMARY KEY (idComp, idRess),
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idRess) REFERENCES Ressources(idRess)
);

-- Table CompSem
CREATE TABLE CompSem (
    idComp INT,
    idSem INT,
    PRIMARY KEY (idComp, idSem),
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table MoyRess
CREATE TABLE MoyRess (
    codeNip INT,
    idRess INT,
    moyRess FLOAT,
    PRIMARY KEY (codeNip, idRess),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idRess) REFERENCES Ressources(idRess)
);

-- Table JurySem
CREATE TABLE JurySem (
    codeNip INT,
    idSem INT,
    moySem FLOAT,
    UE VARCHAR(50),
    bonus FLOAT,
    PRIMARY KEY (codeNip, idSem),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table JuryAnnee
CREATE TABLE JuryAnnee (
    codeNip INT,
    idAnnee INT,
    moyAnnee FLOAT,
    RCUE VARCHAR(50),
    decision VARCHAR(50),
    PRIMARY KEY (codeNip, idAnnee),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);

-- Table MoyCompSem
CREATE TABLE MoyCompSem (
    codeNip INT,
    idComp INT,
    idSem INT,
    moyCompSem FLOAT,
    avis VARCHAR(50),
    PRIMARY KEY (codeNip, idComp, idSem),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table MoyCompAnnee
CREATE TABLE MoyCompAnnee (
    codeNip INT,
    idComp INT,
    idAnnee INT,
    moyCompAnnee FLOAT,
    PRIMARY KEY (codeNip, idComp, idAnnee),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);
