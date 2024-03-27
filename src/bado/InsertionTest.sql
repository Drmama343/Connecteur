-- Insertion de données dans la table Etudiant
INSERT INTO Etudiant (codeNip, nom, prenom, cursus, rang, parcours) VALUES
(1, 'Dupont', 'Jean', 'Développement d''applications', 1, 's1 s2 s3 s4'),
(2, 'Martin', 'Marie', 'Développement d''applications', 2, 's1 s2 s1 s2 s3 s4'),
(3, 'Dubois', 'Pierre', 'Développement d''applications', 1, 's1 s2 s3');

-- Insertion de données dans la table Semestre
INSERT INTO Semestre (nomSem) VALUES
('s1'),
('s2'),
('s3'),
('s4');

-- Insertion de données dans la table Competences
INSERT INTO Competences (nomComp) VALUES
('Compétence 1'),
('Compétence 2'),
('Compétence 3'),
('Compétence 4'),
('Compétence 5'),
('Compétence 6');

-- Insertion de données dans la table Ressources
INSERT INTO Ressources (nomRess) VALUES
('Ressource 1'),
('Ressource 2'),
('Ressource 3'),
('Ressource 4'),
('Ressource 5'),
('Ressource 6');

-- Insertion de données dans la table Annee
INSERT INTO Annee (nomAnnee, Sem1, Sem2) VALUES
('Année 1', 1, 2),
('Année 2', 3, 4),
('Année 3', 1, 2);

-- Insertion de données dans la table Promotion
INSERT INTO Promotion (idPromo, nbEtud, idAnnee) VALUES
('Promo A', 100, 1),
('Promo B', 120, 2),
('Promo C', 90, 3);

-- Insertion de données dans la table PromoEtud
INSERT INTO PromoEtud (idPromo, codeNip) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 1),
(3, 2),
(3, 3);

-- Insertion de données dans la table PromoAnnee
INSERT INTO PromoAnnee (idPromo, idAnnee) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 3),
(3, 1);

-- Insertion de données dans la table CompRess
INSERT INTO CompRess (idComp, idRess, coeff) VALUES
(1, 1, 5),
(1, 2, 3),
(2, 2, 4),
(3, 3, 5),
(3, 1, 2);

-- Insertion de données dans la table CompSem
INSERT INTO CompSem (idComp, idSem) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 3),
(3, 1);

-- Insertion de données dans la table MoyRess
INSERT INTO MoyRess (codeNip, idRess, moyRess) VALUES
(1, 1, 12.5),
(1, 2, 15.75),
(2, 3, 14.25),
(2, 1, 10.5),
(3, 2, 13.75),
(3, 3, 11.8);

-- Insertion de données dans la table JurySem
INSERT INTO JurySem (codeNip, idSem, moySem, UE, bonus) VALUES
(1, 1, 14.3, '3/6', 1.5),
(1, 2, 13.2, '2/6', NULL),
(2, 3, 15.8, '5/6', 0.8),
(2, 1, 12.7, '4/6', 2.1),
(3, 2, 16.5, '6/6', 1.2),
(3, 3, 14.6, '3/6', NULL);

-- Insertion de données dans la table JuryAnnee
INSERT INTO JuryAnnee (codeNip, idAnnee, moyAnnee, RCUE, decision) VALUES
-- Insertion de données dans la table JuryAnnee (suite)
(2, 2, 15.2, '2/6', 'Admis'),
(2, 3, 14.7, '3/6', 'Admis'),
(3, 3, 15.9, '4/6', 'Admis'),
(3, 1, 14.1, '5/6', 'Admis');

-- Insertion de données dans la table MoyCompSem
INSERT INTO MoyCompSem (codeNip, idComp, idSem, moyCompSem, avis) VALUES
(1, 1, 1, 12.8, 'Bon'),
(1, 1, 2, 13.5, 'Très bon'),
(2, 2, 2, 14.7, 'Excellent'),
(2, 3, 3, 15.2, 'Très bon'),
(3, 1, 1, 11.9, 'Moyen'),
(3, 3, 2, 14.5, 'Bon');

-- Insertion de données dans la table MoyCompAnnee
INSERT INTO MoyCompAnnee (codeNip, idComp, idAnnee, moyCompAnnee) VALUES
(1, 1, 1, 12.7),
(1, 1, 2, 13.9),
(2, 2, 2, 14.6),
(2, 3, 3, 15.1),
(3, 1, 1, 12.1),
(3, 3, 2, 14.4);
