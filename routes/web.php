<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

// Vue SPA entry
Route::get('/', function () { return view('welcome'); });

Route::get('/pdf/apryse/edit', [PdfController::class, 'editor']);   // viewer page
Route::post('/pdf/apryse/upload', [PdfController::class, 'upload']); // accept uploads
Route::post('/pdf/apryse/save', [PdfController::class, 'save']);     // save edited file
// Fabric.js + pdfjs-dist editor routes
Route::get('/pdf/fabric/edit', [PdfController::class, 'fabricEditor']);
Route::post('/pdf/fabric/upload', [PdfController::class, 'upload']);
Route::post('/pdf/fabric/save', [PdfController::class, 'save']);

// Let Vue Router handle front-end routes
Route::get('/{any}', function () { return view('welcome'); })->where('any', '^(?!pdf/).*$');
