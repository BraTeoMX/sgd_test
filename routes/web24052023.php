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

Auth::routes(['register' => false]);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['auth']], function () {
    Route::resources([
        'usuario'   => 'UsuarioController',
        'role'      => 'RoleController',
        'permiso'   => 'PermisoController', //Registro de Permisos
        'acceso'    => 'AccesoController',
        'correo'    => 'CorreoController', //Configuracion Correo
        'base'      => 'BaseController',   //Configuracion Base de Datos


    ], ['middleware' => ['role:Administrador Sistema']]);
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
        'estatuspermisos' => 'EstatusPermisosController',
        'formatopermisos' => 'FormatoPermisosController',
        'consultapermisos' => 'ConsultaPermisosController',
        'saldovacaciones' => 'SaldovacacionesController',
        'documentopermisos' => 'DocumentoPermisosController',
        'contenidosConsulta' => 'ContenidosConsultaController',
        'faltasjustificadas' => 'FaltasJustificadasController'
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
Route::get('empleados.buscarEmp', 'TblEmpleadoSIAController@index')->name('buscarEmp');
Route::get('empleados.buscarEmpPer', 'TblEmpleadoSIAController@permisos')->name('buscarEmpPer');
Route::get('empleados.buscarEmpPer2', 'TblEmpleadoSIAController@permisos2')->name('buscarEmpPer2');

Route::get('vacaciones.solicitar_vac/{dias}', 'VacacionesController@registrar_sol_vac')->name('sol_vac');
Route::get('vacaciones.liberar/{folio}', 'VacacionesController@liberar')->name('liberar');
Route::get('vacaciones.liberarPermiso/{folio}', 'VacacionesController@liberarPermiso')->name('vacaciones.liberarPermiso');
Route::get('vacaciones.liberarPermiso2/{folio}', 'VacacionesController@liberarPermiso2')->name('vacaciones.liberarPermiso2');
Route::get('vacaciones.vacacionesLiberadas', 'VacacionesController@vac_liberadas')->name('liberadas');
Route::post('vacacione.update/{folio}', 'VacacionesController@update')->name('vacaciones.update');
Route::get('vacaciones.denegarPermiso/{folio}', 'VacacionesController@denegarPermiso')->name('denegarPermiso');
Route::get('vacaciones.denegarPermiso2/{folio}', 'VacacionesController@denegarPermiso2')->name('denegarPermiso2');
Route::get('vacaciones.cancelacion', 'VacacionesController@cancelacion')->name('cancelacion');
Route::get('vacaciones.excepcion', 'VacacionesController@excepcion')->name('excepcion');
Route::post('vacaciones.update2/{folio}', 'VacacionesController@update2')->name('vacaciones.update2');
Route::get('/consultavacacionespdf/{id}', 'ConsultaVacacionesController@ticketPDF')->name('consultavacaciones.ticket');
Route::get('/consultaexcepcionespdf/{id}', 'ConsultaExcepcionesController@ticketPDF')->name('consultaexcepciones.ticket');
Route::get('vacaciones.saldo', 'VacacionesController@saldo')->name('vacaciones.saldo');
Route::post('vacaciones.saldoempleado', 'VacacionesController@saldoempleado')->name('vacaciones.saldoempleado');




Route::get('formatopermisos.solicitar', 'FormatoPermisosController@solicitar')->name('formatopermisos.solicitar');
Route::get('formatopermisos.liberarPermiso2/{folio}', 'FormatoPermisosController@liberarPermiso2')->name('formatopermisos.liberarPermiso2');
Route::get('formatopermisos.liberarPermisos/{folio}', 'FormatoPermisosController@liberarPermisos')->name('formatopermisos.liberarPermisos');
Route::post('formatopermisos.update/{folio}', 'FormatoPermisosController@update')->name('formatopermisos.update');
Route::post('formatopermisos.update2/{folio}', 'FormatoPermisosController@update2')->name('formatopermisos.update2');
Route::post('formatopermisos.savedraw/{folio}', 'FormatoPermisosController@savedraw')->name('savedraw');
Route::get('formatopermisos.denegarPermiso/{folio}', 'FormatoPermisosController@denegarPermiso')->name('formatopermisos.denegarPermiso');
Route::get('formatopermisos.denegarPermiso2/{folio}', 'FormatoPermisosController@denegarPermiso2')->name('formatopermisos.denegarPermiso2');



Route::get('vacaciones.masivas', 'VacacionesController@masivas')->name('vacaciones.masivas');
Route::post('vacaciones.importar', 'VacacionesController@importar')->name('importarmasivas');

Route::get('/consultapermisospdf/{id}', 'ConsultaPermisosController@ticketPDF')->name('consultapermisos.ticket');

Route::get('formatopermisos.seguridad', 'FormatoPermisosController@seguridad')->name('formatopermisos.seguridad');
Route::post('formatopermisos.permisoempleado', 'FormatoPermisosController@permisoempleado')->name('formatopermisos.permisoempleado');
Route::get('formatopermisos.revisarpermiso/{folio}', 'FormatoPermisosController@revisarpermiso')->name('formatopermisos.revisarpermiso');
Route::get('formatopermisos.revisarEntrada/{folio}', 'FormatoPermisosController@revisarEntrada')->name('formatopermisos.revisarEntrada');
Route::get('anexardocumento/{folio}', 'EstatusPermisosController@anexardocumento')->name('anexardocumento');
Route::get('formatopermisos.excepcion', 'FormatoPermisosController@excepcion')->name('formatopermisos.excepcion');
Route::get('formatopermisos.form2', 'FormatoPermisosController@form2')->name('formatopermisos.form2');
Route::get('formatopermisos.permisofirma/{folio}', 'FormatoPermisosController@permisofirma')->name('formatopermisos.permisofirma');

Route::get('formatopermisos.masivos', 'FormatoPermisosController@masivos')->name('formatopermisos.masivos');
Route::post('formatopermisos.masivos.guardar', 'FormatoPermisosController@guardarmasivos')->name('guardarpermisosmasivos');

Route::post('formatopermisos.firmas', 'FormatoPermisosController@firmas')->name('formatopermisos.firmas');

// Fecha bloqueada vacaciones
Route::post('vacaciones.getajax', 'VacacionesController@viewAjax');  //verificar fechas no laborables
Route::post('vacaciones.getajax2', 'VacacionesController@viewAjax2');  //verificar solicitudes pendientes
Route::post('vacaciones.getajax3', 'VacacionesController@viewAjax3');  // verificar ausentismo

Route::post('permisos.getajax3', 'FormatoPermisosController@viewAjax3');  // verificar ausentismo
Route::post('permisos.getajax', 'FormatoPermisosController@viewAjax');  //verificar fechas no laborables
Route::post('permisos.retardos', 'FormatoPermisosController@retardos'); //verificar los retardos


Route::get('/consultasajax/getmotivopermisos/{tipo}', 'ConsultasAjaxController@getmotivopermisos')->name('consultasajax.getmotivopermisos');
Route::get('/consultasajax/getautorizar/{tipo}', 'ConsultasAjaxController@getautorizar')->name('consultasajax.getautorizar');

Route::get('faltasjustificadas.reporte', 'FaltasJustificadasController@reporte')->name('faltasjustificadas.reporte');
Route::get('faltasjustificadas.busquedareporte', 'FaltasJustificadasController@busquedareporte')->name('busquedareporte');
Route::get('faltasjustificadas.updatearchivo/{folio}', 'FaltasJustificadasController@updatearchivo')->name('updatearchivo');
Route::get('faltasjustificadas.cancelarfaltaj/{folio}', 'FaltasJustificadasController@cancelarfaltaj')->name('cancelarfaltaj');

Route::POST('faltasjustificadas.nuevoarchivo', 'FaltasJustificadasController@nuevoarchivo')->name('faltasjustificadas.nuevoarchivo');
Route::POST('faltasjustificadas.fecha_ini', 'FaltasJustificadasController@ajaxfechainicio');
Route::POST('faltasjustificadas.fecha_fin', 'FaltasJustificadasController@ajaxfechafin');
