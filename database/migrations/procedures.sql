USE Jamin;

DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht;
DROP PROCEDURE IF EXISTS sp_GetGeleverdeProdPerLeverancier;
DROP PROCEDURE IF EXISTS sp_SlaLeveringOp;
DROP PROCEDURE IF EXISTS sp_GetMagazijnOverzicht;
DROP PROCEDURE IF EXISTS sp_GetLeveringDetails;
DROP PROCEDURE IF EXISTS sp_GetAllergenenPerProduct;
DROP PROCEDURE IF EXISTS sp_GetLeverancierById;
DROP PROCEDURE IF EXISTS sp_GetProductById;
DROP PROCEDURE IF EXISTS sp_GetProductLeveringDetails;

DELIMITER //

CREATE PROCEDURE sp_GetLeveranciersOverzicht()
BEGIN
    SELECT l.Id,
           l.Naam,
           l.ContactPersoon,
           l.LeverancierNummer,
           l.Mobiel,
           COUNT(DISTINCT ppl.ProductId) AS AantalVerschillendeProducten
    FROM Leverancier l
    LEFT JOIN ProductPerLeverancier ppl ON l.Id = ppl.LeverancierId
    GROUP BY l.Id, l.Naam, l.ContactPersoon, l.LeverancierNummer, l.Mobiel
    ORDER BY AantalVerschillendeProducten DESC;
END //

CREATE PROCEDURE sp_GetGeleverdeProdPerLeverancier(IN p_LeverancierId INT)
BEGIN
    SELECT p.Id                  AS ProductId,
           p.Naam                AS ProductNaam,
           p.IsActief,
           m.VerpakkingsEenheid,
           m.AantalAanwezig,
           MAX(ppl.DatumLevering) AS LaatsteLevering
    FROM ProductPerLeverancier ppl
    JOIN Product p ON ppl.ProductId = p.Id
    LEFT JOIN Magazijn m ON p.Id = m.ProductId
    WHERE ppl.LeverancierId = p_LeverancierId
    GROUP BY p.Id, p.Naam, p.IsActief, m.VerpakkingsEenheid, m.AantalAanwezig
    ORDER BY m.AantalAanwezig DESC;
END //

CREATE PROCEDURE sp_SlaLeveringOp(
    IN p_LeverancierId          INT,
    IN p_ProductId              INT,
    IN p_Aantal                 INT,
    IN p_DatumEerstVolgende     DATE
)
BEGIN
    INSERT INTO ProductPerLeverancier
    (
         LeverancierId
        ,ProductId
        ,DatumLevering
        ,Aantal
        ,DatumEerstVolgendeLevering
        ,IsActief
        ,DatumAangemaakt
        ,DatumGewijzigd
    )
    VALUES
    (
         p_LeverancierId
        ,p_ProductId
        ,CURDATE()
        ,p_Aantal
        ,p_DatumEerstVolgende
        ,1
        ,NOW(6)
        ,NOW(6)
    );

    UPDATE Magazijn
    SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + p_Aantal,
        DatumGewijzigd = NOW(6)
    WHERE ProductId = p_ProductId;
END //

CREATE PROCEDURE sp_GetMagazijnOverzicht()
BEGIN
    SELECT p.Id,
           p.Naam,
           p.Barcode,
           m.VerpakkingsEenheid,
           m.AantalAanwezig
    FROM Product p
    LEFT JOIN Magazijn m ON p.Id = m.ProductId
    WHERE p.IsActief = 1
    ORDER BY p.Barcode ASC;
END //

CREATE PROCEDURE sp_GetLeveringDetails(IN p_ProductId INT)
BEGIN
    SELECT ppl.Id,
           ppl.LeverancierId,
           ppl.ProductId,
           ppl.DatumLevering,
           ppl.Aantal,
           ppl.DatumEerstVolgendeLevering,
           l.Naam AS LeverancierNaam,
           l.ContactPersoon,
           l.LeverancierNummer,
           l.Mobiel
    FROM ProductPerLeverancier ppl
    JOIN Leverancier l ON ppl.LeverancierId = l.Id
    WHERE ppl.ProductId = p_ProductId
    ORDER BY ppl.DatumLevering ASC;
END //

CREATE PROCEDURE sp_GetAllergenenPerProduct(IN p_ProductId INT)
BEGIN
    SELECT a.Naam, a.Omschrijving
    FROM ProductPerAllergeen ppa
    JOIN Allergeen a ON ppa.AllergeenId = a.Id
    WHERE ppa.ProductId = p_ProductId
    ORDER BY a.Naam ASC;
END //

CREATE PROCEDURE sp_GetLeverancierById(IN p_Id INT)
BEGIN
    SELECT * FROM Leverancier WHERE Id = p_Id;
END //

CREATE PROCEDURE sp_GetProductById(IN p_Id INT)
BEGIN
    SELECT p.Id,
           p.Naam,
           p.Barcode,
           p.IsActief,
           m.AantalAanwezig,
           m.VerpakkingsEenheid
    FROM Product p
    LEFT JOIN Magazijn m ON p.Id = m.ProductId
    WHERE p.Id = p_Id;
END //

CREATE PROCEDURE sp_GetProductLeveringDetails(IN p_LeverancierId INT, IN p_ProductId INT)
BEGIN
    SELECT p.Id AS ProductId,
           p.Naam AS ProductNaam,
           p.IsActief AS ProductIsActief,
           l.Id AS LeverancierId,
           l.Naam AS LeverancierNaam,
           l.ContactPersoon,
           l.Mobiel
    FROM Product p, Leverancier l
    WHERE p.Id = p_ProductId AND l.Id = p_LeverancierId;
END //

DELIMITER ;
