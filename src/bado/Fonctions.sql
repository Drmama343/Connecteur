CREATE OR REPLACE FUNCTION MoyenneMathsParAnnee(nip_param INT, nomannee_param VARCHAR, annee_param VARCHAR)
RETURNS DECIMAL AS $$
DECLARE
	moyenne DECIMAL(10,2);
BEGIN
	SELECT CAST(AVG(moyress) AS DECIMAL(10,2)) INTO moyenne
	FROM MoyRess
	WHERE ((nomannee_param = 'BUT1' AND idress IN ('BINR106', 'BINR107', 'BINR207', 'BINR208', 'BINR209'))
		OR (nomannee_param = 'BUT2' AND idress IN ('BINR308', 'BINR309', 'BINR412'))
		OR (nomannee_param = 'BUT3' AND idress IN ('BINR511', 'BINR512')))
		AND codenip = nip_param AND anneePromo = annee_param;

	RETURN moyenne;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION RangMaths(nip_param INT, nomannee_param VARCHAR, annee_param VARCHAR)
RETURNS INT AS $$
DECLARE
	rang INT;
	moyenne_etudiant DECIMAL(10,2);
BEGIN
	-- Obtenir la moyenne de l'étudiant
	moyenne_etudiant := MoyenneMathsParAnnee(nip_param, nomannee_param, annee_param);

	-- Calculer le rang de l'étudiant
	SELECT COUNT(*) + 1 INTO rang
	FROM (
		SELECT codenip, AVG(moyress) as moyenne
		FROM MoyRess
		WHERE ((nomannee_param = 'BUT1' AND idress IN ('BINR106', 'BINR107', 'BINR207', 'BINR208', 'BINR209'))
			OR (nomannee_param = 'BUT2' AND idress IN ('BINR308', 'BINR309', 'BINR412'))
			OR (nomannee_param = 'BUT3' AND idress IN ('BINR511', 'BINR512')))
		GROUP BY codenip
		HAVING AVG(moyress) > moyenne_etudiant
	) as subquery;

	RETURN rang;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION MoyenneAnglaisParAnnee(nip_param INT, nomannee_param VARCHAR, annee_param VARCHAR)
RETURNS DECIMAL AS $$
DECLARE
	moyenne DECIMAL(10,2);
BEGIN
	SELECT CAST(AVG(moyress) AS DECIMAL(10,2)) INTO moyenne
	FROM MoyRess
	WHERE ((nomannee_param = 'BUT1' AND idress IN ('BINR110', 'BINR212'))
		OR (nomannee_param = 'BUT2' AND idress IN ('BINR312', 'BINR405'))
		OR (nomannee_param = 'BUT3' AND idress IN ('BINR514')))
		AND codenip = nip_param AND anneePromo = annee_param;

	RETURN moyenne;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION RangAnglais(nip_param INT, nomannee_param VARCHAR, annee_param VARCHAR)
RETURNS INT AS $$
DECLARE
	rang INT;
	moyenne_etudiant DECIMAL(10,2);
BEGIN
	-- Obtenir la moyenne de l'étudiant
	moyenne_etudiant := MoyenneAnglaisParAnnee(nip_param, nomannee_param, annee_param);

	-- Calculer le rang de l'étudiant
	SELECT COUNT(*) + 1 INTO rang
	FROM (
		SELECT codenip, AVG(moyress) as moyenne
		FROM MoyRess
		WHERE ((nomannee_param = 'BUT1' AND idress IN ('BINR110', 'BINR212'))
			OR (nomannee_param = 'BUT2' AND idress IN ('BINR312', 'BINR405'))
			OR (nomannee_param = 'BUT3' AND idress IN ('BINR514')))
		GROUP BY codenip
		HAVING AVG(moyress) > moyenne_etudiant
	) as subquery;

	RETURN rang;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION MettreAJourRangsCompetencesParAnnee(nomannee_param VARCHAR, annee_param VARCHAR)
RETURNS VOID AS $$
BEGIN
	UPDATE moycompannee AS m
	SET rang = Classement.rang
	FROM (
		SELECT numcomp, codenip,
			   RANK() OVER (PARTITION BY numcomp ORDER BY moycompannee DESC) AS rang
		FROM moycompannee
		WHERE nomannee = nomannee_param
		AND anneePromo = annee_param
	) AS Classement
	WHERE m.numcomp = Classement.numcomp
	AND m.codenip = Classement.codenip
	AND m.anneePromo = Classement.anneePromo;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION MettreAJourRangsSemestre(semestre_param INT, annee_param VARCHAR)
RETURNS VOID AS $$
BEGIN
	UPDATE JurySem AS j
	SET rang = Classement.rang
	FROM (
		SELECT codeNip, anneePromo, idSem,
			   RANK() OVER (PARTITION BY codeNip, anneePromo, idSem ORDER BY moySem DESC) AS rang
		FROM JurySem
		WHERE anneePromo = annee_param
		AND idSem = semestre_param
	) AS Classement
	WHERE j.codeNip = Classement.codeNip
	AND j.anneePromo = Classement.anneePromo
	AND j.idSem = Classement.idSem;
END;
$$ LANGUAGE plpgsql;