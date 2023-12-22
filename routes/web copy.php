<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TblEmpleadoSIA;
use App\Http\Controllers\Tbl_Empleado_SIA;
use App\Mail\envioCorreo;
use Illuminate\Support\Facades\Mail;

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
/*************************************************************************/
/*---Routes with FakeId----*/
Route::model('permiso', 'App\Permiso');
Route::model('role', 'App\Role');
Route::model('usuario', 'App\User');
Route::model('incidencia', 'App\Incidencia');


/*************************************************************************/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['register'=>false]);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['auth']], function () {
    Route::resources([
        'usuario'   => 'UsuarioController',
        'role'      => 'RoleController',
        'permiso'   => 'PermisoController', //Registro de Permisos
        'acceso'    => 'AccesoController',
        'correo'    => 'CorreoController', //Configuracion Correo
        'base'      => 'BaseController',   //Configuracion Base de Datos
        
       
   ],['middleware' => ['role:Administrador Sistema']]);
    //Ruta para la notificacio al usuario
    Route::get('/usuario/notificacion/{usuario}', 'UsuarioController@notificacion')->name('usuario.notificacion');

    Route::get('/home', 'HomeController@index')->name('home');


    Route::resources([
        'puestos' => 'PuestosController',
        'vacaciones' => 'VacacionesController',
        'formatopermisos' => 'FormatoPermisosController',
        'parametros' => 'ParametrosController',
        'calendario' => 'CalendarioController',
        'incidencia'   => 'IncidenciaController', //Registro de Incidencias
        'responsables'   => 'ResponsablesController', //Registro de Incidencias
        'autorizacion'    => 'AutorizacionController',
        'consultavacaciones' => 'ConsultaVacacionesController',
        'consultaexcepciones' => 'ConsultaExcepcionesController',
        'estatusvacaciones' => 'EstatusVacacionesController',
        'saldovacaciones' => 'SaldovacacionesController',
        'contenidosConsulta' => 'ContenidosConsultaController'
    ]);
    
});
//Ruta para configurar la contraseña
Route::get('/usuario/setpassword/{usuario}', 'UsuarioController@setpassword')->name('usuario.setpassword');
//Ruta al listado de usuarios
Route::put('/usuario/updatepassword/{usuario}', 'UsuarioController@update')->name('usuario.updatepassword');

Route::get('login.usuarioregistro', '\App\Http\Controllers\Auth\LoginController@usuarioregistro')->name('usuarioregistro');
Route::get('login.validaregistro/{id}', '\App\Http\Controllers\Auth\LoginController@validaregistro')->name('validaregistro');

Route::resource('recoleccionsteprol', 'RecoleccionStepRolController');
Route::get('vacaciones.solicitarvacaciones', 'VacacionesController@solicitarvacaciones')->name('vacaciones.solicitarvacaciones');
Route::get('empleados.buscarEmpPer', 'TblEmpleadoSIAController@permisos')->name('buscarEmpPer');
Route::get('empleados.buscarEmp', 'TblEmpleadoSIAController@index')->name('buscarEmp');
Route::get('vacaciones.solicitar_vac/{dias}', 'VacacionesController@registrar_sol_vac')->name('sol_vac');
Route::get('vacaciones.liberar/{folio}', 'VacacionesController@liberar')->name('liberar');
Route::get('vacaciones.liberarPermiso/{folio}', 'VacacionesController@liberarPermiso')->name('liberarPermiso');
Route::get('vacaciones.liberarPermiso2/{folio}', 'VacacionesController@liberarPermiso2')->name('liberarPermiso2');
Route::get('vacaciones.vacacionesLiberadas', 'VacacionesController@vac_liberadas')->name('liberadas');
Route::post('vacacione.update/{folio}', 'VacacionesController@update')->name('vacaciones.update');
Route::get('vacaciones.denegarPermiso/{folio}', 'VacacionesController@denegarPermiso')->name('denegarPermiso');
Route::get('vacaciones.denegarPermiso2/{folio}', 'VacacionesController@denegarPermiso2')->name('denegarPermiso2');
Route::get('vacaciones.cancelacion', 'VacacionesController@cancelacion')->name('cancelacion');
Route::post('vacaciones.update2/{folio}', 'VacacionesController@update2')->name('vacaciones.update2');
Route::get('/consultavacacionespdf/{id}', 'ConsultaVacacionesController@ticketPDF')->name('consultavacaciones.ticket');
Route::get('/consultaexcepcionespdf/{id}', 'ConsultaExcepcionesController@ticketPDF')->name('consultaexcepciones.ticket');

// Fecha bloqueada vacaciones
Route::post('vacaciones.getajax', 'VacacionesController@viewAjax');  //verificar fechas no laborables
Route::post('vacaciones.getajax2', 'VacacionesController@viewAjax2');  //verificar solicitudes pendientes
Route::post('vacaciones.getajax3', 'VacacionesController@viewAjax3');  // verificar ausentismo



