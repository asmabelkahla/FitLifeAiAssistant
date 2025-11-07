<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CoachController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SubscriptionTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\EquipmentController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'Home'])->name('dashboard');
Route::get('/about', [HomeController::class, 'About'])->name('about');
Route::get('/trainer', [HomeController::class, 'Trainer'])->name('trainer');
Route::get('/classes', [HomeController::class, 'classes'])->name('classes');
Route::get('/schedule', [HomeController::class, 'schedule'])->name('schedule');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Public routes
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::get('/types', [SubscriptionTypeController::class, 'index'])->name('types');
Route::get('/addtypes', [SubscriptionTypeController::class, 'create'])->name('addtypes');
Route::post('/store', [SubscriptionTypeController::class, 'store'])->name('store');
Route::get('/editsubscriptiontype/{subscriptiontype}', [SubscriptionTypeController::class, 'edit'])->name('editsubscriptiontype');
Route::post('/update/{subscriptiontype}', [SubscriptionTypeController::class, 'update'])->name('updatetype');
Route::post('/destroysubscriptiontype/{subscriptiontype}', [SubscriptionTypeController::class, 'destroy'])->name('destroytype');
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
Route::get('/addsubscriptions', [SubscriptionController::class, 'create'])->name('addsubscriptions');
Route::get('/editsubscriptions/{subscription}', [SubscriptionController::class, 'edit'])->name('editsubscriptions');
Route::post('/destroysubscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
Route::post('/add', [SubscriptionController::class, 'store'])->name('add');
Route::post('/update/{subscription}', [SubscriptionController::class, 'update'])->name('update');
Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
//
Route::get('/classschedule', [ClassScheduleController::class, 'index'])->name('classschedule');
Route::get('/createschedule', [ClassScheduleController::class, 'create'])->name('class_schedules.create');
Route::post('/storeschedule', [ClassScheduleController::class, 'store'])->name('class_schedules.store');
// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('activities', ActivityController::class);
    Route::resource('sessions', SessionController::class);
    Route::resource('members', MemberController::class);
    Route::resource('coaches', CoachController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('equipments', EquipmentController::class);
    Route::get('/trainer', [CoachController::class, 'showTrainers']);
});

// Member routes
Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('/member', [MemberController::class, 'index'])->name('member.dashboard');
});

// Coach routes
Route::middleware(['auth', 'role:coach'])->group(function () {
    Route::get('/coach', [CoachController::class, 'index'])->name('coach.dashboard');
});

// Additional routes
Route::resource('attendances', AttendanceController::class);
Route::resource('skills', SkillController::class);

// Equipment management routes
Route::resource('equipment', EquipmentController::class);
Route::get('equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
