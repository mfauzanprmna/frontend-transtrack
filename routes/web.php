<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\SarimaxController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\XGBoostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/', 'HomeController@predictAndRenderFamilyChart')->name('home.forecast');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::prefix('dataset')->name('dataset.')->group(function () {
        Route::get('/', [DatasetController::class, 'index'])->name('index');
        Route::get('/create', [DatasetController::class, 'create'])->name('create');
        Route::post('/store', [DatasetController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [DatasetController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DatasetController::class, 'update'])->name('update');
        Route::delete('/{id}', [DatasetController::class, 'destroy'])->name('destroy');
        Route::post('/', [DatasetController::class, 'import'])->name('import');
        Route::get('/export', [DatasetController::class, 'export'])->name('export');
    });


    Route::post('/dataset/family-values', [DatasetController::class, 'getFamilyValues'])->name('dataset.family.values');

    Route::post('/predict', [SarimaxController::class, 'predict'])->name('sarimax.predict');
    Route::post('/train', [SarimaxController::class, 'train'])->name('sarimax.train');

    Route::post('/xgboost/train', [XGBoostController::class, 'train'])->name('xgboost-train');

    Route::get('/forecast', [XGBoostController::class, 'index'])->name('restock');
    Route::post('/restock', [XGBoostController::class, 'predict'])->name('restock.predict');

    Route::get('/train', [TrainController::class, 'index'])->name('train');

    Route::get('/qa', [AIController::class, 'index'])->name('deepseek.index');
    Route::post('/qa', [AIController::class, 'askDeepSeek'])->name('deepseek.ask');

    Route::get('/stock/recommendation', [TrainController::class, 'stockView'])->name('stock.recommendation.view');
    Route::post('/stock/recommendation', [TrainController::class, 'getRecommendation'])->name('stock.recommendation');
});
