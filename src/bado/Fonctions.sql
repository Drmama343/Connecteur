CREATE OR REPLACE FUNCTION MoyenneMathsParAnnee(nip_param INT, annee_param VARCHAR)
RETURNS DECIMAL AS $$
DECLARE
	moyenne DECIMAL(10,2);
BEGIN
	SELECT CAST(AVG(moyress) AS DECIMAL(10,2)) INTO moyenne
	FROM MoyRess
	WHERE ((annee_param = 'BUT1' AND idress IN ('BINR106', 'BINR107', 'BINR207', 'BINR208', 'BINR209'))
		OR (annee_param = 'BUT2' AND idress IN ('BINR308', 'BINR309', 'BINR412'))
		OR (annee_param = 'BUT3' AND idress IN ('BINR511', 'BINR512')))
		AND codenip = nip_param;

	RETURN moyenne;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION RangMaths(nip_param INT, annee_param VARCHAR)
RETURNS INT AS $$
DECLARE
	rang INT;
	moyenne_etudiant DECIMAL(10,2);
BEGIN
	-- Obtenir la moyenne de l'étudiant
	moyenne_etudiant := MoyenneMathsParAnnee(nip_param, annee_param);

	-- Calculer le rang de l'étudiant
	SELECT COUNT(*) + 1 INTO rang
	FROM (
		SELECT codenip, AVG(moyress) as moyenne
		FROM MoyRess
		WHERE ((annee_param = 'BUT1' AND idress IN ('BINR106', 'BINR107', 'BINR207', 'BINR208', 'BINR209'))
			OR (annee_param = 'BUT2' AND idress IN ('BINR308', 'BINR309', 'BINR412'))
			OR (annee_param = 'BUT3' AND idress IN ('BINR511', 'BINR512')))
		GROUP BY codenip
		HAVING AVG(moyress) > moyenne_etudiant
	) as subquery;

	RETURN rang;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION MoyenneAnglaisParAnnee(nip_param INT, annee_param VARCHAR)
RETURNS DECIMAL AS $$
DECLARE
	moyenne DECIMAL(10,2);
BEGIN
	SELECT CAST(AVG(moyress) AS DECIMAL(10,2)) INTO moyenne
	FROM MoyRess
	WHERE ((annee_param = 'BUT1' AND idress IN ('BINR110', 'BINR212'))
		OR (annee_param = 'BUT2' AND idress IN ('BINR312', 'BINR405'))
		OR (annee_param = 'BUT3' AND idress IN ('BINR514')))
		AND codenip = nip_param;

	RETURN moyenne;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION RangAnglais(nip_param INT, annee_param VARCHAR)
RETURNS INT AS $$
DECLARE
	rang INT;
	moyenne_etudiant DECIMAL(10,2);
BEGIN
	-- Obtenir la moyenne de l'étudiant
	moyenne_etudiant := MoyenneAnglaisParAnnee(nip_param, annee_param);

	-- Calculer le rang de l'étudiant
	SELECT COUNT(*) + 1 INTO rang
	FROM (
		SELECT codenip, AVG(moyress) as moyenne
		FROM MoyRess
		WHERE ((annee_param = 'BUT1' AND idress IN ('BINR110', 'BINR212'))
			OR (annee_param = 'BUT2' AND idress IN ('BINR312', 'BINR405'))
			OR (annee_param = 'BUT3' AND idress IN ('BINR514')))
		GROUP BY codenip
		HAVING AVG(moyress) > moyenne_etudiant
	) as subquery;

	RETURN rang;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION MettreAJourRangsCompetencesParAnnee(annee_param VARCHAR)
RETURNS VOID AS $$
BEGIN
	-- Mettre à jour les rangs des compétences pour chaque étudiant pour l'année spécifiée
	UPDATE moycompannee AS m
	SET rang = Classement.rang
	FROM (
		SELECT numcomp, codenip,
			   RANK() OVER (PARTITION BY numcomp ORDER BY moycompannee DESC) AS rang
		FROM moycompannee
		WHERE nomannee = annee_param
	) AS Classement
	WHERE m.numcomp = Classement.numcomp
	AND m.codenip = Classement.codenip;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION count_avis_by_type(nom_annee VARCHAR)
RETURNS TABLE (
	tres_favorable_count BIGINT,
	favorable_count BIGINT,
	assez_favorable_count BIGINT,
	sans_avis_count BIGINT,
	reserve_count BIGINT
) AS $$
BEGIN
	RETURN QUERY
	SELECT
		COUNT(CASE WHEN avisInge = 'Tres Favorable' THEN 1 END) AS tres_favorable_count,
		COUNT(CASE WHEN avisInge = 'Favorable' THEN 1 END) AS favorable_count,
		COUNT(CASE WHEN avisInge = 'Assez Favorable' THEN 1 END) AS assez_favorable_count,
		COUNT(CASE WHEN avisInge = 'Sans Avis' THEN 1 END) AS sans_avis_count,
		COUNT(CASE WHEN avisInge = 'Reserve' THEN 1 END) AS reserve_count
	FROM Etudiant
	JOIN PromoEtud ON Etudiant.codeNip = PromoEtud.codeNip
	JOIN Annee ON PromoEtud.anneePromo = Annee.nomAnnee
	JOIN MoyCompAnnee ON Etudiant.codeNip = MoyCompAnnee.codeNip;
END;
$$ LANGUAGE plpgsql;



