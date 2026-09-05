<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NeighborhoodController;
use App\Http\Controllers\ProfileController;
use App\Livewire\AcceptInvite;
use App\Livewire\BusinessDirectory;
use App\Livewire\RegisterBusiness;
use App\Http\Controllers\ExternalHeadlineController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/news/external/{headline}', [ExternalHeadlineController::class, 'show'])->name('external.show');
Route::get('/feed.xml', [FeedController::class, 'rss'])->name('feed');

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/neighborhood/{neighborhood:slug}', [NeighborhoodController::class, 'show'])->name('neighborhoods.show');

Route::get('/directory', BusinessDirectory::class)->name('directory');
Route::get('/directory/register', RegisterBusiness::class)->name('directory.register');

Route::get('/accept-invite/{user}', AcceptInvite::class)->name('accept-invite');

Route::redirect('/admin', '/portal');

use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/reviews', [DashboardController::class, 'reviews'])->name('dashboard.reviews');
    Route::get('/dashboard/businesses', [DashboardController::class, 'businesses'])->name('dashboard.businesses');
    Route::get('/dashboard/businesses/{business}/edit', [DashboardController::class, 'editBusiness'])->name('dashboard.businesses.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Placed below to prevent capturing /directory/register as a business slug
Route::get('/directory/{business:slug}', [BusinessController::class, 'show'])->name('directory.show');

require __DIR__.'/auth.php';
