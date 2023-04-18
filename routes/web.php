<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\BasicosController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Http;

// use App\Http\Controllers\UpsController;
// use App\Http\Controllers\TecnicosController;
// use App\Http\Controllers\ClientesController;
// use App\Http\Controllers\ReportesController;
// use App\Http\Controllers\PruebasController;
//
use App\Models\User;
use App\Models\Role;



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

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/perfil', [HomeController::class, 'perfil'])->name('perfil');
Route::get('/opciones', [HomeController::class, 'opciones'])->name('opciones');
Route::get('/perfil', [HomeController::class, 'perfil'])->name('perfil');
Route::post('MostrarInformacionPerfil', [HomeController::class,'MostrarInformacionPerfil'])->middleware('auth');
Route::post('GuardarFilePerfil', [HomeController::class,'GuardarFilePerfil'])->middleware('auth');
Route::post('updatePassword', [HomeController::class,'updatePassword'])->middleware('auth');
Route::post('actualizarDatosPersonales', [HomeController::class,'actualizarDatosPersonales'])->middleware('auth');
Route::post('actualizarCorreoFacturacion', [HomeController::class,'actualizarCorreoFacturacion'])->middleware('auth');
Route::post('graficaBarrasContadorXestado', [HomeController::class,'graficaBarrasContadorXestado'])->middleware('auth');
Route::post('reporteHome', [HomeController::class,'reporteHome'])->middleware('auth');
Route::post('MostrarCalendarioAgendas', [HomeController::class,'MostrarCalendarioAgendas'])->middleware('auth');
Route::post('mostrarEvento', [HomeController::class,'mostrarEvento'])->middleware('auth');
// rutas para usuarios
Route::apiResource('usuarios', UsuariosController::class) ->middleware('auth');
Route::post('ListarUsuarios', [UsuariosController::class,'Listar'])->middleware('auth');
Route::post('EliminarUsuario', [UsuariosController::class,'Eliminar'])->middleware('auth');
Route::post('CrearUsuario', [UsuariosController::class,'Crear'])->middleware('auth');
Route::post('MostrarUsuario', [UsuariosController::class,'Mostrar'])->middleware('auth');
Route::post('ActualizarUsuario', [UsuariosController::class,'Actualizar'])->middleware('auth');
Route::post('obtenerSucursales', [UsuariosController::class,'obtenerSucursales'])->middleware('auth');
Route::post('actualizarFechaVencimiento', [UsuariosController::class,'actualizarFechaVencimiento'])->middleware('auth');
Route::post('enviarMailCreacionUsuario', [UsuariosController::class,'enviarMailCreacion'])->middleware('auth');
//rutas roles
Route::resource('roles', rolesController::class)->middleware('auth','admin');
Route::post('ListarRoles', [rolesController::class,'Listar'])->middleware('auth');
Route::post('EliminarRole', [rolesController::class,'Eliminar'])->middleware('auth');
Route::post('CrearRole', [rolesController::class,'Crear'])->middleware('auth');
Route::post('MostrarRole', [rolesController::class,'Mostrar'])->middleware('auth');
Route::post('ActualizarRole', [rolesController::class,'Actualizar'])->middleware('auth');
//rutas basicos
Route::get('basicos', [BasicosController::class, 'index'])->middleware('auth');
Route::post('ListarBasicos', [BasicosController::class,'Listar'])->middleware('auth');
Route::post('EliminarBasico', [BasicosController::class,'Eliminar'])->middleware('auth');
Route::post('CrearBasico', [BasicosController::class,'Crear'])->middleware('auth');
Route::post('MostrarBasico', [BasicosController::class,'Mostrar'])->middleware('auth');
Route::post('ActualizarBasico', [BasicosController::class,'Actualizar'])->middleware('auth');
Route::post('GuardarFileEmpresa', [BasicosController::class,'GuardarFileEmpresa'])->middleware('auth');
//limpiezade cache
Route::get('/clear-cache', function() {
  echo Artisan::call('optimize');
  echo Artisan::call('route:clear');
  echo Artisan::call('config:clear');
  echo Artisan::call('config:cache');
  echo Artisan::call('cache:clear');
// return "Cleared!";
//      return 'what you want';
});









