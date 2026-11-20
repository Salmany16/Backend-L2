<?php

namespace Tests\Unit;

use App\Models\Leverancier;
use Database\Seeders\JaminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeverancierUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JaminSeeder::class);
    }

    /**
     * Unit Test 1: Test dat getOverzichtPaginering maximaal het opgegeven limiet aantal records teruggeeft.
     */
    public function test_leverancier_get_overzicht_paginering_respects_limit(): void
    {
        $limit = 4;
        $leveranciers = Leverancier::getOverzichtPaginering(0, $limit);

        $this->assertIsArray($leveranciers);
        $this->assertLessThanOrEqual($limit, count($leveranciers));
        $this->assertNotEmpty($leveranciers);
        $this->assertObjectHasProperty('Naam', $leveranciers[0]);
        $this->assertObjectHasProperty('LeverancierNummer', $leveranciers[0]);
    }

    /**
     * Unit Test 2: Test dat getDetailsEnAdres zowel leveranciers- als contactgegevens ophaalt via Stored Procedure.
     */
    public function test_leverancier_get_details_en_adres_returns_complete_data(): void
    {
        $astraSweetsId = 2;
        $details = Leverancier::getDetailsEnAdres($astraSweetsId);

        $this->assertNotNull($details);
        $this->assertEquals('Astra Sweets', $details->Naam);
        $this->assertObjectHasProperty('Straatnaam', $details);
        $this->assertObjectHasProperty('Huisnummer', $details);
        $this->assertObjectHasProperty('Postcode', $details);
        $this->assertObjectHasProperty('Stad', $details);
    }
}
