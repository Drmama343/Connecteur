DROP TABLE IF EXISTS MoyCompAnnee CASCADE;
DROP TABLE IF EXISTS MoyCompSem CASCADE;
DROP TABLE IF EXISTS JuryAnnee CASCADE;
DROP TABLE IF EXISTS JurySem CASCADE;
DROP TABLE IF EXISTS MoyRess CASCADE;
DROP TABLE IF EXISTS CompSem CASCADE;
DROP TABLE IF EXISTS Coeff CASCADE;
DROP TABLE IF EXISTS PromoEtud CASCADE;
DROP TABLE IF EXISTS Etudiant CASCADE;
DROP TABLE IF EXISTS Promotion CASCADE;
DROP TABLE IF EXISTS Ressource CASCADE;
DROP TABLE IF EXISTS Competence CASCADE;
DROP TABLE IF EXISTS Annee CASCADE;
DROP TABLE IF EXISTS Semestre CASCADE;

-- Table Semestre
CREATE TABLE Semestre (
    idSem INT PRIMARY KEY,
    nomSem VARCHAR(50)
);

-- Table Annee
CREATE TABLE Annee (
    nomAnnee VARCHAR(50) PRIMARY KEY CHECK (nomAnnee IN ('BUT1', 'BUT2', 'BUT3')),
    semestre1 INT,
    semestre2 INT,
    FOREIGN KEY (semestre1) REFERENCES Semestre(idSem),
    FOREIGN KEY (semestre2) REFERENCES Semestre(idSem)
);

-- Table Competences
CREATE TABLE Competence (
    idComp VARCHAR(50) PRIMARY KEY,
    nomComp VARCHAR(150)
);

-- Table Ressources
CREATE TABLE Ressource (
    idRess VARCHAR(50) PRIMARY KEY,
    nomRess VARCHAR(150)
);

-- Table Promotion
CREATE TABLE Promotion (
    anneePromo VARCHAR(50) PRIMARY KEY
);

-- Table Etudiant
CREATE TABLE Etudiant (
    codeNip INT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    cursus VARCHAR(50) NOT NULL,
    parcours VARCHAR(50),
    apprentissage VARCHAR(50),
    avisInge VARCHAR(50),
    avisMaster VARCHAR(50),
    commentaire VARCHAR(50),
    mobEtrang VARCHAR(150)
);

-- Table PromoEtud
CREATE TABLE PromoEtud (
    anneePromo VARCHAR(50),
    codeNip INT,
    PRIMARY KEY (anneePromo, codeNip),
    FOREIGN KEY (anneePromo) REFERENCES Promotion(anneePromo),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip)
);

-- Table Coeff
CREATE TABLE Coeff (
    idComp VARCHAR(50),
    idRess VARCHAR(50),
    coeff INT,
    PRIMARY KEY (idComp, idRess),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idRess) REFERENCES Ressource(idRess)
);

-- Table CompSem
CREATE TABLE CompSem (
    idComp VARCHAR(50),
    idSem INT,
    PRIMARY KEY (idComp, idSem),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table MoyRess
CREATE TABLE MoyRess (
    codeNip INT,
    idRess VARCHAR(50),
    moyRess FLOAT,
    PRIMARY KEY (codeNip, idRess),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idRess) REFERENCES Ressource(idRess)
);

-- Table JurySem
CREATE TABLE JurySem (
    codeNip INT,
    idSem INT,
    moySem FLOAT,
    UE VARCHAR(50),
    bonus FLOAT,
    rang INT,
    PRIMARY KEY (codeNip, idSem),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table JuryAnnee
CREATE TABLE JuryAnnee (
    codeNip INT,
    nomAnnee VARCHAR(50),
    moyAnnee FLOAT,
    RCUE VARCHAR(50),
    decision VARCHAR(50),
    rang INT,
    anneePromo VARCHAR(50),
    absInjust INT,
    PRIMARY KEY (codeNip, nomAnnee),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (nomAnnee) REFERENCES Annee(nomAnnee),
    FOREIGN KEY (anneePromo) REFERENCES Promotion(anneePromo)
);

-- Table MoyCompSem
CREATE TABLE MoyCompSem (
    codeNip INT,
    idComp VARCHAR(50),
    idSem INT,
    moyCompSem FLOAT,
    avis VARCHAR(50),
    PRIMARY KEY (codeNip, idComp, idSem),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table MoyCompAnnee
CREATE TABLE MoyCompAnnee (
    codeNip INT,
    idComp VARCHAR(50),
    nomAnnee VARCHAR(50),
    moyCompAnnee FLOAT,
    avis VARCHAR(50),
	rang INT,
    PRIMARY KEY (codeNip, idComp, nomAnnee),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (nomAnnee) REFERENCES Annee(nomAnnee)
);
