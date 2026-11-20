# Testrapport - BE-opdracht 03: Wijzigen Leverancier

**Opdracht:** BE-opdracht 03  
**Tester:** YousufzaiS  
**Klas:** IO-SD-2509AB  
**Datum:** 20-11-2026  
**Resultaat:** Alle tests geslaagd (100% PASS)  

---

## 1. Testuitvoering Samenvatting

Alle testgevallen uit het testplan zijn zowel geautomatiseerd (via Pest / PHPUnit testsuite) als handmatig in de browser uitgevoerd.

| Test ID | Omschrijving | Verwacht Resultaat | Werkelijk Resultaat | Status |
| :--- | :--- | :--- | :--- | :---: |
| **TG-01** | Link "Wijzigen Leveranciers" op homepage | Link aanwezig en functioneel | Link zichtbaar op `/dashboard` en leidt naar `/leverancier/wijzigen` | **PASS** |
| **TG-02** | Overzicht leveranciers met paginering (max 4) | Maximaal 4 rijen per pagina, pen-icoon ✏️ per rij | Pagina 1 toont 4 records, pagina 2 toont overige 2 records | **PASS** |
| **TG-03** | Detailscherm Astra Sweets (Wireframe-03) | Alle velden inclusief adresgegevens zichtbaar | Data correct opgehaald via `sp_GetLeverancierDetailsEnAdres` | **PASS** |
| **TG-04** | Scenario_01: Mobiel & Straatnaam wijzigen | Melding "De wijzigingen zijn doorgevoerd" + 3 sec redirect + DB update | Mobiel bijgewerkt naar `06-39398825`, straat naar `Den Dolderlaan`, succesmelding en redirect werken | **PASS** |
| **TG-05** | Scenario_02: Validatie bij lege velden | Foutmelding en geen opslag | Validatiefouten netjes gerenderd, geen ongeldige data in DB | **PASS** |
| **TG-06** | Unit Test 1: `getOverzichtPaginering` limiet | Retourneert max 4 rijen | Precies 4 records geretourneerd in unit test | **PASS** |
| **TG-07** | Unit Test 2: `getDetailsEnAdres` data | Bevat alle adres- en contactvelden | Alle velden succesvol geassert in unit test | **PASS** |

---

## 2. Geautomatiseerde Testresultaten

De geautomatiseerde testsuite is uitgevoerd via het commando:
```powershell
php artisan test --compact tests/Unit/LeverancierUnitTest.php tests/Feature/WijzigenLeverancierTest.php tests/Feature/LeverancierTest.php tests/Feature/MagazijnJaminTest.php
```

### Resultaat:
```text
Pass: 17 tests passed (81 assertions)
Duration: 2.81s
```

---

## 3. Conclusie
De functionaliteit **Wijzigen Leverancier** voldoet aan alle gestelde eisen van BE-opdracht 03:
- Volledig gebouwd volgens het MVC-patroon en Object-Georiënteerd Programmeren (OOP).
- Uitsluitend gebruik van MySQL Stored Procedures voor alle data-interacties.
- Server-side pagination toegepast met maximaal 4 records per pagina.
- Scenario 01 en Scenario 02 zijn succesvol gerealiseerd en geverifieerd.
