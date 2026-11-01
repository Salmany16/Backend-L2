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


-- Step : 02.2
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Leverancier.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		CREATE TABLE Leverancier gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Leverancier 
(
     Id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam                VARCHAR(100) NOT NULL
    ,ContactPersoon      VARCHAR(100) NOT NULL
    ,LeverancierNummer   VARCHAR(20) NOT NULL
    ,Mobiel              VARCHAR(20) NOT NULL
    ,IsActief            BIT NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- Step : 02.3
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


-- Step : 04.2
/*********************************************************************************
-- Doel : Voeg 5 rijen toe met INSERT aan tabel Leverancier
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		5 rijen gevoegd met insert aan tabel Leverancier 
**********************************************************************************/ 
INSERT INTO Leverancier
(
     Id
    ,Naam
    ,ContactPersoon
    ,LeverancierNummer
    ,Mobiel
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Venco', 'Bert van Linge', 'L1029384719', '06-28493827', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Astra Sweets', 'Jasper del Monte', 'L1029284315', '06-39398734', 1, NULL, NOW(6), NOW(6))
      ,(3, 'Haribo', 'Sven Stalman', 'L1029324748', '06-24383291', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Basset', 'Joyce Stelterberg', 'L1023845773', '06-48293823', 1, NULL, NOW(6), NOW(6))
      ,(5, 'De Bron', 'Remco Veenstra', 'L1023857736', '06-34291234', 1, NULL, NOW(6), NOW(6))
      ,(6, 'Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456', 1, NULL, NOW(6), NOW(6));


-- Step : 04.3
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


-- Step : 04.4
/*********************************************************************************
-- Doel : Voeg 13 rijen toe met INSERT aan tabel Magazijn
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     YousufzaiS		13 rijen gevoegd met insert aan tabel Magazijn (rij 10 heeft NULL voorraad) 
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


-- Step : 04.5
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


-- Step : 04.6
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
-- DEEL 5: STORED PROCEDURES (Opdracht 2)
-- ==============================================================================

-- Step : 06.1
/*********************************************************************************
-- Doel : Stored procedure sp_GetLeveranciersOverzicht
--        Geeft alle leveranciers terug gesorteerd op aantal verschillende
--        producten DESC (meeste leveringen bovenaan).
-- *******************************************************************************
-- Versie     Datum          Auteur          Omschrijving
-- ******     **********     **********      **************
-- 01         29-10-2026     YousufzaiS      sp_GetLeveranciersOverzicht aangemaakt
**********************************************************************************/
DELIMITER $$

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

DELIMITER ;


-- Step : 06.2
/*********************************************************************************
-- Doel : Stored procedure sp_GetGeleverdeProdPerLeverancier
--        Geeft alle producten die een leverancier heeft geleverd, met voorraad
--        en datum van laatste levering, gesorteerd op AantalAanwezig DESC.
-- *******************************************************************************
-- Versie     Datum          Auteur          Omschrijving
-- ******     **********     **********      **************
-- 01         29-10-2026     YousufzaiS      sp_GetGeleverdeProdPerLeverancier aangemaakt
**********************************************************************************/
DELIMITER $$

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

DELIMITER ;


-- Step : 06.3
/*********************************************************************************
-- Doel : Stored procedure sp_SlaLeveringOp
--        Voegt een nieuwe levering in ProductPerLeverancier in en werkt
--        de voorraad in Magazijn bij. Controleert NIET op IsActief hier —
--        validatie vindt plaats in de applicatielaag vóór aanroep.
-- *******************************************************************************
-- Versie     Datum          Auteur          Omschrijving
-- ******     **********     **********      **************
-- 01         29-10-2026     YousufzaiS      sp_SlaLeveringOp aangemaakt
**********************************************************************************/
DELIMITER $$

CREATE PROCEDURE sp_SlaLeveringOp(
    IN p_LeverancierId          INT,
    IN p_ProductId              INT,
    IN p_Aantal                 INT,
    IN p_DatumEerstVolgende     DATE
)
BEGIN
    -- Voeg nieuwe leveringsregel in
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

    -- Bijwerken van voorraad; gebruik COALESCE zodat NULL als 0 telt
    UPDATE Magazijn
    SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + p_Aantal,
        DatumGewijzigd = NOW(6)
    WHERE ProductId = p_ProductId;
END$$

DELIMITER ;
