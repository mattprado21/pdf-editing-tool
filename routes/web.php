<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/pdf/apryse/edit', [PdfController::class, 'editor']);   // viewer page
Route::post('/pdf/apryse/upload', [PdfController::class, 'upload']); // accept uploads
Route::post('/pdf/apryse/save', [PdfController::class, 'save']);     // save edited file
