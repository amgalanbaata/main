<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddPostController;
use App\Http\Controllers\EditEnqueteController;
use App\Http\Controllers\EnquetePreviewController;
use App\Http\Controllers\EnqueteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\AppUsersController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StaticUrlController;
use App\Http\Controllers\UserController;
use App\Models\AppUsers;

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

Route::get('/cache-path', function() {
    return storage_path('framework/cache/data');
});


Route::get('/',  [HomeController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::get('/admin/logout', [AdminController::class, 'logout']);
Route::post('/admin', [AdminController::class, 'dashboard']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/admin/posts', [AdminController::class, 'posts'])->name('admin.posts');
Route::post('/admin/posts', [AdminController::class, 'posts'])->name('admin.postsPost');
Route::get('/admin/post', [AdminController::class, 'post']);
Route::post('/admin/post', [AdminController::class, 'updatePost']);
Route::post('/admin/category', [AdminController::class, 'getCategory']);
Route::get('/admin/addpost', [AdminController::class, 'addPost']);
Route::post('/admin/addpost', [ApiController::class, 'insert']);
Route::get('/admin/user', [UserController::class, 'index'])->name('users.index');
// Route::post('admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::get('admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::post('admin/users/{id}', [UserController::class, 'update'])->name('users.update');

// Route::get('/admin/post', [AdminController::class, 'post']);
// add post location
Route::post('/admin/post/location/upload', [LocationController::class, 'createLocation']);
// add user and delete
Route::post('admin/users', [UserController::class, 'store'])->name('users.store');
Route::get('admin/users/create', [UserController::class, 'create'])->name('users.create');
Route::delete('admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
// user change password and name
Route::get('admin/user/profile', [UserController::class, 'userIndex'])->name('user.profile');
Route::post('admin/user/profile/edit', [UserController::class, 'profileEdit'])->name('user.profile.edit');
// admin location page
Route::get('/admin/location', [LocationController::class, 'index']);
Route::get('/admin/location/add', [LocationController::class, 'create']);
Route::post('/admin/location/add', [LocationController::class, 'create']);
Route::get('/admin/location/edit/{id}', [LocationController::class, 'edit']);
// report
Route::get('/admin/report', [ReportController::class, 'index']);
Route::post('/admin/report', [ReportController::class, 'generate'])->name('report.generate');
// static urls
Route::get('pdfurl', [StaticUrlController::class, 'pdfUrls']);
Route::get('contact-us', [StaticUrlController::class, 'contactUs']);
Route::get('standarts', [StaticUrlController::class, 'standarts']);
Route::get('electronic-librery', [StaticUrlController::class, 'electronicLibrery']);
Route::get('ubSoil', [StaticUrlController::class, 'ubSoil']);
Route::get('soil-pollution', [StaticUrlController::class, 'soilPollution']);
Route::get('laboratoryList', [StaticUrlController::class, 'laboratoryList']);
Route::get('documents', [StaticUrlController::class, 'documents']);
Route::get('soil-pollution', [StaticUrlController::class, 'soilPollution']);
Route::get('map', [StaticUrlController::class, 'map']);
Route::get('privacy', [ApiController::class, 'privacy']);
Route::resource('locations', LocationController::class);
// app users
Route::get('/admin/app-user',  [AppUserController::class, 'index'])->name('app-users.index');
Route::get('/admin/app-users/{id}/edit',  [AppUserController::class, 'edit'])->name('app-users.edit');
Route::get('/admin/app-users/create',  [AppUserController::class, 'create'])->name('app-users.create');
Route::post('/admin/app-users/{id}',  [AppUserController::class, 'update'])->name('app-users.update');
Route::delete('/admin/app-users/{id}',  [AppUserController::class, 'destroy'])->name('app-users.destroy');
Route::post('/admin/app-users', [AppUserController::class, 'store'])->name('app-users.store');
