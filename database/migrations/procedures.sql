USE Jamin;

DELIMITER $$

-- 1. Leveranciers overzicht gesorteerd op aantal verschillende producten DESC
DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht$$
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
END$$

-- 2. Geleverde producten per leverancier
DROP PROCEDURE IF EXISTS sp_GetGeleverdeProdPerLeverancier$$
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
END$$

-- 3. Levering opslaan en voorraad bijwerken
DROP PROCEDURE IF EXISTS sp_SlaLeveringOp$$
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
END$$

-- 4. Magazijn overzicht
DROP PROCEDURE IF EXISTS sp_GetMagazijnOverzicht$$
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
END$$

-- 5. Levering details per product
DROP PROCEDURE IF EXISTS sp_GetLeveringDetails$$
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
END$$

-- 6. Allergenen per product
DROP PROCEDURE IF EXISTS sp_GetAllergenenPerProduct$$
CREATE PROCEDURE sp_GetAllergenenPerProduct(IN p_ProductId INT)
BEGIN
    SELECT a.Naam, a.Omschrijving
    FROM ProductPerAllergeen ppa
    JOIN Allergeen a ON ppa.AllergeenId = a.Id
    WHERE ppa.ProductId = p_ProductId
    ORDER BY a.Naam ASC;
END$$

-- 7. Leverancier op Id
DROP PROCEDURE IF EXISTS sp_GetLeverancierById$$
CREATE PROCEDURE sp_GetLeverancierById(IN p_Id INT)
BEGIN
    SELECT * FROM Leverancier WHERE Id = p_Id;
END$$

-- 8. Product op Id
DROP PROCEDURE IF EXISTS sp_GetProductById$$
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
END$$

-- 9. Product en leverancier details voor formulier
DROP PROCEDURE IF EXISTS sp_GetProductLeveringDetails$$
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
END$$

-- 10. BE-Opdracht 03: Leveranciers overzicht met paginering (max 4 per pagina)
DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzichtPaginering$$
CREATE PROCEDURE sp_GetLeveranciersOverzichtPaginering(IN p_Offset INT, IN p_Limit INT)
BEGIN
    SELECT l.Id,
           l.Naam,
           l.ContactPersoon,
           l.LeverancierNummer,
           l.Mobiel
    FROM Leverancier l
    ORDER BY l.Id ASC
    LIMIT p_Limit OFFSET p_Offset;
END$$

-- 11. BE-Opdracht 03: Totaal aantal leveranciers voor paginering
DROP PROCEDURE IF EXISTS sp_GetLeveranciersCount$$
CREATE PROCEDURE sp_GetLeveranciersCount()
BEGIN
    SELECT COUNT(*) AS Totaal FROM Leverancier;
END$$

-- 12. BE-Opdracht 03: Details leverancier inclusief adres (Contact)
DROP PROCEDURE IF EXISTS sp_GetLeverancierDetailsEnAdres$$
CREATE PROCEDURE sp_GetLeverancierDetailsEnAdres(IN p_LeverancierId INT)
BEGIN
    SELECT l.Id AS LeverancierId,
           l.Naam,
           l.ContactPersoon,
           l.LeverancierNummer,
           l.Mobiel,
           c.Id AS ContactId,
           c.Straat AS Straatnaam,
           c.Huisnummer,
           c.Postcode,
           c.Stad
    FROM Leverancier l
    LEFT JOIN Contact c ON l.ContactId = c.Id
    WHERE l.Id = p_LeverancierId;
END$$

-- 13. BE-Opdracht 03: Wijzigen leverancier en contactgegevens
DROP PROCEDURE IF EXISTS sp_UpdateLeverancierEnContact$$
CREATE PROCEDURE sp_UpdateLeverancierEnContact(
    IN p_LeverancierId      INT,
    IN p_Naam               VARCHAR(100),
    IN p_ContactPersoon     VARCHAR(100),
    IN p_LeverancierNummer  VARCHAR(20),
    IN p_Mobiel             VARCHAR(20),
    IN p_Straatnaam         VARCHAR(100),
    IN p_Huisnummer         INT,
    IN p_Postcode           VARCHAR(10),
    IN p_Stad               VARCHAR(100)
)
BEGIN
    DECLARE v_ContactId INT;

    SELECT ContactId INTO v_ContactId
    FROM Leverancier
    WHERE Id = p_LeverancierId;

    UPDATE Leverancier
    SET Naam = p_Naam,
        ContactPersoon = p_ContactPersoon,
        LeverancierNummer = p_LeverancierNummer,
        Mobiel = p_Mobiel,
        DatumGewijzigd = NOW(6)
    WHERE Id = p_LeverancierId;

    IF v_ContactId IS NOT NULL THEN
        UPDATE Contact
        SET Straat = p_Straatnaam,
            Huisnummer = p_Huisnummer,
            Postcode = p_Postcode,
            Stad = p_Stad,
            DatumGewijzigd = NOW(6)
        WHERE Id = v_ContactId;
    ELSE
        INSERT INTO Contact
        (
             Straat
            ,Huisnummer
            ,Postcode
            ,Stad
            ,IsActief
            ,Opmerking
            ,DatumAangemaakt
            ,DatumGewijzigd
        )
        VALUES
        (
             p_Straatnaam
            ,p_Huisnummer
            ,p_Postcode
            ,p_Stad
            ,1
            ,NULL
            ,NOW(6)
            ,NOW(6)
        );

        UPDATE Leverancier
        SET ContactId = LAST_INSERT_ID(),
            DatumGewijzigd = NOW(6)
        WHERE Id = p_LeverancierId;
    END IF;
END$$

DELIMITER ;
