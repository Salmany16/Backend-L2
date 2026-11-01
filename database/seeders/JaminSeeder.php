<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ProductPerLeverancier')->delete();
        DB::table('ProductPerAllergeen')->delete();
        DB::table('Magazijn')->delete();
        DB::table('Allergeen')->delete();
        DB::table('Leverancier')->delete();
        DB::table('Product')->delete();

        // 1. Producten
        DB::table('Product')->insert([
            ['Id' => 1, 'Naam' => 'Mintnopjes', 'Barcode' => '8719587231278', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'Naam' => 'Schoolkrijt', 'Barcode' => '8719587326713', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'Naam' => 'Honingdrop', 'Barcode' => '8719587327836', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'Naam' => 'Zure Beren', 'Barcode' => '8719587321441', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'Naam' => 'Cola Flesjes', 'Barcode' => '8719587321237', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'Naam' => 'Turtles', 'Barcode' => '8719587322245', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 7, 'Naam' => 'Witte Muizen', 'Barcode' => '8719587328256', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 8, 'Naam' => 'Reuzen Slangen', 'Barcode' => '8719587325641', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 9, 'Naam' => 'Zoute Rijen', 'Barcode' => '8719587322739', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 10, 'Naam' => 'Winegums', 'Barcode' => '8719587327527', 'IsActief' => 0, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 11, 'Naam' => 'Drop Munten', 'Barcode' => '8719587322345', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 12, 'Naam' => 'Kruis Drop', 'Barcode' => '8719587322265', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 13, 'Naam' => 'Zoute Ruitjes', 'Barcode' => '8719587323256', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        // 2. Leveranciers
        DB::table('Leverancier')->insert([
            ['Id' => 1, 'Naam' => 'Venco', 'ContactPersoon' => 'Bert van Linge', 'LeverancierNummer' => 'L1029384719', 'Mobiel' => '06-28493827', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'Naam' => 'Astra Sweets', 'ContactPersoon' => 'Jasper del Monte', 'LeverancierNummer' => 'L1029284315', 'Mobiel' => '06-39398734', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'Naam' => 'Haribo', 'ContactPersoon' => 'Sven Stalman', 'LeverancierNummer' => 'L1029324748', 'Mobiel' => '06-24383291', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'Naam' => 'Basset', 'ContactPersoon' => 'Joyce Stelterberg', 'LeverancierNummer' => 'L1023845773', 'Mobiel' => '06-48293823', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'Naam' => 'De Bron', 'ContactPersoon' => 'Remco Veenstra', 'LeverancierNummer' => 'L1023857736', 'Mobiel' => '06-34291234', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'Naam' => 'Quality Street', 'ContactPersoon' => 'Johan Nooij', 'LeverancierNummer' => 'L1029234586', 'Mobiel' => '06-23458456', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        // 3. Allergenen
        DB::table('Allergeen')->insert([
            ['Id' => 1, 'Naam' => 'Gluten', 'Omschrijving' => 'Dit product bevat gluten', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'Naam' => 'Gelatine', 'Omschrijving' => 'Dit product bevat gelatine', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'Naam' => 'AZO-Kleurstof', 'Omschrijving' => 'Dit product bevat AZO-kleurstoffen', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'Naam' => 'Lactose', 'Omschrijving' => 'Dit product bevat lactose', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'Naam' => 'Soja', 'Omschrijving' => 'Dit product bevat soja', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        // 4. Magazijn
        DB::table('Magazijn')->insert([
            ['Id' => 1, 'ProductId' => 1, 'VerpakkingsEenheid' => 5.0, 'AantalAanwezig' => 453, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'ProductId' => 2, 'VerpakkingsEenheid' => 2.5, 'AantalAanwezig' => 400, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'ProductId' => 3, 'VerpakkingsEenheid' => 5.0, 'AantalAanwezig' => 1, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'ProductId' => 4, 'VerpakkingsEenheid' => 1.0, 'AantalAanwezig' => 800, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'ProductId' => 5, 'VerpakkingsEenheid' => 3.0, 'AantalAanwezig' => 234, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'ProductId' => 6, 'VerpakkingsEenheid' => 2.0, 'AantalAanwezig' => 345, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 7, 'ProductId' => 7, 'VerpakkingsEenheid' => 1.0, 'AantalAanwezig' => 795, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 8, 'ProductId' => 8, 'VerpakkingsEenheid' => 10.0, 'AantalAanwezig' => 233, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 9, 'ProductId' => 9, 'VerpakkingsEenheid' => 2.5, 'AantalAanwezig' => 123, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 10, 'ProductId' => 10, 'VerpakkingsEenheid' => 3.0, 'AantalAanwezig' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 11, 'ProductId' => 11, 'VerpakkingsEenheid' => 2.0, 'AantalAanwezig' => 367, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 12, 'ProductId' => 12, 'VerpakkingsEenheid' => 1.0, 'AantalAanwezig' => 467, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 13, 'ProductId' => 13, 'VerpakkingsEenheid' => 5.0, 'AantalAanwezig' => 20, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        // 5. ProductPerAllergeen
        DB::table('ProductPerAllergeen')->insert([
            ['Id' => 1, 'ProductId' => 1, 'AllergeenId' => 2, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'ProductId' => 1, 'AllergeenId' => 1, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'ProductId' => 1, 'AllergeenId' => 3, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'ProductId' => 3, 'AllergeenId' => 4, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'ProductId' => 6, 'AllergeenId' => 5, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'ProductId' => 9, 'AllergeenId' => 2, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 7, 'ProductId' => 9, 'AllergeenId' => 5, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 8, 'ProductId' => 10, 'AllergeenId' => 2, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 9, 'ProductId' => 12, 'AllergeenId' => 4, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 10, 'ProductId' => 13, 'AllergeenId' => 1, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 11, 'ProductId' => 13, 'AllergeenId' => 4, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 12, 'ProductId' => 13, 'AllergeenId' => 5, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);

        // 6. ProductPerLeverancier
        DB::table('ProductPerLeverancier')->insert([
            ['Id' => 1, 'LeverancierId' => 1, 'ProductId' => 1, 'DatumLevering' => '2024-10-09', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => '2024-10-16', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 2, 'LeverancierId' => 1, 'ProductId' => 1, 'DatumLevering' => '2024-10-18', 'Aantal' => 21, 'DatumEerstVolgendeLevering' => '2024-10-25', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 3, 'LeverancierId' => 1, 'ProductId' => 2, 'DatumLevering' => '2024-10-09', 'Aantal' => 12, 'DatumEerstVolgendeLevering' => '2024-10-16', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 4, 'LeverancierId' => 1, 'ProductId' => 3, 'DatumLevering' => '2024-10-10', 'Aantal' => 11, 'DatumEerstVolgendeLevering' => '2024-10-17', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 5, 'LeverancierId' => 2, 'ProductId' => 4, 'DatumLevering' => '2024-10-14', 'Aantal' => 16, 'DatumEerstVolgendeLevering' => '2024-10-21', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 6, 'LeverancierId' => 2, 'ProductId' => 4, 'DatumLevering' => '2024-10-21', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => '2024-10-28', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 7, 'LeverancierId' => 2, 'ProductId' => 5, 'DatumLevering' => '2024-10-14', 'Aantal' => 45, 'DatumEerstVolgendeLevering' => '2024-10-21', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 8, 'LeverancierId' => 2, 'ProductId' => 6, 'DatumLevering' => '2024-10-14', 'Aantal' => 30, 'DatumEerstVolgendeLevering' => '2024-10-21', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 9, 'LeverancierId' => 3, 'ProductId' => 7, 'DatumLevering' => '2024-10-12', 'Aantal' => 12, 'DatumEerstVolgendeLevering' => '2024-10-19', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 10, 'LeverancierId' => 3, 'ProductId' => 7, 'DatumLevering' => '2024-10-19', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => '2024-10-26', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 11, 'LeverancierId' => 3, 'ProductId' => 8, 'DatumLevering' => '2024-10-10', 'Aantal' => 12, 'DatumEerstVolgendeLevering' => '2024-10-17', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 12, 'LeverancierId' => 3, 'ProductId' => 9, 'DatumLevering' => '2024-10-11', 'Aantal' => 1, 'DatumEerstVolgendeLevering' => '2024-10-18', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 13, 'LeverancierId' => 4, 'ProductId' => 10, 'DatumLevering' => '2024-10-16', 'Aantal' => 24, 'DatumEerstVolgendeLevering' => '2024-10-30', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 14, 'LeverancierId' => 5, 'ProductId' => 11, 'DatumLevering' => '2024-10-10', 'Aantal' => 47, 'DatumEerstVolgendeLevering' => '2024-10-17', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 15, 'LeverancierId' => 5, 'ProductId' => 11, 'DatumLevering' => '2024-10-19', 'Aantal' => 60, 'DatumEerstVolgendeLevering' => '2024-10-26', 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 16, 'LeverancierId' => 5, 'ProductId' => 12, 'DatumLevering' => '2024-10-11', 'Aantal' => 45, 'DatumEerstVolgendeLevering' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
            ['Id' => 17, 'LeverancierId' => 5, 'ProductId' => 13, 'DatumLevering' => '2024-10-12', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => null, 'IsActief' => 1, 'Opmerking' => null, 'DatumAangemaakt' => now(), 'DatumGewijzigd' => now()],
        ]);
    }
}
