<?php

use AliAlizade\Customer\Http\Controllers\CustomerBankAccountHistoryController;
use AliAlizade\Customer\Http\Controllers\CustomerBankAccountsController;
use AliAlizade\Customer\Http\Controllers\CustomersController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1')
     ->middleware(['api'])
     ->group(function () {

         Route::post('/customers', [CustomersController::class, 'store']);

         Route::get(
             '/customers/{customer}/accounts',
             [CustomerBankAccountsController::class, 'index']
         );

         Route::get('/accounts/{account}', [CustomerBankAccountsController::class, 'show']);
         Route::post('/accounts', [CustomerBankAccountsController::class, 'store']);

         Route::get(
             '/accounts/{account}/history',
             [CustomerBankAccountHistoryController::class, 'index']
         );
     });