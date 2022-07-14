<?php

use AliAlizade\Customer\Http\Controllers\CustomersController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1')
     ->middleware(['api'])
     ->group(function () {

         Route::prefix('/customers')->group(function () {
             Route::post('/', [CustomersController::class, 'store']);
         });
     });