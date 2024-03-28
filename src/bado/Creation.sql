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
    idSem SERIAL PRIMARY KEY,
    nomSem VARCHAR(50)
);

-- Table Annee
CREATE TABLE Annee (
    idAnnee SERIAL PRIMARY KEY,
    nomAnnee VARCHAR(50),
    semestre1 integer,
    semestre2 integer,
    FOREIGN KEY (semestre1) REFERENCES Semestre(idSem),
    FOREIGN KEY (semestre2) REFERENCES Semestre(idSem)
);

-- Table Competences
CREATE TABLE Competence (
    idComp VARCHAR(50) PRIMARY KEY,
    nomComp VARCHAR(50)
);

-- Table Ressources
CREATE TABLE Ressource (
    idRess VARCHAR(50) PRIMARY KEY,
    nomRess VARCHAR(50)
);

-- Table Promotion
CREATE TABLE Promotion (
    anneePromo VARCHAR(50) PRIMARY KEY,
    nbEtud integer
);

-- Table Etudiant
CREATE TABLE Etudiant (
    codeNip integer PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    cursus VARCHAR(50) NOT NULL,
    parcours VARCHAR(50),
    apprentissage VARCHAR(50),
    avisInge VARCHAR(50),
    avisMaster VARCHAR(50),
    commentaire VARCHAR(50),
    absInjust integer
);

-- Table PromoEtud
CREATE TABLE PromoEtud (
    anneePromo VARCHAR(50),
    codeNip integer,
    PRIMARY KEY (anneePromo, codeNip),
    FOREIGN KEY (anneePromo) REFERENCES Promotion(anneePromo),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip)
);

-- Table Coeff
CREATE TABLE Coeff (
    idComp VARCHAR(50),
    idRess VARCHAR(50),
    coeff integer,
    PRIMARY KEY (idComp, idRess),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idRess) REFERENCES Ressource(idRess)
);

-- Table CompSem
CREATE TABLE CompSem (
    idComp VARCHAR(50),
    idSem integer,
    PRIMARY KEY (idComp, idSem),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table MoyRess
CREATE TABLE MoyRess (
    codeNip integer,
    idRess VARCHAR(50),
    moyRess FLOAT,
    PRIMARY KEY (codeNip, idRess),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idRess) REFERENCES Ressource(idRess)
);

-- Table JurySem
CREATE TABLE JurySem (
    codeNip integer,
    idSem integer,
    moySem FLOAT,
    UE VARCHAR(50),
    bonus FLOAT,
    rang integer,
    PRIMARY KEY (codeNip, idSem),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table JuryAnnee
CREATE TABLE JuryAnnee (
    codeNip integer,
    idAnnee integer,
    moyAnnee FLOAT,
    RCUE VARCHAR(50),
    decision VARCHAR(50),
    rang integer,
    PRIMARY KEY (codeNip, idAnnee),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);

-- Table MoyCompSem
CREATE TABLE MoyCompSem (
    codeNip integer,
    idComp VARCHAR(50),
    idSem integer,
    moyCompSem FLOAT,
    avis VARCHAR(50),
    PRIMARY KEY (codeNip, idComp, idSem),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem)
);

-- Table MoyCompAnnee
CREATE TABLE MoyCompAnnee (
    codeNip integer,
    idComp VARCHAR(50),
    idAnnee integer,
    moyCompAnnee FLOAT,
    avis VARCHAR(50),
    PRIMARY KEY (codeNip, idComp, idAnnee),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idComp) REFERENCES Competence(idComp),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);


