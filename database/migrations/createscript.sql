-- Step : 01
/********************************************************************************
-- Doel : Maak een nieuwe database aan heet Jamin.
-- ******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		Database Jamin is aangemaakt + gebruiken (DROP, CREATE, USE)   	 
*********************************************************************************/ 

DROP DATABASE IF EXISTS Jamin;
CREATE DATABASE Jamin;
USE Jamin;


-- ==============================================================================
-- DEEL 1: STAMTABELLEN (Alleen Primary Key, geen Foreign Keys)
-- ==============================================================================

-- Step : 02.1
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Contact (BE-opdracht 03).
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         20-11-2026     YousufzaiS		CREATE TABLE Contact gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Contact 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Straat              VARCHAR(100) NOT NULL
    ,Huisnummer          INT NOT NULL
    ,Postcode            VARCHAR(10) NOT NULL
    ,Stad                VARCHAR(100) NOT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- Step : 02.2
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Product.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE Product gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Product 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam                VARCHAR(100) NOT NULL
    ,Barcode             VARCHAR(20) NOT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- Step : 02.3
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Leverancier.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE Leverancier gemaakt
-- 02         20-11-2026     YousufzaiS		ContactId toegevoegd met FK naar Contact
**********************************************************************************/ 
CREATE TABLE Leverancier 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ContactId           INT UNSIGNED NULL DEFAULT NULL
    ,Naam                VARCHAR(100) NOT NULL
    ,ContactPersoon      VARCHAR(100) NOT NULL
    ,LeverancierNummer   VARCHAR(20) NOT NULL
    ,Mobiel              VARCHAR(20) NOT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_Leverancier_ContactId FOREIGN KEY (ContactId) REFERENCES Contact(Id) ON DELETE SET NULL ON UPDATE CASCADE
);


-- Step : 02.4
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Allergeen.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE Allergeen gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Allergeen 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam                VARCHAR(100) NOT NULL
    ,Omschrijving        VARCHAR(255) NOT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- ==============================================================================
-- DEEL 2: KOPPELTABELLEN & AFHANKELIJKE TABELLEN (Met Foreign Keys)
-- ==============================================================================

