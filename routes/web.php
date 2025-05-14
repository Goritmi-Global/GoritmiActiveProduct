<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DegreesController;
use App\Http\Controllers\SpacialitiesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */
// Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'index'])->name('login');
Route::post('signin', [HomeController::class, 'login'])->name('signin');
Route::get('/logout', [HomeController::class, 'logout_user'])->name('logout');

Route::middleware(['auth:sanctum'])->get('/settings', [AdminController::class, 'user_profile'])->name('settings');
Route::get('/reset/password', [AdminController::class, 'reset_password'])->name('reset.password');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['web'])->group(function () {

        // Dashboard Routes
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        

        // Profile Routes
        Route::get('/users', [UserController::class, 'index'])->name('users');

        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
  
        // languages Routes
        Route::get('/languages', [LanguageController::class, 'index'])->name('languages');
        Route::get('/languages/add', [LanguageController::class, 'languages_add'])->name('languages.add');
        Route::get('/languages/edit/{id}', [LanguageController::class, 'languages_edit'])->name('languages.edit');
        Route::get('/translations/show/{id}', [LanguageController::class, 'translations_show'])->name('translations.show');

//    external website

        Route::get('/external-website', [SettingController::class, 'external_website_index'])->name('external-website');


        // Doctors Routes
        Route::get('/doctors', [DashboardController::class, 'index'])->name('doctors');


        // Patient Routes
        Route::get('/patients', [PatientController::class, 'index'])->name('patients');



        // Assistant Routes
        Route::get('/assistants', [AssistantController::class, 'index'])->name('assistants');

        // Degrees Routes
        Route::get('/degrees', [DegreesController::class, 'index'])->name('degrees');
        Route::get('/degree/add', [DegreesController::class, 'store'])->name('degree.add');
        Route::get('/degree/details/{id}', [DegreeController::class, 'details'])->name('degree.details');
        Route::get('/degree/edit/{id}', [DegreesController::class, 'edit'])->name('degree.edit');

        // degrees and specialities Routes
        Route::get('/specialities', [SpacialitiesController::class, 'index'])->name('specialities');
        Route::get('/speciality/add', [SpacialitiesController::class, 'store'])->name('speciality.add');
        Route::get('/speciality/details/{id}', [SpacialitiesController::class, 'details'])->name('speciality.details');
        Route::get('/download/presctiption/pdf/{id}', [VisitController::class, 'download_presctiption_pdf'])->name('download.presctiption.pdf');
        Route::get('/send/presctiption/pdf/{patient_id}/{prescription_id}', [VisitController::class, 'send_presctiption_pdf'])->name('send.presctiption.pdf');

    });
});
