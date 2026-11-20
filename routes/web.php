<?php

use App\Http\Controllers\AllergeenController;
use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\LeveringController;
use App\Http\Controllers\MagazijnController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $rol = Auth::user()->role;

    return view('dashboard', [
        'rol' => $rol,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // BE-opdracht 01: Overzicht Magazijn Jamin
    Route::get('/magazijn', [MagazijnController::class, 'index'])->name('magazijn.index');

    // BE-opdracht 01: Detailscherm Leveringsinformatie
    Route::get('/levering/{productId}', [LeveringController::class, 'show'])->name('levering.show');

    // BE-opdracht 02: Detailscherm Levering product toevoegen (Wireframe 4) via LeveringController
    Route::get('/levering/create/{leverancierId}/{productId}', [LeveringController::class, 'create'])->name('levering.create');
    Route::post('/levering/store/{leverancierId}/{productId}', [LeveringController::class, 'store'])->name('levering.store');

    // BE-opdracht 01: Detailscherm Allergeneninformatie
    Route::get('/allergeen/{productId}', [AllergeenController::class, 'show'])->name('allergeen.show');

    // BE-opdracht 02: Leveranciers beheer
    Route::get('/leverancier', [LeverancierController::class, 'index'])->name('leverancier.index');
    Route::get('/leverancier/{leverancierId}/producten', [LeverancierController::class, 'showProducten'])->name('leverancier.producten');
    Route::get('/leverancier/{leverancierId}/product/{productId}/levering', [LeverancierController::class, 'showLeveringForm'])->name('leverancier.levering.create');
    Route::post('/leverancier/{leverancierId}/product/{productId}/levering', [LeverancierController::class, 'slaLeveringOp'])->name('leverancier.levering.store');

    // BE-opdracht 03: Wijzigen Leveranciers
    Route::get('/leverancier/wijzigen', [LeverancierController::class, 'indexWijzigen'])->name('leverancier.wijzigen.index');
    Route::get('/leverancier/details/{leverancierId}', [LeverancierController::class, 'showDetails'])->name('leverancier.details');
    Route::get('/leverancier/wijzigen/{leverancierId}', [LeverancierController::class, 'edit'])->name('leverancier.edit');
    Route::post('/leverancier/wijzigen/{leverancierId}', [LeverancierController::class, 'update'])->name('leverancier.update');
});

require __DIR__.'/auth.php';
