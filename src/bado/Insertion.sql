TRUNCATE TABLE MoyCompAnnee;
TRUNCATE TABLE MoyCompSem;
TRUNCATE TABLE JuryAnnee;
TRUNCATE TABLE JurySem;
TRUNCATE TABLE MoyRess;
TRUNCATE TABLE CompSem;
TRUNCATE TABLE Coeff;
TRUNCATE TABLE PromoEtud CASCADE;
TRUNCATE TABLE Etudiant CASCADE;
TRUNCATE TABLE Promotion CASCADE;
TRUNCATE TABLE Ressource CASCADE;
TRUNCATE TABLE Competence CASCADE;
TRUNCATE TABLE Annee CASCADE;
TRUNCATE TABLE Semestre CASCADE;


INSERT INTO Semestre VALUES 
	(1,'S1'),
	(2,'S2'),
	(3,'S3'),
	(4,'S4'),
	(5,'S5'),
	(6,'S6');

INSERT INTO Competence VALUES
	('BIN11', 'Réaliser un développement d’application'),
	('BIN12', 'Optimiser des Applications informatiques'),
	('BIN13', 'Administrer des Systèmes informatiques communicants complexes'),
	('BIN14', 'Gérer des Données de l’information'),
	('BIN15', 'Conduire un projet'),
	('BIN16', 'Travailler dans une équipe informatique'),
	('BIN21', 'Réaliser un développement d’application'),
	('BIN22', 'Optimiser des Applications informatiques'),
	('BIN23', 'Administrer des Systèmes informatiques communicants complexes'),
	('BIN24', 'Gérer des Données de l’information'),
	('BIN25', 'Conduire un projet'),
	('BIN26', 'Travailler dans une équipe informatique'),
	('BIN31', 'Réaliser un développement d’application'),
	('BIN32', 'Optimiser des Applications informatiques'),
	('BIN33', 'Administrer des Systèmes informatiques communicants complexes'),
	('BIN34', 'Gérer des Données de l’information'),
	('BIN35', 'Conduire un projet'),
	('BIN36', 'Travailler dans une équipe informatique'),
	('BIN41', 'Réaliser un développement d’application'),
	('BIN42', 'Optimiser des Applications informatiques'),
	('BIN43', 'Administrer des Systèmes informatiques communicants complexes'),
	('BIN44', 'Gérer des Données de l’information'),
	('BIN45', 'Conduire un projet'),
	('BIN46', 'Travailler dans une équipe informatique'),
	('BIN51', 'Réaliser un développement d’application'),
	('BIN52', 'Optimiser des Applications informatiques'),
	('BIN56', 'Travailler dans une équipe informatique'),
	('BIN61', 'Réaliser un développement d’application'),
	('BIN62', 'Optimiser des Applications informatiques'),
	('BIN66', 'Travailler dans une équipe informatique');

