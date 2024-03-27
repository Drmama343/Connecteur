-- Suppression des tables liées aux clés étrangères avec CASCADE
DROP TABLE IF EXISTS MoyCompAnnee CASCADE;
DROP TABLE IF EXISTS MoyCompSem CASCADE;
DROP TABLE IF EXISTS JurySem CASCADE;
DROP TABLE IF EXISTS JuryAnnee CASCADE;
DROP TABLE IF EXISTS MoyRess CASCADE;
DROP TABLE IF EXISTS CompRess CASCADE;
DROP TABLE IF EXISTS CompSem CASCADE;
DROP TABLE IF EXISTS PromoEtud CASCADE;
DROP TABLE IF EXISTS PromoAnnee CASCADE;
DROP TABLE IF EXISTS Promotion CASCADAE;
DROP TABLE IF EXISTS Annee CASCADE;
DROP TABLE IF EXISTS Ressources CASCADE;
DROP TABLE IF EXISTS Competences CASCADE;
DROP TABLE IF EXISTS Etudiant CASCADE;
DROP TABLE IF EXISTS Semestre CASCADE;


-- Création des tables
CREATE TABLE Semestre (
    idSem SERIAL PRIMARY KEY,
    nomSem VARCHAR(50) NOT NULL
);

CREATE TABLE Competences (
    idComp SERIAL PRIMARY KEY,
    nomComp VARCHAR(50) NOT NULL
);

CREATE TABLE Ressources (
    idRess SERIAL PRIMARY KEY,
    nomRess VARCHAR(50) NOT NULL
);

CREATE TABLE Annee (
    idAnnee SERIAL PRIMARY KEY,
    nomAnnee VARCHAR(50) NOT NULL,
    numAnnee VARCHAR(50) NOT NULL,
    Sem1 INT NOT NULL,
    Sem2 INT,
    FOREIGN KEY (Sem1) REFERENCES Semestre(idSem),
    FOREIGN KEY (Sem2) REFERENCES Semestre(idSem)
);

CREATE TABLE Etudiant (
    codeNip INT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    cursus VARCHAR(50) NOT NULL,
    rang INT NOT NULL,
    parcours VARCHAR(50) NOT NULL
);

CREATE TABLE Promotion (
    idPromo VARCHAR(50) PRIMARY KEY,
    nbEtud INT NOT NULL,
    idAnnee INT NOT NULL,
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee)
);

CREATE TABLE PromoEtud (
    idPromo VARCHAR(50),
    codeNip INT,
    FOREIGN KEY (idPromo) REFERENCES Promotion(idPromo),
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    PRIMARY KEY (idPromo, codeNip)
);

CREATE TABLE PromoAnnee (
    idPromo VARCHAR(50),
    idAnnee INT,
    FOREIGN KEY (idPromo) REFERENCES Promotion(idPromo),
    FOREIGN KEY (idAnnee) REFERENCES Annee(idAnnee),
    PRIMARY KEY (idPromo, idAnnee)
);

CREATE TABLE CompRess (
    idComp INT,
    idRess INT,
    coeff INT NOT NULL,
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idRess) REFERENCES Ressources(idRess),
    PRIMARY KEY (idComp, idRess)
);

CREATE TABLE CompSem (
    idComp INT,
    idSem INT,
    FOREIGN KEY (idComp) REFERENCES Competences(idComp),
    FOREIGN KEY (idSem) REFERENCES Semestre(idSem),
    PRIMARY KEY (idComp, idSem)
);

CREATE TABLE MoyRess (
    codeNip INT,
    idRess INT,
    moyRess FLOAT NOT NULL,
    FOREIGN KEY (codeNip) REFERENCES Etudiant(codeNip),
    FOREIGN KEY (idRess) REFERENCES Ressources(idRess),
    PRIMARY KEY (codeNip, idRess)
);

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
