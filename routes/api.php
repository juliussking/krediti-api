<?php

use App\Events\UserRegistered;
use App\Http\Controllers\Auth\CompanyRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ClientAddressController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SolicitationController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserAndClientCompanyForClient;
use App\Http\Middleware\CheckUserAndClientCompanyForCreatePayment;
use App\Http\Middleware\CheckUserAndClientCompanyForPayment;
use App\Http\Middleware\CheckUserAndClientCompanyForSolicitation;
use App\Http\Middleware\EnsureCompanyHasActivePlan;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    Route::post('login', LoginController::class);

    Route::post('logout', LogoutController::class);

    Route::post('company-register', CompanyRegisterController::class);

    Route::post('subscription', SubscriptionController::class);

    Route::post('verify-email', VerifyEmailController::class);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('plans', PlanController::class);
        Route::get('me', [MeController::class, 'show']);
        
        Route::middleware(EnsureCompanyHasActivePlan::class)->group(function () {

            Route::get('users', [UserController::class, 'show']);
            Route::post('user-register', [UserController::class, 'store']);

            Route::get('tasks', [TaskController::class, 'index']);
            Route::post('create-task', [TaskController::class, 'store']);
            Route::put('update-task/{id}', [TaskController::class, 'updateCompleted']);

            Route::get('info-company', [CompanyController::class]);

            Route::get('clients', [ClientController::class, 'index']);
            Route::get('clients/statistics', [ClientController::class, 'statistics']);
            Route::post('client-register', [ClientController::class, 'store']);

            Route::middleware(CheckUserAndClientCompanyForClient::class)->group(function () {

                Route::get('client-profile/{id}', [ClientController::class, 'show']);
                Route::delete('client-delete/{id}', [ClientController::class, 'destroy']);
                Route::put('client-update/{id}', [ClientController::class, 'update']);
                Route::put('client-address-update/{id}', [ClientAddressController::class, 'update']);

            });

            Route::get('solicitations', [SolicitationController::class, 'index']);
            Route::post('create-solicitation/{id}', [SolicitationController::class, 'store']);

            Route::middleware(CheckUserAndClientCompanyForSolicitation::class)->group(function () {

                Route::put('counteroffer-solicitation/{id}', [SolicitationController::class, 'counteroffer']);
                Route::delete('delete-solicitation/{id}', [SolicitationController::class, 'destroy']);
                Route::put('approve-solicitation/{id}', [SolicitationController::class, 'approve']);
                Route::put('recuse-solicitation/{id}', [SolicitationController::class, 'recuse']);
                Route::put('cancel-solicitation/{id}', [SolicitationController::class, 'cancel']);
                Route::put('update-solicitation/{id}', [SolicitationController::class, 'update']);

            });

            Route::get('payments', [PaymentController::class, 'show']);

            Route::post('create-payment/{id}', [PaymentController::class, 'store'])->middleware(CheckUserAndClientCompanyForCreatePayment::class);

            Route::middleware(CheckUserAndClientCompanyForPayment::class)->group(function () {

                Route::post('edit-payment/{id}', [PaymentController::class, 'update']);
                Route::delete('delete-payment/{id}', [PaymentController::class, 'destroy']);
                
            });
        });
    });
});
