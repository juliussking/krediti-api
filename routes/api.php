<?php

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
use App\Http\Middleware\CheckIfUserForPainelAccess;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckUserAndClientCompany;
use App\Http\Middleware\EnsureCompanyHasActivePlan;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    Route::post('login', LoginController::class)->middleware(CheckIfUserForPainelAccess::class);

    Route::post('logout', LogoutController::class);

    Route::post('company-register', CompanyRegisterController::class);

    Route::post('subscription', SubscriptionController::class);

    Route::post('verify-email', VerifyEmailController::class);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('plans', PlanController::class);
        Route::get('me', [MeController::class, 'show']);

        Route::middleware(EnsureCompanyHasActivePlan::class)->group(function () {

            Route::get('users', [UserController::class, 'show']);
            Route::get('users/statistics', [UserController::class, 'statistics']);
            Route::post('user-register', [UserController::class, 'store'])->middleware(CheckPermission::class . ':criar usuarios');

            Route::get('tasks', [TaskController::class, 'index']);
            Route::post('create-task', [TaskController::class, 'store'])->middleware(CheckPermission::class . ':criar tarefas');
            Route::put('update-task/{id}', [TaskController::class, 'updateCompleted'])->middleware(CheckPermission::class . ':editar tarefas');

            Route::get('info-company', [CompanyController::class]);

            Route::get('clients', [ClientController::class, 'index']);
            Route::get('clients/statistics', [ClientController::class, 'statistics']);
            Route::post('client-register', [ClientController::class, 'store'])->middleware(CheckPermission::class . ':criar clientes');

            Route::middleware(CheckUserAndClientCompany::class . ':client')->group(function () {

                Route::get('client-profile/{id}', [ClientController::class, 'show']);
                Route::delete('client-delete/{id}', [ClientController::class, 'destroy'])->middleware(CheckPermission::class . ':deletar clientes');
                Route::put('client-update/{id}', [ClientController::class, 'update'])->middleware(CheckPermission::class . ':editar clientes');
                Route::put('client-address-update/{id}', [ClientAddressController::class, 'update'])->middleware(CheckPermission::class . ':editar clientes');
            });

            Route::get('solicitations', [SolicitationController::class, 'index']);
            Route::post('create-solicitation/{id}', [SolicitationController::class, 'store'])->middleware(CheckPermission::class . ':criar solicitações');

            Route::middleware(CheckUserAndClientCompany::class . ':solicitation')->group(function () {

                Route::put('counteroffer-solicitation/{id}', [SolicitationController::class, 'counteroffer']);
                Route::delete('delete-solicitation/{id}', [SolicitationController::class, 'destroy'])->middleware(CheckPermission::class . ':deletar solicitações');
                Route::put('approve-solicitation/{id}', [SolicitationController::class, 'approve'])->middleware(CheckPermission::class . ':aprovar solicitações');
                Route::put('recuse-solicitation/{id}', [SolicitationController::class, 'recuse'])->middleware(CheckPermission::class . ':recusar solicitações');
                Route::put('cancel-solicitation/{id}', [SolicitationController::class, 'cancel'])->middleware(CheckPermission::class . ':cancelar solicitações');
                Route::put('update-solicitation/{id}', [SolicitationController::class, 'update'])->middleware(CheckPermission::class . ':editar solicitações');
            });

            Route::get('payments', [PaymentController::class, 'show']);

            Route::post('create-payment/{id}', [PaymentController::class, 'store'])->middleware(
                [
                    CheckUserAndClientCompany::class . ':liberation',
                    CheckPermission::class . ':criar pagamentos'
                ]
            );

            Route::middleware(CheckUserAndClientCompany::class . ':payment')->group(function () {

                Route::post('edit-payment/{id}', [PaymentController::class, 'update']);
                Route::delete('delete-payment/{id}', [PaymentController::class, 'destroy']);
            });
        });
    });
});