-- Step : 03.1
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Magazijn.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE Magazijn gemaakt met FK naar Product	 
**********************************************************************************/ 
CREATE TABLE Magazijn 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ProductId           INT UNSIGNED NOT NULL
    ,VerpakkingsEenheid  DECIMAL(4,1) NOT NULL
    ,AantalAanwezig      INT NULL DEFAULT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_Magazijn_ProductId FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Step : 03.2
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet ProductPerAllergeen.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE ProductPerAllergeen gemaakt met FK naar Product en Allergeen	 
**********************************************************************************/ 
CREATE TABLE ProductPerAllergeen 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ProductId           INT UNSIGNED NOT NULL
    ,AllergeenId         INT UNSIGNED NOT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_ProductPerAllergeen_ProductId FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE ON UPDATE CASCADE
    ,CONSTRAINT FK_ProductPerAllergeen_AllergeenId FOREIGN KEY (AllergeenId) REFERENCES Allergeen(Id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Step : 03.3
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet ProductPerLeverancier.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE ProductPerLeverancier gemaakt met FK naar Leverancier en Product	 
**********************************************************************************/ 
CREATE TABLE ProductPerLeverancier 
(
     Id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,LeverancierId               INT UNSIGNED NOT NULL
    ,ProductId                   INT UNSIGNED NOT NULL
    ,DatumLevering               DATE NOT NULL
    ,Aantal                      INT NOT NULL
    ,DatumEerstVolgendeLevering  DATE NULL DEFAULT NULL
    ,IsActief                    BIT NOT NULL DEFAULT 1
    ,Opmerking                   VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt             DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd              DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_ProductPerLeverancier_LeverancierId FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id) ON DELETE CASCADE ON UPDATE CASCADE
    ,CONSTRAINT FK_ProductPerLeverancier_ProductId FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- ==============================================================================
-- DEEL 3: DATA INVOEREN (INSERT STATEMENTS)
-- ==============================================================================

-- Step : 04.1
/*********************************************************************************
-- Doel : Voeg 6 rijen toe met INSERT aan tabel Contact (BE-opdracht 03)
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         20-11-2026     YousufzaiS		6 rijen ingevoegd in Contact
**********************************************************************************/ 
INSERT INTO Contact
(
     Id
    ,Straat
    ,Huisnummer
    ,Postcode
    ,Stad
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Van Gilslaan', 34, '1045CB', 'Hilvarenbeek', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Den Dolderpad', 2, '1067RC', 'Utrecht', 1, NULL, NOW(6), NOW(6))
      ,(3, 'Fredo Raalteweg', 257, '1236OP', 'Nijmegen', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Bertrand Russellhof', 21, '2034AP', 'Den Haag', 1, NULL, NOW(6), NOW(6))
      ,(5, 'Leon van Bonstraat', 213, '145XC', 'Lunteren', 1, NULL, NOW(6), NOW(6))
      ,(6, 'Bea van Lingenlaan', 234, '2197FG', 'Sint Pancras', 1, NULL, NOW(6), NOW(6));


-- Step : 04.2
/*********************************************************************************
-- Doel : Voeg 13 rijen toe met INSERT aan tabel Product
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		13 rijen gevoegd met insert aan tabel Product 
**********************************************************************************/ 
INSERT INTO Product
(
     Id
    ,Naam
    ,Barcode
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Mintnopjes', '8719587231278', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Schoolkrijt', '8719587326713', 1, NULL, NOW(6), NOW(6))
      ,(3, 'Honingdrop', '8719587327836', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Zure Beren', '8719587321441', 1, NULL, NOW(6), NOW(6))
      ,(5, 'Cola Flesjes', '8719587321237', 1, NULL, NOW(6), NOW(6))
      ,(6, 'Turtles', '8719587322245', 1, NULL, NOW(6), NOW(6))
      ,(7, 'Witte Muizen', '8719587328256', 1, NULL, NOW(6), NOW(6))
      ,(8, 'Reuzen Slangen', '8719587325641', 1, NULL, NOW(6), NOW(6))
      ,(9, 'Zoute Rijen', '8719587322739', 1, NULL, NOW(6), NOW(6))
      ,(10, 'Winegums', '8719587327527', 0, NULL, NOW(6), NOW(6))
      ,(11, 'Drop Munten', '8719587322345', 1, NULL, NOW(6), NOW(6))
      ,(12, 'Kruis Drop', '8719587322265', 1, NULL, NOW(6), NOW(6))
      ,(13, 'Zoute Ruitjes', '8719587323256', 1, NULL, NOW(6), NOW(6));


-- Step : 04.3
/*********************************************************************************
-- Doel : Voeg 6 rijen toe met INSERT aan tabel Leverancier (inclusief ContactId)
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		6 rijen gevoegd met insert aan tabel Leverancier 
**********************************************************************************/ 
INSERT INTO Leverancier
(
     Id
    ,ContactId
    ,Naam
    ,ContactPersoon
    ,LeverancierNummer
    ,Mobiel
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 'Venco', 'Bert van Linge', 'L1029384719', '06-28493827', 1, NULL, NOW(6), NOW(6))
      ,(2, 2, 'Astra Sweets', 'Jasper del Monte', 'L1029284315', '06-39398734', 1, NULL, NOW(6), NOW(6))
      ,(3, 3, 'Haribo', 'Sven Stalman', 'L1029324748', '06-24383291', 1, NULL, NOW(6), NOW(6))
      ,(4, 4, 'Basset', 'Joyce Stelterberg', 'L1023845773', '06-48293823', 1, NULL, NOW(6), NOW(6))
      ,(5, 5, 'De Bron', 'Remco Veenstra', 'L1023857736', '06-34291234', 1, NULL, NOW(6), NOW(6))
      ,(6, 6, 'Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456', 1, NULL, NOW(6), NOW(6));


-- Step : 04.4
/*********************************************************************************
-- Doel : Voeg 5 rijen toe met INSERT aan tabel Allergeen
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		5 rijen gevoegd met insert aan tabel Allergeen 
**********************************************************************************/ 
INSERT INTO Allergeen
(
     Id
    ,Naam
    ,Omschrijving
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Gluten', 'Dit product bevat gluten', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Gelatine', 'Dit product bevat gelatine', 1, NULL, NOW(6), NOW(6))
      ,(3, 'AZO-Kleurstof', 'Dit product bevat AZO-kleurstoffen', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Lactose', 'Dit product bevat lactose', 1, NULL, NOW(6), NOW(6))
      ,(5, 'Soja', 'Dit product bevat soja', 1, NULL, NOW(6), NOW(6));


-- Step : 04.5
/*********************************************************************************
-- Doel : Voeg 13 rijen toe met INSERT aan tabel Magazijn
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		13 rijen gevoegd met insert aan tabel Magazijn 
**********************************************************************************/ 
INSERT INTO Magazijn
(
     Id
    ,ProductId
    ,VerpakkingsEenheid
    ,AantalAanwezig
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 5.0, 453, 1, NULL, NOW(6), NOW(6))
      ,(2, 2, 2.5, 400, 1, NULL, NOW(6), NOW(6))
      ,(3, 3, 5.0, 1, 1, NULL, NOW(6), NOW(6))
      ,(4, 4, 1.0, 800, 1, NULL, NOW(6), NOW(6))
      ,(5, 5, 3.0, 234, 1, NULL, NOW(6), NOW(6))
      ,(6, 6, 2.0, 345, 1, NULL, NOW(6), NOW(6))
      ,(7, 7, 1.0, 795, 1, NULL, NOW(6), NOW(6))
      ,(8, 8, 10.0, 233, 1, NULL, NOW(6), NOW(6))
      ,(9, 9, 2.5, 123, 1, NULL, NOW(6), NOW(6))
      ,(10, 10, 3.0, NULL, 1, NULL, NOW(6), NOW(6))
      ,(11, 11, 2.0, 367, 1, NULL, NOW(6), NOW(6))
      ,(12, 12, 1.0, 467, 1, NULL, NOW(6), NOW(6))
      ,(13, 13, 5.0, 20, 1, NULL, NOW(6), NOW(6));


-- Step : 04.6
/*********************************************************************************
-- Doel : Voeg 12 rijen toe met INSERT aan koppeltabel ProductPerAllergeen
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		12 rijen gevoegd met insert aan tabel ProductPerAllergeen 
**********************************************************************************/ 
INSERT INTO ProductPerAllergeen
(
     Id
    ,ProductId
    ,AllergeenId
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 2, 1, NULL, NOW(6), NOW(6))
      ,(2, 1, 1, 1, NULL, NOW(6), NOW(6))
      ,(3, 1, 3, 1, NULL, NOW(6), NOW(6))
      ,(4, 3, 4, 1, NULL, NOW(6), NOW(6))
      ,(5, 6, 5, 1, NULL, NOW(6), NOW(6))
      ,(6, 9, 2, 1, NULL, NOW(6), NOW(6))
      ,(7, 9, 5, 1, NULL, NOW(6), NOW(6))
      ,(8, 10, 2, 1, NULL, NOW(6), NOW(6))
      ,(9, 12, 4, 1, NULL, NOW(6), NOW(6))
      ,(10, 13, 1, 1, NULL, NOW(6), NOW(6))
      ,(11, 13, 4, 1, NULL, NOW(6), NOW(6))
      ,(12, 13, 5, 1, NULL, NOW(6), NOW(6));


-- Step : 04.7
/*********************************************************************************
-- Doel : Voeg 17 rijen toe met INSERT aan koppeltabel ProductPerLeverancier
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		17 rijen gevoegd met insert aan tabel ProductPerLeverancier 
**********************************************************************************/ 
INSERT INTO ProductPerLeverancier
(
     Id
    ,LeverancierId
    ,ProductId
    ,DatumLevering
    ,Aantal
    ,DatumEerstVolgendeLevering
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 1, '2024-10-09', 23, '2024-10-16', 1, NULL, NOW(6), NOW(6))
      ,(2, 1, 1, '2024-10-18', 21, '2024-10-25', 1, NULL, NOW(6), NOW(6))
      ,(3, 1, 2, '2024-10-09', 12, '2024-10-16', 1, NULL, NOW(6), NOW(6))
      ,(4, 1, 3, '2024-10-10', 11, '2024-10-17', 1, NULL, NOW(6), NOW(6))
      ,(5, 2, 4, '2024-10-14', 16, '2024-10-21', 1, NULL, NOW(6), NOW(6))
      ,(6, 2, 4, '2024-10-21', 23, '2024-10-28', 1, NULL, NOW(6), NOW(6))
      ,(7, 2, 5, '2024-10-14', 45, '2024-10-21', 1, NULL, NOW(6), NOW(6))
      ,(8, 2, 6, '2024-10-14', 30, '2024-10-21', 1, NULL, NOW(6), NOW(6))
      ,(9, 3, 7, '2024-10-12', 12, '2024-10-19', 1, NULL, NOW(6), NOW(6))
      ,(10, 3, 7, '2024-10-19', 23, '2024-10-26', 1, NULL, NOW(6), NOW(6))
      ,(11, 3, 8, '2024-10-10', 12, '2024-10-17', 1, NULL, NOW(6), NOW(6))
      ,(12, 3, 9, '2024-10-11', 1, '2024-10-18', 1, NULL, NOW(6), NOW(6))
      ,(13, 4, 10, '2024-10-16', 24, '2024-10-30', 1, NULL, NOW(6), NOW(6))
      ,(14, 5, 11, '2024-10-10', 47, '2024-10-17', 1, NULL, NOW(6), NOW(6))
      ,(15, 5, 11, '2024-10-19', 60, '2024-10-26', 1, NULL, NOW(6), NOW(6))
      ,(16, 5, 12, '2024-10-11', 45, NULL, 1, NULL, NOW(6), NOW(6))
      ,(17, 5, 13, '2024-10-12', 23, NULL, 1, NULL, NOW(6), NOW(6));


-- ==============================================================================
-- DEEL 4: STORED PROCEDURES
-- ==============================================================================

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
