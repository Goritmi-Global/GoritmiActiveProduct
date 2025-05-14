<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\LanguageController; 
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DegreesController;
use App\Http\Controllers\SpacialitiesController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// getting translations
Route::get('/translate', [LanguageController::class, 'translate'])->name('api.translate');
Route::post('/missing/translations/store', [LanguageController::class, 'missing_translations_store'])->name('api.missing-translations.store');

Route::middleware(['web'])->group(function () {
    Route::post('/logout', [HomeController::class, 'logout_user'])->name('api.logout');
     

       // Users Api
       Route::get('/users/fetch', [UserController::class, 'users_fetch'])->name('api.users.fetch');
       Route::post('/users/store', [UserController::class, 'store'])->name('api.users.store');
       Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('api.users.delete');
       Route::get('/users/show/{id}', [UserController::class, 'show'])->name('api.users.show');
       Route::post('/users/update', [UserController::class, 'users_update'])->name('api.users.update');



    //    Account setting and profile routes
    Route::post('reset/password/email/store', [SettingController::class, 'reset_password_email_store'])->name('reset.password.email.store');

    Route::get('/profile/show/{id}', [UserController::class, 'profile_show'])->name('api.profile.show');
    Route::post('/profile/update', [UserController::class, 'profile_update'])->name('api.profile.update');
    Route::get('/user/show/', [UserController::class, 'show'])->name('api.user.show');


    // Lanaguges
    Route::get('/languages', [LanguageController::class, 'languages'])->name('api.languages');
    Route::get('/top/bar/languages', [LanguageController::class, 'top_bar_languages'])->name('api.top.bar.languages');
    Route::get('/languages/data', [LanguageController::class, 'languages_data'])->name('api.languages.data');

        // languages

        Route::post('/languages/store', [LanguageController::class, 'languages_store'])->name('api.languages.store');
        Route::get('/languages/show/{id}', [LanguageController::class, 'languages_show'])->name('api.languages.show');
        Route::post('/languages/update', [LanguageController::class, 'languages_update'])->name('api.languages.update');
        Route::delete('/languages/delete/{id}', [LanguageController::class, 'languages_delete'])->name('api.languages.delete');
        Route::post('/translations/show', [LanguageController::class, 'translations'])->name('api.translations.show');
        Route::post('/translations/store', [LanguageController::class, 'translations_store'])->name('api.translations.store');
        Route::post('/translations/search', [LanguageController::class, 'translations_search'])->name('api.translations.search');
        Route::delete('/translation/delete/{id}', [LanguageController::class, 'translation_delete'])->name('api.translation.delete');
        Route::get('/lang/countries/pluck', [LanguageController::class, 'lang_countries'])->name('api.lang.countries.pluck');
        

        // changing default language
        Route::post('/make/default/language', [LanguageController::class, 'make_default_language'])->name('api.make.default.language');
        Route::get('/fetch/default/language', [LanguageController::class, 'fetch_default_language'])->name('api.fetch.default.language');
        // Route::get('/languages/data', [LanguageController::class, 'languages_data'])->name('languages.data');
    
        Route::post('/user/default/language', [LanguageController::class, 'make_frontend_default_language'])->name('api.user.default.language');



        // External website sections roles
    Route::post('/website-name/store', [SettingController::class, 'website_name_store'])->name('api.website-name.store');
    Route::get('/fetch/website-name', [SettingController::class, 'website_name_fetch'])->name('api.fetch.website-name');

    Route::get('/logo/fetch', [SettingController::class, 'logo'])->name('api.logo.feth');
    Route::post('/logo/store', [SettingController::class, 'logo_store'])->name('api.logo.store');
    // Hero section routes
    Route::post('/hero/store/all', [SettingController::class, 'storeAllHeroSections'])->name('api.hero.store.all');
    Route::get('/fetch/hero/section/all', [SettingController::class, 'fetchAllHeroSections'])->name('api.fetch.hero.section.all');

    // carousel
    Route::post('/carousel/store', [SettingController::class, 'carousel_store'])->name('api.carousel.store');
    Route::post('/carousel/update', [SettingController::class, 'carousel_text_update'])->name('api.carousel.update');
    Route::get('/admin/carousel/text/fetch', [SettingController::class, 'carousel_fetch'])->name('api.carousel.fetch');
    Route::delete('/carousel/delete/{id}', [SettingController::class, 'carousel_delete'])->name('api.carousel.delete');
    Route::post('/carousel/dragging/{newPositions}', [SettingController::class, 'carousel_dragging'])->name('api.carousel.dragging');

    
       // Degrees
       Route::post('/all.degrees', [DegreesController::class, 'degrees'])->name('all.degrees');
       Route::post('/degree/store', [DegreesController::class, 'store'])->name('degree.store');
       Route::delete('/degree/delete/{id}', [DegreesController::class, 'delete'])->name('degree.delete');
       Route::get('/degree/show/{id}', [DegreesController::class, 'show'])->name('degree.show');


        // Specialities
        Route::post('/all/specialities', [SpacialitiesController::class, 'specialities'])->name('all.specialities');
        Route::post('/speciality/store', [SpacialitiesController::class, 'store'])->name('speciality.store');
        Route::delete('/speciality/delete/{id}', [SpacialitiesController::class, 'delete'])->name('speciality.delete');
        Route::get('/speciality/show/{id}', [SpacialitiesController::class, 'show'])->name('speciality.show');

        Route::post('/speciality/status/change', [SpacialitiesController::class, 'status_changer'])->name('speciality.status.change');
        Route::post('/speciality/show/hide', [SpacialitiesController::class, 'specialty_hide_show'])->name('speciality.show.hide');



});


// Couuntries, States and cities 
Route::get('/countries/pluck', [HomeController::class, 'countries_pluck'])->name('api.countries.pluck');
Route::get('/states/pluck/{country_of_birth_id}', [HomeController::class, 'states_pluck'])->name('api.states.pluck');
Route::get('/cities/pluck/{state_id}', [HomeController::class, 'cities_pluck'])->name('api.cities.pluck');

// languages
Route::get('/languages', [HomeController::class, 'languages'])->name('api.languages'); 
Route::get('/front_end/languages/data', [LanguageController::class, 'frontend_languages_data'])->name('api.front_end.languages.data');
