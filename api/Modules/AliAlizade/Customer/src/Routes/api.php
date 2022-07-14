<?php

use AliAlizade\Customer\Http\Controllers\CustomerBankAccountsController;
use AliAlizade\Customer\Http\Controllers\CustomersController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1')
     ->middleware(['api'])
     ->group(function () {

         Route::post('/customers', [CustomersController::class, 'store']);

         Route::get('/accounts/{account}', [CustomerBankAccountsController::class, 'show']);
     });