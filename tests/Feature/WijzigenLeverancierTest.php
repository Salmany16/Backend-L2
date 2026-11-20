<?php

namespace Tests\Feature;

use App\Models\Leverancier;
use App\Models\User;
use Database\Seeders\JaminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WijzigenLeverancierTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JaminSeeder::class);

        $this->manager = User::firstOrCreate(
            ['email' => 'manager@jamin.nl'],
            [
                'name' => 'Manager',
                'role' => 'Manager',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
    }

    /**
     * Wireframe-01: Manager homepage bevat link 'Wijzigen Leveranciers'.
     */
    public function test_manager_homepage_contains_link_to_wijzigen_leveranciers(): void
    {
        $response = $this->actingAs($this->manager)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Wijzigen Leveranciers');
        $response->assertSee(route('leverancier.wijzigen.index'));
    }

    /**
     * Wireframe-02: Overzicht leveranciers toont server-side pagination met maximaal 4 records.
     */
    public function test_wireframe_02_overzicht_leveranciers_applies_pagination_max_4(): void
    {
        $response = $this->actingAs($this->manager)->get(route('leverancier.wijzigen.index'));

        $response->assertStatus(200);
        $response->assertSee('Overzicht Leveranciers');
        $response->assertSee('Astra Sweets');
        $response->assertSee('Venco');
        $response->assertSee('✏️');
        $response->assertSee('Pagina 1 van 2');
    }

    /**
     * Wireframe-03: Detailscherm van Astra Sweets toont alle gegevens inclusief adres.
     */
    public function test_wireframe_03_leverancier_details_shows_supplier_and_contact_info(): void
    {
        $astraSweetsId = 2;
        $response = $this->actingAs($this->manager)->get(route('leverancier.details', $astraSweetsId));

        $response->assertStatus(200);
        $response->assertSee('Leverancier Details');
        $response->assertSee('Astra Sweets');
        $response->assertSee('Jasper del Monte');
        $response->assertSee('L1029284315');
        $response->assertSee('Wijzig');
    }

    /**
     * User Story 01 - Scenario 01: Wijzigen van Mobiel en Straatnaam slaat op en toont melding.
     */
    public function test_scenario_01_wijzigen_mobiel_en_straatnaam_success(): void
    {
        $astraSweetsId = 2;

        // 1. Open Wireframe-04
        $formResponse = $this->actingAs($this->manager)->get(route('leverancier.edit', $astraSweetsId));
        $formResponse->assertStatus(200);
        $formResponse->assertSee('Wijzig Leveranciergegevens');

        // 2. Wijzig Mobiel in 06-39398825 en Straatnaam in Den Dolderlaan
        $postData = [
            'naam' => 'Astra Sweets',
            'contactpersoon' => 'Jasper del Monte',
            'leveranciernummer' => 'L1029284315',
            'mobiel' => '06-39398825',
            'straatnaam' => 'Den Dolderlaan',
            'huisnummer' => 2,
            'postcode' => '1067RC',
            'stad' => 'Utrecht',
        ];

        $updateResponse = $this->actingAs($this->manager)->post(route('leverancier.update', $astraSweetsId), $postData);

        // 3. Verwachte melding en redirect na 3 seconden
        $updateResponse->assertStatus(200);
        $updateResponse->assertSee('De wijzigingen zijn doorgevoerd');

        // 4. Controleer via Stored Procedure dat de gegevens daadwerkelijk zijn bijgewerkt in de database
        $bijgewerkt = Leverancier::getDetailsEnAdres($astraSweetsId);
        $this->assertEquals('06-39398825', $bijgewerkt->Mobiel);
        $this->assertEquals('Den Dolderlaan', $bijgewerkt->Straatnaam);
    }

    /**
     * User Story 01 - Scenario 02: Validatie foutmelding bij ontbrekende verplichte velden.
     */
    public function test_scenario_02_validatie_faalt_bij_lege_velden(): void
    {
        $astraSweetsId = 2;

        $postData = [
            'naam' => '',
            'contactpersoon' => '',
            'leveranciernummer' => 'L1029284315',
            'mobiel' => '',
            'straatnaam' => '',
            'huisnummer' => 'geen_getal',
            'postcode' => '',
            'stad' => '',
        ];

        $response = $this->actingAs($this->manager)->post(route('leverancier.update', $astraSweetsId), $postData);

        $response->assertSessionHasErrors(['naam', 'contactpersoon', 'mobiel', 'straatnaam', 'huisnummer', 'postcode', 'stad']);
    }
}
