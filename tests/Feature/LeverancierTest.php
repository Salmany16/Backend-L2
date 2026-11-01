<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\JaminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeverancierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JaminSeeder::class);
    }

    /**
     * Test 1: Wireframe-01 – Overzicht leveranciers wordt getoond.
     */
    public function test_overzicht_leveranciers_wordt_getoond(): void
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get(route('leverancier.index'));

        $response->assertStatus(200);
        $response->assertSee('Overzicht leveranciers');
        $response->assertSee('Venco');
        $response->assertSee('Astra Sweets');
        $response->assertSee('Haribo');
        $response->assertSee('Quality Street');
    }

    /**
     * Test 2: Wireframe-02 – Geleverde producten van leverancier worden getoond.
     */
    public function test_leverancier_producten_worden_getoond(): void
    {
        $user = User::first() ?? User::factory()->create();

        // Leverancier 1 = Venco, levert Mintnopjes, Schoolkrijt, Honingdrop
        $response = $this->actingAs($user)->get(route('leverancier.producten', 1));

        $response->assertStatus(200);
        $response->assertSee('Geleverde producten');
        $response->assertSee('Venco');
        $response->assertSee('Mintnopjes');
    }

    /**
     * Test 3: Wireframe-03 – Leverancier zonder producten toont de melding.
     */
    public function test_leverancier_zonder_producten_toont_melding(): void
    {
        $user = User::first() ?? User::factory()->create();

        // Leverancier 6 = Quality Street, heeft geen producten geleverd
        $response = $this->actingAs($user)->get(route('leverancier.producten', 6));

        $response->assertStatus(200);
        $response->assertSee('Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin');
    }

    /**
     * Test 4: Succesvolle nieuwe levering toevoegen voor een actief product.
     */
    public function test_nieuwe_levering_toevoegen_succesvol(): void
    {
        $user = User::first() ?? User::factory()->create();

        // Mintnopjes (ProductId=1) van Venco (LeverancierId=1)
        $response = $this->actingAs($user)->post(
            route('leverancier.levering.store', [1, 1]),
            [
                'aantal' => 25,
                'datum_eerstvolgende' => '2026-11-15',
            ]
        );

        $response->assertRedirect(route('leverancier.producten', 1));

        // Controleer dat de levering is opgeslagen
        $this->assertDatabaseHas('ProductPerLeverancier', [
            'LeverancierId' => 1,
            'ProductId' => 1,
            'Aantal' => 25,
        ]);
    }

    /**
     * Test 5: Levering voor inactief product toont foutmelding.
     */
    public function test_nieuwe_levering_inactief_product_toont_foutmelding(): void
    {
        $user = User::first() ?? User::factory()->create();

        // Winegums (ProductId=10) is IsActief=0
        $response = $this->actingAs($user)->post(
            route('leverancier.levering.store', [4, 10]),
            [
                'aantal' => 10,
                'datum_eerstvolgende' => '2026-11-20',
            ]
        );

        $response->assertStatus(200);
        $response->assertSee('wordt niet meer geproduceerd');
    }
}
