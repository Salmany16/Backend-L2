# Class Diagram - Magazijn Jamin (BE-opdracht 03)

**Opdracht:** BE-opdracht 03  
**Auteur:** YousufzaiS  
**Klas:** IO-SD-2509AB  
**Onderdeel:** Wijzigen Leverancier  

---

## 1. Mermaid Klassendiagram

```mermaid
classDiagram
    direction TB

    class Leverancier {
        +int Id
        +int ContactId
        +string Naam
        +string ContactPersoon
        +string LeverancierNummer
        +string Mobiel
        +bool IsActief
        +string Opmerking
        +DateTime DatumAangemaakt
        +DateTime DatumGewijzigd
        +getOverzicht() array$
        +getGeleverdeProducten(int leverancierId) array$
        +getById(int id) object$
        +getOverzichtPaginering(int offset, int limit) array$
        +getCount() int$
        +getDetailsEnAdres(int leverancierId) object$
        +updateEnContact(int leverancierId, string naam, string contactPersoon, string leverancierNummer, string mobiel, string straatnaam, int huisnummer, string postcode, string stad) void$
        +slaLeveringOp(int leverancierId, int productId, int aantal, string datumEerstVolgende) void$
    }

    class Contact {
        +int Id
        +string Straat
        +int Huisnummer
        +string Postcode
        +string Stad
        +bool IsActief
        +string Opmerking
        +DateTime DatumAangemaakt
        +DateTime DatumGewijzigd
    }

    class Product {
        +int Id
        +string Naam
        +string Barcode
        +bool IsActief
        +string Opmerking
        +DateTime DatumAangemaakt
        +DateTime DatumGewijzigd
        +getMagazijnOverzicht() array$
        +getById(int id) object$
        +magazijn() HasOne
    }

    class Magazijn {
        +int Id
        +int ProductId
        +float VerpakkingsEenheid
        +int AantalAanwezig
        +bool IsActief
        +string Opmerking
        +DateTime DatumAangemaakt
        +DateTime DatumGewijzigd
        +product() BelongsTo
    }

    class Allergeen {
        +int Id
        +string Naam
        +string Omschrijving
        +bool IsActief
        +string Opmerking
        +DateTime DatumAangemaakt
        +DateTime DatumGewijzigd
        +getAllergenenVoorProduct(int productId) array$
    }

    class Levering {
        +int Id
        +int LeverancierId
        +int ProductId
        +Date DatumLevering
        +int Aantal
        +Date DatumEerstVolgendeLevering
        +bool IsActief
        +string Opmerking
        +DateTime DatumAangemaakt
        +DateTime DatumGewijzigd
        +getLeveringDetails(int productId) array$
        +getProductLeveringDetails(int leverancierId, int productId) object$
        +voegLeveringToe(int leverancierId, int productId, int aantal, string datumEerstvolgende) void$
    }

    class LeverancierController {
        +index() View
        +showProducten(int leverancierId) View
        +showLeveringForm(int leverancierId, int productId) View
        +slaLeveringOp(Request request, int leverancierId, int productId) RedirectResponse
        +indexWijzigen(Request request) View
        +showDetails(int leverancierId) View
        +edit(int leverancierId) View
        +update(Request request, int leverancierId) View
    }

    Leverancier "1" --> "0..1" Contact : heeft adres
    Product "1" --> "0..1" Magazijn : heeft voorraad
    Leverancier "1" --> "*" Levering : levert
    Product "1" --> "*" Levering : geleverd via
    Product "*" --> "*" Allergeen : bevat
    LeverancierController ..> Leverancier : gebruikt via Stored Procedures
    LeverancierController ..> Contact : beheert
```

---

## 2. Toelichting Architectuur (MVC & OOP)

1. **Model Laag (OOP)**:
   - Alle modellen vertegenwoordigen de entiteiten in de database.
   - Alle databasequery's worden afgehandeld via **MySQL Stored Procedures** (geen inline SQL in de applicatielaag).
2. **Controller Laag**:
   - `LeverancierController` fungeert als de dirigent tussen HTTP verzoeken en de modellen.
   - Valideert formulierinvoer en geeft bewerkte data door aan de views.
3. **View Laag (Blade)**:
   - `leverancier.wijzigen-overzicht`: Wireframe-02 met server-side pagination (max 4 records per pagina).
   - `leverancier.details`: Wireframe-03 toont detailkaart met leveranciers- en contactgegevens.
   - `leverancier.wijzigen-form`: Wireframe-04 bewerkingsformulier met 3-seconden redirect na succesvolle opslag.
