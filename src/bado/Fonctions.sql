CREATE OR REPLACE FUNCTION MoyenneMathsParAnnee(nip_param INT, annee_param VARCHAR)
RETURNS DECIMAL AS $$
DECLARE
	moyenne DECIMAL;
BEGIN
	-- Calcul de la moyenne des notes pour les ressources cibles, l'année et le NIP spécifiés
	SELECT CAST(AVG(moyress) AS DECIMAL(10,2)) INTO moyenne
	FROM MoyRess
	WHERE (annee_param = 'BUT1' AND idress IN ('BINR106', 'BINR107', 'BINR207', 'BINR208', 'BINR209'))
		OR (annee_param = 'BUT2' AND idress IN ('BINR308', 'BINR309', 'BINR412'))
		OR (annee_param = 'BUT3' AND idress IN ('BINR511', 'BINR512'))
		AND codenip = nip_param;

	RETURN moyenne;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION MoyenneAnglaisParAnnee(nip_param INT, annee_param VARCHAR)
RETURNS DECIMAL AS $$
DECLARE
	moyenne DECIMAL;
BEGIN
	-- Calcul de la moyenne des notes pour les ressources cibles, l'année et le NIP spécifiés
	SELECT CAST(AVG(moyress) AS DECIMAL(10,2)) INTO moyenne
	FROM MoyRess
	WHERE (annee_param = 'BUT1' AND idress IN ('BINR110', 'BINR212'))
		OR (annee_param = 'BUT2' AND idress IN ('BINR312', 'BINR405'))
		OR (annee_param = 'BUT3' AND idress IN ('BINR514'))
		AND codenip = nip_param;

	RETURN moyenne;
END;
$$ LANGUAGE plpgsql;
