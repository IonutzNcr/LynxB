<?php



use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UController;
use App\Http\Controllers\InfoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     $user = new stdClass();
//     $user->name = 'Arhur';
//     dd($user);
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//         'user' => $user,
//     ]);
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });


// Route::get("/test", function(){
//     return Inertia::render('Test',['test'=> 'test']);
// });

Route::get('/dashboard', [InfoController::class, 'Index']);
// Route::post('/dashboard', [InfoController::class, 'store']);
Route::post('/dashboard/store', [InfoController::class, 'store']);
Route::post('/dashboard/update', [InfoController::class, 'update']);
Route::delete('/dashboard/delete', [InfoController::class, 'delete']);

