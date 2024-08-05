<?php

use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about-us', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/check-email', [LoginController::class, 'checkEmail'])->name('checkEmail');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}/show', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/articles/{user:name}/author', [ArticleController::class, 'author'])->name('articles.author');
Route::get('/articles/search', [SearchController::class, 'searchArticles'])->name('article.search');
// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/search', [SearchController::class, 'searchEvents'])->name('event.search');
// Call
Route::post('/contact', [CallController::class, 'store'])->name('call.store');

// Guest
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login']);
    Route::post('/auth', [LoginController::class, 'authh'])->name('authh');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

// Member
Route::middleware(['auth'])->group(function () {
    // Articles
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles/store', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}/update', [ArticleController::class, 'update'])->name('articles.update');
    // Profile
    Route::get('users/{slug}/edit', [UserController::class, 'edit'])->name('profile');
    Route::put('users/{slug}/update', [UserController::class, 'update'])->name('users.update');
    // Notifikasi
    Route::get('/notification', [NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    // Likes
    Route::post('/articles/{article}/like', [ArticleController::class, 'like'])->name('articles.like');
    Route::delete('/articles/{article}/unlike', [ArticleController::class, 'unlike'])->name('articles.unlike');
    Route::post('/campaigns/{campaign}/like', [CampaignController::class, 'like'])->name('campaigns.like');
    Route::delete('/campaigns/{campaign}/unlike', [CampaignController::class, 'unlike'])->name('campaigns.unlike');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/articles', [DashboardController::class, 'articles'])->name('admin.articles');
    Route::get('/admin/events', [DashboardController::class, 'events'])->name('admin.events');
    Route::get('/admin/campaigns', [DashboardController::class, 'campaigns'])->name('admin.campaigns');
    Route::get('/admin/dashboard/report_pdf', [ReportController::class, 'generatePdfReport'])->name('admin.download.pdf');
    // Articles
    Route::get('/admin/articles/pending', [DashboardController::class, 'pendingArticles'])->name('admin.articles.pending');
    Route::get('/admin/articles/{article}/show', [ArticleController::class, 'adminShow'])->name('admin.articles.show');
    Route::post('/admin/articles/{article}/approve', [DashboardController::class, 'approveArticle'])->name('admin.articles.approve');
    Route::post('/admin/articles/message/{article}/approve', [DashboardController::class, 'approveArticle'])->name('articles.approve');
    Route::post('/admin/articles/message/{article}/reject', [DashboardController::class, 'rejectArticle'])->name('articles.reject');
    Route::post('/admin/articles/star', [HomeController::class, 'starArticle'])->name('star.article');
    Route::delete('/admin/articles/{article}/destroy', [ArticleController::class, 'destroy'])->name('articles.destroy');
    // Events
    Route::get('/admin/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/admin/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/admin/events/{event}/update', [EventController::class, 'update'])->name('events.update');
    Route::delete('/admin/events/{event}/destroy', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/admin/events/star', [HomeController::class, 'starEvent'])->name('star.event');
    // Users
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/update-role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{user}/destroy', [UserController::class, 'destroy'])->name('admin.users.destroy');
    // Location
    Route::get('/get-regencies/{provinceId}', [EventController::class, 'getRegencies']);
    Route::get('/get-districts/{regencyId}', [EventController::class, 'getDistricts']);
    Route::get('/get-villages/{districtId}', [EventController::class, 'getVillages']);
    // Call
    Route::get('/admin/call', [CallController::class, 'index'])->name('admin.call');
    Route::delete('/admin/call/{call}/destroy', [CallController::class, 'destroy'])->name('admin.call.destroy');
    Route::post('/admin/call/{id}/read', [CallController::class, 'markAsRead'])->name('admin.call.read');
    Route::post('/admin/call/{id}/unread', [CallController::class, 'markAsUnread'])->name('admin.call.unread');
    // Campaign
    Route::get('/admin/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/admin/campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/admin/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/admin/campaigns/{campaign}/update', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/admin/campaigns/{campaign}/destroy', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/admin/campagins/star', [HomeController::class, 'starCampaign'])->name('star.campaign');
});
