<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Product', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Naam', 100);
            $table->string('Barcode', 20);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->useCurrent();
        });

        Schema::create('Leverancier', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Naam', 100);
            $table->string('ContactPersoon', 100);
            $table->string('LeverancierNummer', 20);
            $table->string('Mobiel', 20);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->useCurrent();
        });

        Schema::create('Allergeen', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Naam', 100);
            $table->string('Omschrijving', 255);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->useCurrent();
        });

        Schema::create('Magazijn', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->decimal('VerpakkingsEenheid', 4, 1);
            $table->integer('AantalAanwezig')->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->useCurrent();

            $table->foreign('ProductId')->references('Id')->on('Product')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('ProductPerAllergeen', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('AllergeenId');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->useCurrent();

            $table->foreign('ProductId')->references('Id')->on('Product')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('AllergeenId')->references('Id')->on('Allergeen')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('ProductPerLeverancier', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('LeverancierId');
            $table->unsignedInteger('ProductId');
            $table->date('DatumLevering');
            $table->integer('Aantal');
            $table->date('DatumEerstVolgendeLevering')->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->useCurrent();

            $table->foreign('LeverancierId')->references('Id')->on('Leverancier')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ProductId')->references('Id')->on('Product')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductPerLeverancier');
        Schema::dropIfExists('ProductPerAllergeen');
        Schema::dropIfExists('Magazijn');
        Schema::dropIfExists('Allergeen');
        Schema::dropIfExists('Leverancier');
        Schema::dropIfExists('Product');
    }
};
