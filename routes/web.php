<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\CursesController;
use App\Http\Controllers\SponsorsController;
use App\Http\Controllers\AsseguradoresController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CorredorsController;

//Main
Route::get('/', [MainController::class, 'index'])->name('frontend.index');
Route::get('/inscripcions', [MainController::class, 'inscripcions'])->name('frontend.inscripcions');
Route::prefix('/cursa')->group(function () {
    Route::get('/', [MainController::class, 'dadesCursa'])->name('frontend.cursa');
    Route::get('/fotografies', [MainController::class, 'fotografies'])->name('frontend.cursa.fotografies');
    Route::get('/classificacio', [MainController::class, 'classificacio'])->name('frontend.cursa.classificacio');
    Route::get('/descarregarPdfPage', [CorredorsController::class, 'descarregarPdfPage'])->name('frontend.descarregarPdfPage');
    Route::get('/descarregarPdf', [CorredorsController::class, 'descarregarPdf'])->name('frontend.descarregarPdf');  
});
Route::prefix('/corredors')->group(function () {
    Route::get('/registrar', [CorredorsController::class, 'formulariAfegir'])->name('corredors.formulariAfegir');
    Route::get('/establirTemps', [CorredorsController::class, 'establirTemps'])->name('corredors.establirTemps');
    Route::post('/afegirCorredors', [CorredorsController::class, 'afegir'])->name('corredors.afegir');
    Route::post('/pagament', [CorredorsController::class, 'inscripcio'])->name('corredors.pagament');
    Route::get('/guardarInscripcio', [CorredorsController::class, 'guardarInscripcio'])->name('corredors.guardarInscripcio');
});


// Admin
Route::prefix('/admin')->group(function () {
    // Admin Login Routes
    Route::middleware(['guest', 'adminRedirectIfAuthenticated'])->get('/', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.loginPost');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout')->middleware('adminAuth');

    Route::prefix('/curses')->group(function () {
        // Curses
        Route::get('/', [CursesController::class, 'index'])->name('curses.index')->middleware('adminAuth');
        Route::get('/afegir', [CursesController::class, 'formulariAfegir'])->name('curses.formulariAfegir')->middleware('adminAuth');
        Route::get('/fotografies', [CursesController::class, 'fotografies'])->name('curses.fotografies')->middleware('adminAuth');
        Route::get('/participants', [CursesController::class, 'participants'])->name('curses.participants')->middleware('adminAuth');
        Route::get('/descarregarQR', [CursesController::class, 'descarregarQR'])->name('curses.descarregarQR')->middleware('adminAuth');
        Route::get('/descarregarZipQR', [CursesController::class, 'descarregarZipQR'])->name('curses.descarregarZipQR')->middleware('adminAuth');
        Route::post('/afegirCurses', [CursesController::class, 'afegir'])->name('curses.afegir')->middleware('adminAuth');
        Route::post('/afegirFotos', [CursesController::class, 'afegirFotos'])->name('curses.afegirFotos')->middleware('adminAuth');
        Route::post('/editarCurses', [CursesController::class, 'editar'])->name('curses.editar')->middleware('adminAuth');
        Route::get('/editar', [CursesController::class, 'formulariEditar'])->name('curses.formulariEditar')->middleware('adminAuth');
        Route::post('/filtreAjax', [CursesController::class, 'filtreAjax'])->name('curses.filtreAjax')->middleware('adminAuth');
        Route::post('/checkedAjax', [CursesController::class, 'checkedAjax'])->name('curses.checkedAjax')->middleware('adminAuth');
        Route::post('/deletePhoto', [CursesController::class, 'deletePhoto'])->name('curses.deletePhoto')->middleware('adminAuth');
    });
    Route::prefix('/sponsors')->group(function () {
        // Sponsors
        Route::get('/', [SponsorsController::class, 'index'])->name('sponsors.index')->middleware('adminAuth');
        Route::get('/afegir', [SponsorsController::class, 'formulariAfegir'])->name('sponsors.formulariAfegir')->middleware('adminAuth');
        Route::get('/descarregarFactura', [SponsorsController::class, 'descarregarFactura'])->name('sponsors.descarregarFactura')->middleware('adminAuth');
        Route::post('/afegirSponsors', [SponsorsController::class, 'afegir'])->name('sponsors.afegir')->middleware('adminAuth');
        Route::post('/editarSponsors', [SponsorsController::class, 'editar'])->name('sponsors.editar')->middleware('adminAuth');
        Route::get('/editar', [SponsorsController::class, 'formulariEditar'])->name('sponsors.formulariEditar')->middleware('adminAuth');
        Route::get('/patrocinar', [SponsorsController::class, 'formulariPatrocini'])->name('sponsors.formulariPatrocini')->middleware('adminAuth');
        Route::post('/filtreAjax', [SponsorsController::class, 'filtreAjax'])->name('sponsors.filtreAjax')->middleware('adminAuth');
        Route::post('/checkedAjax', [SponsorsController::class, 'checkedAjax'])->name('sponsors.checkedAjax')->middleware('adminAuth');
        Route::post('/checkedAjax2', [SponsorsController::class, 'checkedAjax2'])->name('sponsors.checkedAjax2')->middleware('adminAuth');
        Route::post('/patrocinarCursa', [SponsorsController::class, 'patrocinar'])->name('sponsors.patrocinar')->middleware('adminAuth');
        Route::post('/quitarPatrociniCursa', [SponsorsController::class, 'quitarPatrocini'])->name('sponsors.quitarPatrocini')->middleware('adminAuth');

    });
    Route::prefix('/asseguradores')->group(function () {
        // Asseguradores
        Route::get('/', [AsseguradoresController::class, 'index'])->name('asseguradores.index')->middleware('adminAuth');
        Route::get('/afegir', [AsseguradoresController::class, 'formulariAfegir'])->name('asseguradores.formulariAfegir')->middleware('adminAuth');
        Route::post('/afegirAsseguradores', [AsseguradoresController::class, 'afegir'])->name('asseguradores.afegir')->middleware('adminAuth');
        Route::post('/editarAsseguradores', [AsseguradoresController::class, 'editar'])->name('asseguradores.editar')->middleware('adminAuth');
        Route::get('/editar', [AsseguradoresController::class, 'formulariEditar'])->name('asseguradores.formulariEditar')->middleware('adminAuth');
        Route::post('/filtreAjax', [AsseguradoresController::class, 'filtreAjax'])->name('asseguradores.filtreAjax')->middleware('adminAuth');
        Route::post('/checkedAjax', [AsseguradoresController::class, 'checkedAjax'])->name('asseguradores.checkedAjax')->middleware('adminAuth');
    });
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
