<?php

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// XML Sitemap for Search Engines
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// Catch-all SPA Route
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

