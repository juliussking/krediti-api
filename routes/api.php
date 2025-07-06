<?php

use App\Events\UserRegistered;
use App\Http\Controllers\Auth\CompanyRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SolicitationController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Subscription;

Route::middleware('web')->group(function () {

Route::post('login', LoginController::class);
Route::post('logout', LogoutController::class);
Route::post('company-register', CompanyRegisterController::class);

Route::get('subscription', SubscriptionController::class);


Route::post('verify-email', VerifyEmailController::class);


Route::middleware(['auth:sanctum'])->group(function () {

Route::get('me', [MeController::class, 'show']);
Route::get('users', [UserController::class, 'show']);

Route::get('tasks', [TaskController::class, 'index']);
Route::post('create-task', [TaskController::class, 'store']);
Route::put('update-task/{id}', [TaskController::class, 'updateCompleted']);


Route::get('clients', [ClientController::class, 'index']);
Route::get('clients/statistics', [ClientController::class, 'statistics']);
Route::get('client-profile/{id}', [ClientController::class, 'show']);
Route::post('client-register', [ClientController::class, 'store']);
Route::delete('client-delete/{id}', [ClientController::class, 'destroy']);

Route::get('solicitations', [SolicitationController::class, 'index']);
Route::post('create-solicitation/{id}', [SolicitationController::class, 'store']);
Route::put('approve-solicitation/{id}', [SolicitationController::class, 'approve']);
Route::put('recuse-solicitation/{id}', [SolicitationController::class, 'recuse']);
Route::put('counteroffer-solicitation/{id}', [SolicitationController::class, 'counteroffer']);
Route::delete('delete-solicitation/{id}', [SolicitationController::class, 'destroy']);


});



});