<?php

use AliAlizade\Transfer\Http\Controllers\TransferMoneyController;
use Illuminate\Support\Facades\Route;


Route::prefix('/api/v1')
     ->group(function () {
         Route::post('/transfer', [TransferMoneyController::class, 'store']);
     });