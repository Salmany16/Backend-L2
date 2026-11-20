# Database Specificatie Tabel - Magazijn Jamin

**Opdracht:** BE-opdracht 03  
**Auteur:** YousufzaiS  
**Klas:** IO-SD-2509AB  
**Database:** `Jamin`  

---

## 1. Stamtabellen

### 1.1 Tabel: `Contact`
Beschrijving: Bevat de adres- en contactlocatiegegevens van leveranciers (BE-opdracht 03).

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier van het contactadres |
| `Straat` | VARCHAR | 100 | Nee | - | - | Straatnaam van het leveranciersadres |
| `Huisnummer` | INT | 10 | Nee | - | - | Huisnummer van het leveranciersadres |
| `Postcode` | VARCHAR | 10 | Nee | - | - | Postcode (bijv. 1067RC) |
| `Stad` | VARCHAR | 100 | Nee | - | - | Plaats / vestigingsstad |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

---

### 1.2 Tabel: `Product`
Beschrijving: Bevat de basisgegevens van alle snoepgoedproducten.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier van het product |
| `Naam` | VARCHAR | 100 | Nee | - | - | Naam van het snoepgoedproduct |
| `Barcode` | VARCHAR | 20 | Nee | - | - | Unieke streepjescode (EAN) |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

---

### 1.3 Tabel: `Leverancier`
Beschrijving: Bevat de identificatie- en contactpersoongegevens van leveranciers, gekoppeld aan een adres in `Contact`.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier van de leverancier |
| `ContactId` | INT UNSIGNED | 10 | Ja | FK naar `Contact(Id)` | NULL | Referentie naar het adres in `Contact` |
| `Naam` | VARCHAR | 100 | Nee | - | - | Naam van het leveranciersbedrijf |
| `ContactPersoon` | VARCHAR | 100 | Nee | - | - | Naam van de vaste contactpersoon |
| `LeverancierNummer` | VARCHAR | 20 | Nee | - | - | Uniek nummer van de leverancier (bijv. L1029384719) |
| `Mobiel` | VARCHAR | 20 | Nee | - | - | Mobiel telefoonnummer contactpersoon |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_Leverancier_ContactId`: `ContactId` verwijst naar `Contact(Id)` met `ON DELETE SET NULL ON UPDATE CASCADE`.

---

### 1.4 Tabel: `Allergeen`
Beschrijving: Bevat de lijst met bekende allergenen die in snoepgoed kunnen voorkomen.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier van het allergeen |
| `Naam` | VARCHAR | 100 | Nee | - | - | Naam van het allergeen (bijv. Gluten, Soja) |
| `Omschrijving` | VARCHAR | 255 | Nee | - | - | Beschrijving van het allergeen |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

---

## 2. Koppeltabellen en Afhankelijke Tabellen

### 2.1 Tabel: `Magazijn`
Beschrijving: Bevat de actuele voorraad en verpakkingseenheid van producten in het magazijn.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier voorraadregel |
| `ProductId` | INT UNSIGNED | 10 | Nee | FK naar `Product(Id)` | - | Referentie naar het gekoppelde product |
| `VerpakkingsEenheid` | DECIMAL | 4,1 | Nee | - | - | Gewicht per verpakkingseenheid in kg |
| `AantalAanwezig` | INT | 10 | Ja | - | NULL | Aantal verpakkingen op voorraad (NULL = geen voorraad) |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_Magazijn_ProductId`: `ProductId` verwijst naar `Product(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.

---

### 2.2 Tabel: `ProductPerAllergeen`
Beschrijving: Koppeltabel tussen producten en allergenen (veel-op-veel relatie).

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier koppelregel |
| `ProductId` | INT UNSIGNED | 10 | Nee | FK naar `Product(Id)` | - | Referentie naar product |
| `AllergeenId` | INT UNSIGNED | 10 | Nee | FK naar `Allergeen(Id)` | - | Referentie naar allergeen |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_ProductPerAllergeen_ProductId`: `ProductId` verwijst naar `Product(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.
- `FK_ProductPerAllergeen_AllergeenId`: `AllergeenId` verwijst naar `Allergeen(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.

---

### 2.3 Tabel: `ProductPerLeverancier`
Beschrijving: Registreert leveringen van producten door leveranciers inclusief verwachte vervolglevering.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | INT UNSIGNED | 10 | Nee | PK (Auto Increment) | - | Unieke identifier leveringsregel |
| `LeverancierId` | INT UNSIGNED | 10 | Nee | FK naar `Leverancier(Id)` | - | Referentie naar de leverancier |
| `ProductId` | INT UNSIGNED | 10 | Nee | FK naar `Product(Id)` | - | Referentie naar het geleverde product |
| `DatumLevering` | DATE | - | Nee | - | - | Datum van levering |
| `Aantal` | INT | 10 | Nee | - | - | Aantal geleverde eenheden |
| `DatumEerstVolgendeLevering` | DATE | - | Ja | - | NULL | Verwachte datum van eerstvolgende levering |
| `IsActief` | BIT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_ProductPerLeverancier_LeverancierId`: `LeverancierId` verwijst naar `Leverancier(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.
- `FK_ProductPerLeverancier_ProductId`: `ProductId` verwijst naar `Product(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.
