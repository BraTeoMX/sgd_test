<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TblEmpleadoSIA;
use App\Http\Controllers\Tbl_Empleado_SIA;
use App\Mail\envioCorreo;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\VistaPapelController;

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
        'faltasjustificadas' => 'FaltasJustificadasController',
        'retardos'    => 'RetardosController',
        'matrizautorizacion'    => 'MatrizAutorizacionController',
        'incapacidades' => 'IncapacidadesController',
        'asisweb' => 'AsiswebController'
    ]);
});
//Ruta para configurar la contraseña
Route::get('/usuario/setpassword/{usuario}', 'UsuarioController@setpassword')->name('usuario.setpassword');
//Ruta al listado de usuarios
Route::put('/usuario/updatepassword/{usuario}', 'UsuarioController@update')->name('usuario.updatepassword');
Route::post('/usuario/passwordupdate', 'UsuarioController@passwordupdate')->name('usuario.passwordupdate');

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
Route::get('vacaciones.autorizar', 'VacacionesController@autorizar')->name('vacaciones.autorizar');
Route::get('vacaciones.form2', 'VacacionesController@form2')->name('vacaciones.form2');
Route::get('vacaciones.solicitarvacaciones2023', 'VacacionesController@solicitarvacaciones2023')->name('vacaciones.solicitarvacaciones2023');


Route::get('formatopermisos.solicitar', 'FormatoPermisosController@solicitar')->name('formatopermisos.solicitar');
Route::get('formatopermisos.liberarPermiso2/{folio}', 'FormatoPermisosController@liberarPermiso2')->name('formatopermisos.liberarPermiso2');
Route::get('formatopermisos.liberarPermisos/{folio}', 'FormatoPermisosController@liberarPermisos')->name('formatopermisos.liberarPermisos');
Route::post('formatopermisos.update/{folio}', 'FormatoPermisosController@update')->name('formatopermisos.update');
Route::post('formatopermisos.update2/{folio}', 'FormatoPermisosController@update2')->name('formatopermisos.update2');
Route::post('formatopermisos.savedraw/{folio}', 'FormatoPermisosController@savedraw')->name('savedraw');
Route::get('formatopermisos.denegarPermiso/{folio}', 'FormatoPermisosController@denegarPermiso')->name('formatopermisos.denegarPermiso');
Route::get('formatopermisos.denegarPermiso2/{folio}', 'FormatoPermisosController@denegarPermiso2')->name('formatopermisos.denegarPermiso2');

Route::get('formatopermisos.pabiertosinicio', 'FormatoPermisosController@pabiertosinicio')->name('formatopermisos.pabiertosinicio');
Route::post('formatopermisos.pabiertos', 'FormatoPermisosController@pabiertos')->name('genperabiertos');


Route::get('vacaciones.masivas', 'VacacionesController@masivas')->name('vacaciones.masivas');
Route::post('vacaciones.importar', 'VacacionesController@importar')->name('importarmasivas');

Route::get('/consultapermisospdf/{id}', 'ConsultaPermisosController@ticketPDF')->name('consultapermisos.ticket');

Route::get('formatopermisos.seguridad', 'FormatoPermisosController@seguridad')->name('formatopermisos.seguridad');
Route::post('formatopermisos.permisoempleado', 'FormatoPermisosController@permisoempleado')->name('formatopermisos.permisoempleado');
Route::get('formatopermisos.revisarpermiso/{folio}', 'FormatoPermisosController@revisarpermiso')->name('formatopermisos.revisarpermiso'); //seguridad
Route::get('formatopermisos.revisarEntrada/{folio}', 'FormatoPermisosController@revisarEntrada')->name('formatopermisos.revisarEntrada'); //seguridad
Route::get('anexardocumento/{folio}', 'EstatusPermisosController@anexardocumento')->name('anexardocumento');
Route::get('formatopermisos.excepcion', 'FormatoPermisosController@excepcion')->name('formatopermisos.excepcion');
Route::get('formatopermisos.form2', 'FormatoPermisosController@form2')->name('formatopermisos.form2');
Route::get('formatopermisos.permisofirma/{folio}', 'FormatoPermisosController@permisofirma')->name('formatopermisos.permisofirma');
Route::get('formatopermisos.autorizar', 'FormatoPermisosController@autorizar')->name('formatopermisos.autorizar');
Route::get('formatopermisos.form2', 'FormatoPermisosController@form2')->name('formatopermisos.form2');

Route::get('permisos.masivos', 'FormatoPermisosController@masivos')->name('permisos.masivos');
Route::post('permisos.permisosimportar', 'FormatoPermisosController@permisosimportar')->name('importarmasivos');

Route::post('formatopermisos.firmas', 'FormatoPermisosController@firmas')->name('formatopermisos.firmas');

