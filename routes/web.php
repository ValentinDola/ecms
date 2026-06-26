<?php

use App\Http\Controllers\AssistanceCaseController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dev\DatabaseViewerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VisaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/search', [SearchController::class, 'index'])->name('search');

if (app()->environment(['local', 'testing', 'development'])) {
    Route::get('/dev/database-viewer', [DatabaseViewerController::class, 'index'])->name('dev.database-viewer');
}

Route::get('/reports', [ReportingController::class, 'index'])->name('reports.index');
Route::get('/reports/print', [ReportingController::class, 'print'])->name('reports.print');

Route::resource('citizens', CitizenController::class);

Route::get('/visas/citizens/lookup', [VisaController::class, 'citizenLookup'])->name('visas.citizens.lookup');
Route::resource('visas', VisaController::class);

Route::resource('assistance', AssistanceCaseController::class);

Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

Route::get('/print/citizen/{citizen}', [PrintController::class, 'citizen'])->name('print.citizen');
Route::get('/print/visa/{visa}', [PrintController::class, 'visa'])->name('print.visa');
Route::get('/print/case/{assistance}', [PrintController::class, 'case'])->name('print.case');