INSERT INTO Ressource VALUES
	('BINR101', 'Initiation au développement'),
	('BINR102', 'Développement interfaces Web'),
	('BINR103', 'Introduction Architecture'),
	('BINR104', 'Introduction Système'),
	('BINR105', 'Introduction Base de données'),
	('BINR106', 'Mathématiques discrètes'),
	('BINR107', 'Outils mathématiques fondamentaux'),
	('BINR108', 'Gestion de projet et des organisations'),
	('BINR109', 'Économie durable et numérique'),
	('BINR110', 'Anglais Technique'),
	('BINR111', 'Bases de la communication'),
	('BINR112', 'Projet Professionnel et Personnel'),
	('BINS101', 'Implémentation d’un besoin client'),
	('BINS102', 'Comparaison d’approches algorithmiques'),
	('BINS103', 'Installation d’un poste pour le développement'),
	('BINS104', 'Création d’une base de données'),
	('BINS105', 'Recueil de besoins'),
	('BINS106', 'Découverte de l’environnement économique et écologique'),


	('BINR201', 'Développement orienté objets'),
	('BINR202', 'Développement d’applications avec IHM'),
	('BINR203', 'Qualité de développement'),
	('BINR204', 'Communication et fonctionnement bas niveau'),
	('BINR205', 'Introduction aux services réseaux'),
	('BINR206', 'Exploitation d’une base de données'),
	('BINR207', 'Graphes'),
	('BINR208', 'Outils numériques pour les statistiques'),
	('BINR209', 'Méthodes numériques'),
	('BINR210', 'Gestion de projet et des organisations'),
	('BINR211', 'Droit'),
	('BINR212', 'Anglais d’entreprise'),
	('BINR213', 'Communication Technique'),
	('BINR214', 'Projet Professionnel et Personnel'),
	('BINS201', 'Développement d’une application'),
	('BINS202', 'Exploration algorithmique d’un problème'),
	('BINS203', 'Installation de services réseau'),
	('BINS204', 'Exploitation d’une base de données'),
	('BINS205', 'Gestion d’un projet '),
	('BINS206', 'Organisation d’un travail d’équipe'),
	('BINP201', 'Portfolio'),

	('BINR301', 'Développement WEB'),
	('BINR302', 'Développement Efficace'),
	('BINR303', 'Analyse'),
	('BINR304', 'Qualité de développement 3'),
	('BINR305', 'Programmation système'),
	('BINR306', 'Architecture des réseaux'),
	('BINR307', 'SQL dans un langage de programmation'),
	('BINR308', 'Probabilités'),
	('BINR309', 'Cryptographie et sécurité'),
	('BINR310', 'Management des systèmes d’information'),
	('BINR311', 'Droits des contrats et du numérique'),
	('BINR312', 'Anglais 3'),
	('BINR313', 'Communication professionnelle'),
	('BINR314', 'PPP 3'),
	('BINS301', 'Développement d’une application'),

	('BINR401', 'Architecture logicielle'),
	('BINR402', 'Qualité de développement 4'),
	('BINR403', 'Qualité et au delà du relationnel'),
	('BINR404', 'Méthodes d’optimisation'),
	('BINR405', 'Anglais 4'),
	('BINR406', 'Communication interne'),
	('BINR407', 'PPP 4'),
	('BINR408', 'Virtualisation'),
	('BINR409', 'Management avancé des Systèmes d’information'),
	('BINR410', 'Complément web'),
	('BINR411', 'Développement mobile'),
	('BINR412', 'Automates'),
	('BINS401', 'Développement d’une application'),
	('BINS411', 'Stages'),

	('BINR501', 'Initiation au management d’une équipe de projet informatique'),
	('BINR502', 'Projet Personnel et Professionnel'),
	('BINR503', 'Politique de communication'),
	('BINR504', 'Qualité algorithmique'),
	('BINR505', 'Programmation avancée'),
	('BINR506', 'Programmation multimédia'),
	('BINR507', 'Automatisation de la chaîne de production'),
	('BINR508', 'Qualité de développement'),
	('BINR509', 'Virtualisation avancée'),
	('BINR510', 'Nouveaux paradigmes de base de données'),
	('BINR511', 'Méthodes d’optimisation pour l’aide à la décision'),
	('BINR512', 'Modélisations mathématiques'),
	('BINR513', 'Économie durable et numérique'),
	('BINR514', 'Anglais'),
	('BINS501', 'Développement avancé'),

	('BINR601', 'Initiation à l’entrepreneuriat'),
	('BINR602', 'Droit du numérique et de la propriété intellectuelle'),
	('BINR603', 'Communication : organisation et diffusion de l’information '),
	('BINR604', 'Projet Personnel et Professionnel'),
	('BINR605', 'Développement avancé'),
	('BINR606', 'Maintenance applicative'),
	('BINS601', 'Développement d’une application'),
	('BINS611', 'Stage');

INSERT INTO Annee VALUES
	('BUT1', 1, 2),
	('BUT2', 3, 4),
	('BUT3', 5, 6);

INSERT INTO Promotion VALUES ('2023-2024')