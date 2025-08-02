<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BarangController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RenterController;
use App\Http\Controllers\Backend\RentingController;
use App\Http\Controllers\Backend\ReturnController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\BagController;
use App\Http\Controllers\Frontend\BarangController as FrontendBarangController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\RentController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\LoadSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::prefix('panel')
    ->name('panel.')
    ->middleware([LoadSettings::class, 'auth', AdminOnly::class])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)
            ->names('categories')
            ->except('create', 'show', 'edit');

        Route::resource('barangs', BarangController::class)
            ->names('barangs');

        Route::resource('renters', RenterController::class)
            ->names('renters')
            ->except('store', 'edit');

        Route::resource('admins', AdminController::class)
            ->names('admins')
            ->except('show');

        Route::resource('rentings', RentingController::class)
            ->names('rentings')
            ->only('index', 'show', 'destroy');
        Route::post('rentings/download', [RentingController::class, 'download'])->name('rentings.download');
        Route::get('rentings/download/pdf', [RentingController::class, 'pdf'])->name('rentings.pdf');
        Route::get('rentings/kirim-pengingat', [RentingController::class, 'kirimPengingat'])->name('rentings.kirim-pengingat');

        Route::resource('returns', ReturnController::class)
            ->names('returns')
            ->only('index', 'update');
        Route::put('returns/{return}/update-lost', [ReturnController::class, 'updateLost'])->name('returns.update-lost');

        Route::resource('settings', SettingController::class)
            ->names('settings')
            ->only('index', 'store');
    });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('barangs', [FrontendBarangController::class, 'index'])->name('barangs');
Route::get('barangs/{barang}', [FrontendBarangController::class, 'show'])->name('barangs.show');

Route::middleware([LoadSettings::class, 'auth'])->group(function () {
    Route::resource('bags', BagController::class)
        ->names('bags')
        ->only('index', 'store', 'destroy');

    Route::resource('rents', RentController::class)
        ->names('rents')
        ->only('store');

    Route::resource('account', AccountController::class)
        ->names('account')
        ->only('index', 'update');

    Route::put('account/{account}/password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');
});