// Fecha bloqueada vacaciones
Route::post('vacaciones.getajax', 'VacacionesController@viewAjax');  //verificar fechas no laborables
Route::post('vacaciones.getajax2', 'VacacionesController@viewAjax2');  //verificar solicitudes pendientes
Route::post('vacaciones.getajax3', 'VacacionesController@viewAjax3');  // verificar ausentismo
Route::post('vacaciones.getajax4', 'VacacionesController@viewAjax4');  // contar fechas no laborables


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

Route::get('incapacidades.reporte', 'IncapacidadesController@reporte')->name('incapacidades.reporte');
Route::get('incapacidades.busquedaincapacidades', 'IncapacidadesController@busquedaincapacidades')->name('busquedaincapacidades');
Route::POST('incapacidades.editarincapacidad', 'IncapacidadesController@editarincapacidad')->name('editarincapacidad');
Route::get('incapacidades.cancelarincapacidad/{id}', 'IncapacidadesController@cancelarincapacidad')->name('cancelarincapacidad');

//Ruta para Eventos

Route::get('CrearEvento', 'EventoController@create')->name('eventos.create');
Route::POST('RegistrarEventos', 'EventoController@RegistrarEventos')->name('eventos.RegistrarEventos');

// Ruta para eliminar un evento
Route::delete('eventos/{evento}', 'EventoController@destroy')->name('eventos.destroy');
Route::delete('Borrar/{registro}', 'EventoController@BorrarRegistro')->name('eventos.BorrarRegistro');
Route::delete('BorrarAsis/{registro}', 'EventoController@BorrarRegistroPre')->name('eventos.BorrarRegistroPre');
//Rutas para Asistencias/Confirmacion de asistencias/
Route::get('ListaEventos','EventoController@ListaEventos')->name('eventos.ListaEventos');
Route::POST('RegistrarAsistencias', 'ReportesEventosController@RegistrarAsistencias')->name('eventos.RegistrarAsistencias');
Route::POST('AsistenciaBecarios', 'ReportesEventosController@AsistenciaBecarios')->name('eventos.AsistenciaBecarios');
Route::POST('EntregaPapel', 'ReportesEventosController@EntregaPapel')->name('eventos.EntregaPapel');
//Ruta Para  Reportes
Route::get('ReportesEventos', 'ReportesEventosController@ReportesEventos')->name('eventos.ReportesEventos');
Route::get('/obtener-meses/{evento}', 'ReportesEventosController@obtenerMeses');
Route::post('GenerarReportes', 'ReportesEventosController@GenerarReporte')->name('eventos.GenerarReporte');
// apartado de Pre Registro para Eventos
Route::get('PreRegistro','EventoController@PreRegistro')->name('eventos.PreRegistro');
Route::POST('PreRegistro', 'ReportesEventosController@PreRegistro')->name('eventos.PreRegistro');
Route::POST('RegistroBecario', 'ReportesEventosController@registrarBecario')->name('eventos.registrarBecario');

// apartado de Registro asistencias para Eventos
Route::get('RegistroAsistencias','EventoController@RegistroAsistencias')->name('eventos.RegistroAsistencias');
Route::POST('RegistroAsistencias', 'ReportesEventosController@RegistroAsistencias')->name('eventos.RegistroAsistencias');

//Eliminar registros de PreRegistros Vistas
Route::get('ElimiarRegistros', 'EventoController@DeleteRegistros')->name('eventos.DeleteRegistros');
Route::POST('ElimiarRegistros', 'EventoController@DeleteRegistros')->name('eventos.DeleteRegistros');

//Eliminar Registros Ecentos Generales Vistas
Route::get('DeleteRegPre', 'EventoController@DeleteRegPre')->name('eventos.DeleteRegPre');
Route::POST('DeleteRegPre', 'EventoController@DeleteRegPre')->name('eventos.DeleteRegPre');

// apartado de tickets para PDF de los eventos de papel
Route::get('PDFReportView','ReportesEventosController@PDFReportView')->name('eventos.PDFReportView');
// actualiza tu archivo web.php o routes.php con la siguiente línea
Route::post('PDFReportView', 'ReportesEventosController@PDFReportView')->name('eventos.PDFReportView');

Route::get('GenerarReportesExcel', 'ReportesEventosController@exportarExcel')->name('eventos.exportarExcel');


Route::post('GenerarReportesExcel', 'ReportesEventosController@exportarExcel')->name('eventos.exportarExcel');

//Enviar a Update Puestos
Route::get('UpdatePuestos','EventoController@UpdatePuestos')->name('eventos.UpdatePuestos');
Route::post('ActualizaPapel', 'EventoController@actualizarPapel')->name('eventos.actualizarPapel');

//Rutas para Papel - pensado en una vista independiente
Route::get('VistaPapel','VistaPapelController@VistaPapel')->name('eventos.VistaPapel');

// Ruta para procesar el formulario
Route::post('RegistroVistaPapel', 'VistaPapelController@RegistroVistaPapel')->name('eventos.RegistroVistaPapel');