<?php

use Crater\Http\Controllers\AppVersionController;
use Crater\Http\Controllers\V1\Auth\ForgotPasswordController;
use Crater\Http\Controllers\V1\Auth\ResetPasswordController;
use Crater\Http\Controllers\V1\Backup\BackupsController;
use Crater\Http\Controllers\V1\Backup\DownloadBackupController;
use Crater\Http\Controllers\V1\Dashboard\DashboardController;
use Crater\Http\Controllers\V1\General\BootstrapController;
use Crater\Http\Controllers\V1\General\CountriesController;
use Crater\Http\Controllers\V1\General\CurrenciesController;
use Crater\Http\Controllers\V1\General\DateFormatsController;
use Crater\Http\Controllers\V1\General\FiscalYearsController;
use Crater\Http\Controllers\V1\General\LanguagesController;
use Crater\Http\Controllers\V1\General\NotesController;
use Crater\Http\Controllers\V1\General\SearchController;
use Crater\Http\Controllers\V1\General\TimezonesController;
use Crater\Http\Controllers\V1\Mobile\AuthController;
use Crater\Http\Controllers\V1\Onboarding\AppDomainController;
use Crater\Http\Controllers\V1\Onboarding\DatabaseConfigurationController;
use Crater\Http\Controllers\V1\Onboarding\FinishController;
use Crater\Http\Controllers\V1\Onboarding\LoginController;
use Crater\Http\Controllers\V1\Onboarding\OnboardingWizardController;
use Crater\Http\Controllers\V1\Onboarding\PermissionsController;
use Crater\Http\Controllers\V1\Onboarding\RequirementsController;
use Crater\Http\Controllers\V1\Settings\CompanyController;
use Crater\Http\Controllers\V1\Settings\DiskController;
use Crater\Http\Controllers\V1\Settings\GetCompanySettingsController;
use Crater\Http\Controllers\V1\Settings\GetUserSettingsController;
use Crater\Http\Controllers\V1\Settings\MailConfigurationController;
use Crater\Http\Controllers\V1\Settings\TaxTypesController;
use Crater\Http\Controllers\V1\Settings\UpdateCompanySettingsController;
use Crater\Http\Controllers\V1\Settings\UpdateUserSettingsController;
use Crater\Http\Controllers\V1\Update\CheckVersionController;
use Crater\Http\Controllers\V1\Update\CopyFilesController;
use Crater\Http\Controllers\V1\Update\DeleteFilesController;
use Crater\Http\Controllers\V1\Update\DownloadUpdateController;
use Crater\Http\Controllers\V1\Update\FinishUpdateController;
use Crater\Http\Controllers\V1\Update\MigrateUpdateController;
use Crater\Http\Controllers\V1\Update\UnzipUpdateController;
use Crater\Http\Controllers\V1\Users\UsersController;
use Crater\Http\Controllers\V1\Customer\CustomersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// ping
//----------------------------------

Route::get('ping', function () {
    return response()->json([
        'success' => 'crater-self-hosted',
    ]);
})->name('ping');


// Version 1 endpoints
// --------------------------------------
Route::prefix('/v1')->group(function () {


    // App version
    // ----------------------------------

    Route::get('/app/version', AppVersionController::class);


    // Authentication & Password Reset
    //----------------------------------

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

        // Send reset password mail
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware("throttle:10,2");

        // handle reset password form process
        Route::post('reset/password', [ResetPasswordController::class, 'reset']);
    });


    // Countries
    //----------------------------------

    Route::get('/countries', CountriesController::class);


    // Onboarding
    //----------------------------------

    Route::middleware(['redirect-if-installed'])->group(function () {
        Route::get('/onboarding/wizard-step', [OnboardingWizardController::class, 'getStep']);

        Route::post('/onboarding/wizard-step', [OnboardingWizardController::class, 'updateStep']);

        Route::get('/onboarding/requirements', [RequirementsController::class, 'requirements']);

        Route::get('/onboarding/permissions', [PermissionsController::class, 'permissions']);

        Route::post('/onboarding/database/config', [DatabaseConfigurationController::class, 'saveDatabaseEnvironment']);

        Route::get('/onboarding/database/config', [DatabaseConfigurationController::class, 'getDatabaseEnvironment']);

        Route::put('/onboarding/set-domain', AppDomainController::class);

        Route::post('/onboarding/login', LoginController::class);

        Route::post('/onboarding/finish', FinishController::class);
    });


    Route::middleware(['auth:sanctum', 'admin'])->group(function () {


        // Bootstrap
        //----------------------------------

        Route::get('/bootstrap', BootstrapController::class);


        // Dashboard
        //----------------------------------

        Route::get('/dashboard', DashboardController::class);


        // Auth check
        //----------------------------------

        Route::get('/auth/check', [AuthController::class, 'check']);


        // Search users
        //----------------------------------

        Route::get('/search', SearchController::class);


        // MISC
        //----------------------------------

        Route::get('/currencies', CurrenciesController::class);

        Route::get('/timezones', TimezonesController::class);

        Route::get('/date/formats', DateFormatsController::class);

        Route::get('/fiscal/years', FiscalYearsController::class);

        Route::get('/languages', LanguagesController::class);


        // Self Update
        //----------------------------------

        Route::get('/check/update', CheckVersionController::class);

        Route::post('/update/download', DownloadUpdateController::class);

        Route::post('/update/unzip', UnzipUpdateController::class);

        Route::post('/update/copy', CopyFilesController::class);

        Route::post('/update/delete', DeleteFilesController::class);

        Route::post('/update/migrate', MigrateUpdateController::class);

        Route::post('/update/finish', FinishUpdateController::class);


        // Customers
        //----------------------------------

        Route::post('/customers/delete', [CustomersController::class, 'delete']);

        Route::resource('customers', CustomersController::class);


        // Backup & Disk
        //----------------------------------

        Route::apiResource('backups', BackupsController::class);

        Route::apiResource('/disks', DiskController::class);

        Route::get('download-backup', DownloadBackupController::class);

        Route::get('/disk/drivers', [DiskController::class, 'getDiskDrivers']);


        // Settings
        //----------------------------------

        Route::get('/me', [CompanyController::class, 'getUser']);

        Route::put('/me', [CompanyController::class, 'updateProfile']);

        Route::get('/me/settings', GetUserSettingsController::class);

        Route::put('/me/settings', UpdateUserSettingsController::class);

        Route::post('/me/upload-avatar', [CompanyController::class, 'uploadAvatar']);


        Route::put('/company', [CompanyController::class, 'updateCompany']);

        Route::post('/company/upload-logo', [CompanyController::class, 'uploadCompanyLogo']);

        Route::get('/company/settings', GetCompanySettingsController::class);

        Route::post('/company/settings', UpdateCompanySettingsController::class);


        // Mails
        //----------------------------------

        Route::get('/mail/drivers', [MailConfigurationController::class, 'getMailDrivers']);

        Route::get('/mail/config', [MailConfigurationController::class, 'getMailEnvironment']);

        Route::post('/mail/config', [MailConfigurationController::class, 'saveMailEnvironment']);

        Route::post('/mail/test', [MailConfigurationController::class, 'testEmailConfig']);


        Route::apiResource('notes', NotesController::class);


        // Tax Types
        //----------------------------------

        Route::apiResource('tax-types', TaxTypesController::class);


        // Users
        //----------------------------------

        Route::post('/users/delete', [UsersController::class, 'delete']);

        Route::apiResource('/users', UsersController::class);
    });
});
