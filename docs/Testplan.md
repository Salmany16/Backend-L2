# Testplan - BE-opdracht 03: Wijzigen Leverancier

**Opdracht:** BE-opdracht 03  
**Auteur:** YousufzaiS  
**Klas:** IO-SD-2509AB  
**Datum:** 20-11-2026  
**Status:** Definitief  

---

## 1. Inleiding & Doel
Dit testplan beschrijft de testaanpak voor de functionaliteit **Wijzigen Leverancier** (User Story 01). Het doel is vaststellen of alle scenario's, wireframes en stored procedures correct functioneren conform de specificaties van BE-opdracht 03.

---

## 2. Testgevallen Matrix

| Test ID | Onderdeel | Scenario / Omschrijving | Pre-conditie | Input / Actie | Verwacht Resultaat |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TG-01** | Wireframe-01 | Link "Wijzigen Leveranciers" op homepage | Ingelogd als Manager | Navigeer naar `/dashboard` | Link "Wijzigen Leveranciers" is zichtbaar en klikbaar naar `/leverancier/wijzigen`. |
| **TG-02** | Wireframe-02 | Overzicht leveranciers met paginering (max 4) | Ingelogd als Manager | Klik op "Wijzigen Leveranciers" | Tabel toont maximaal 4 rijen op pagina 1 (Venco, Astra Sweets, Haribo, Basset) en paginatieknoppen naar pagina 2. Pen-icoon ✏️ is aanwezig in kolom 'Leverancier Details'. |
| **TG-03** | Wireframe-03 | Detailscherm Astra Sweets | Op overzichtspagina | Klik op pen-icoon ✏️ bij Astra Sweets | Pagina toont alle gegevens: Naam, Contactpersoon, Leveranciernummer, Mobiel, Straatnaam, Huisnummer, Postcode, Stad. Knoppen 'Wijzig', 'Terug' en 'Home' zijn aanwezig. |
| **TG-04** | Scenario_01 (WF-04) | Wijzigen van Mobiel en Straatnaam | Op detailscherm Astra Sweets | 1. Klik op button 'Wijzig'<br>2. Verander Mobiel in `06-39398825`<br>3. Verander Straatnaam in `Den Dolderlaan`<br>4. Klik op 'Sla Op' | 1. Melding "De wijzigingen zijn doorgevoerd" verschijnt.<br>2. Na 3 seconden volgt automatische redirect naar Leverancier Details.<br>3. Gewijzigde gegevens zijn opgeslagen in database via Stored Procedure. |
| **TG-05** | Scenario_02 | Validatiefout bij ontbrekende invoer | Op wijzigingsformulier | Maak verplichte velden (bijv. Straatnaam of Mobiel) leeg en klik op 'Sla Op' | Formulier toont validatiefoutmeldingen en blokkeert opslag. Gegevens in database blijven ongewijzigd. |
| **TG-06** | Unit Test 1 | Paginering limiet van `getOverzichtPaginering` | Database gevuld met testdata | Roep `Leverancier::getOverzichtPaginering(0, 4)` aan | Methode retourneert een array met maximaal 4 objecten. |
| **TG-07** | Unit Test 2 | Data integriteit van `getDetailsEnAdres` | Database gevuld met testdata | Roep `Leverancier::getDetailsEnAdres(2)` aan | Retourneert leveranciers- én contactadresvelden (Straatnaam, Huisnummer, Postcode, Stad) via Stored Procedure. |
