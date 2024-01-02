<aside
    class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
    <div class="navbar-vertical-container" style="background-color: #8c8c8c;">
        <div class="navbar-vertical-footer-offset">
            <div class="navbar-brand-wrapper justify-content-between" style="background-color: #ffffff;">
                <!-- Logo -->


                <a class="navbar-brand" href="{!! route('home') !!}" aria-label="Front">
                    <img class="navbar-brand-logo" src="{!! asset('/img/logo.png') !!}" alt="Logo">
                    <img class="navbar-brand-logo-mini" src="{!! asset('/img/logo.png') !!}" alt="Logo">
                </a>

                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                    class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Content -->
            <div class="navbar-vertical-content" style="background-color: #efebe9;">
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->

                    <!-- End Dashboards -->
                    <!-- Pages -->

                    <!-- End Pages -->
                    <!--<li class="nav-item">
              <div class="nav-divider"></div>
            </li>-->
                    <!-- Pages -->

                    @if (auth()->user()->hasPermissionTo('vacaciones.solicitarvacaciones'))
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle " href="javascript:;"
                                title="Pages">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Vacaciones</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                @if (!auth()->user()->hasRole('Vicepresidente'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones.solicitarvacaciones') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Solicitud </span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Team Leader') ||
                                        auth()->user()->hasRole('Gerente Produccion') ||
                                        auth()->user()->hasRole('Coordinador/Analista') ||
                                        auth()->user()->hasRole('Seguridad e Higiene') ||
                                        auth()->user()->hasRole('Vicepresidente'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Personal de Area</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Jefe Administrativo') ||
                                        auth()->user()->hasRole('Vicepresidente') ||
                                        auth()->user()->hasRole('Team Leader') ||
                                        auth()->user()->hasRole('Gerente Produccion'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones.form2') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Autorizar</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones.cancelacion') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Cancelación</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones.saldo') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Saldo de dias</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Team Leader') ||
                                        auth()->user()->hasRole('Coordinador/Analista') ||
                                        auth()->user()->hasRole('Seguridad e Higiene'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/estatusvacaciones') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Estatus de Solicitud</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Jefe Administrativo') ||
                                        auth()->user()->hasRole('Gerente Produccion') ||
                                        auth()->user()->hasRole('Vicepresidente') ||
                                        auth()->user()->hasRole('Seguridad e Higiene') ||
                                        auth()->user()->hasRole('Nominas PII') ||
                                        auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/consultavacaciones') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Reporte General</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Vicepresidente'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Excepciones</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Vicepresidente') ||
                                        auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/consultaexcepciones') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Reporte Excepciones</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/vacaciones.masivas') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Vacaciones Masivas</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Administrador Sistema'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/vacaciones.solicitarvacaciones2023') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Solicitud2023 </span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>

                    @endif



                    @if (auth()->user()->hasPermissionTo('formatopermisos.solicitar'))
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Pages">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Permisos</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                <!-- <li class="nav-item">
                     <a class="nav-link" href="{!! url('/formatopermisos.solicitar') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Solicitud</span>
                      </a>
                    </li> -->

                                @if (auth()->user()->hasRole('Analista/Coordinador') ||
                                        auth()->user()->hasRole('Gerente Produccion') ||
                                        auth()->user()->hasRole('Jefe Administrativo') ||
                                        auth()->user()->hasRole('Coordinador/Analista') ||
                                        auth()->user()->hasRole('Seguridad e Higiene') ||
                                        auth()->user()->hasRole('Administrador Sistema') ||
                                        auth()->user()->hasRole('Vicepresidente') ||
                                        auth()->user()->hasRole('Servicio Medico'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/formatopermisos') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Solicitud</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Team Leader') ||
                                        auth()->user()->hasRole('Vicepresidente'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/formatopermisos.form2') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Autorizar</span>
                                        </a>
                                    </li>
                                @endif
                                @if (!auth()->user()->hasRole('Vigilancia') ||
                                    auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/estatuspermisos') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Estatus de Solicitud</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Gerente Produccion') ||
                                        auth()->user()->hasRole('Jefe Administrativo') ||
                                        auth()->user()->hasRole('Vicepresidente'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/formatopermisos.excepcion') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Excepciones</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('formatopermisos.pabiertosinicio') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Permisos Abiertos</span>
                                        </a>
                                    </li>
                                @endif
                                @if (!auth()->user()->hasRole('Vigilancia'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/consultapermisos') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Reporte General</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Vigilancia') ||
                                        auth()->user()->hasRole('Administrador Sistema'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/formatopermisos.seguridad') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Revision Seguridad</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasRole('Administrador Sistema') || auth()->user()->hasRole('Coordinador/Analista'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/permisos.masivos') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Permisos Masivos</span>
                                    </a>
                                </li>
                            @endif
                            </ul>
                    @endif

                    @if (auth()->user()->hasRole('Vigilancia') ||
                            auth()->user()->hasRole('Administrador Sistema'))
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Pages">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Retardos
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">

                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/retardos') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Retardos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermissionTo('faltasjustificadas.nuevoarchivo'))
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Pages">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Faltas
                                    Justificadas</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">

                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/faltasjustificadas') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Registrar Justificantes</span>
                                    </a>
                                </li>

                            </ul>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">

                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/faltasjustificadas.reporte') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Reporte General</span>
                                    </a>
                                </li>

                            </ul>

                        </li>
                    @endif

                    @if (auth()->user()->hasRole('Servicio Medico') ||
                            auth()->user()->hasRole('Recursos Humanos') ||
                            auth()->user()->hasRole('Administrador Sistema')||
                            auth()->user()->hasRole('Coordinador/Analista') ||
                                        auth()->user()->hasRole('Gerente Produccion'))
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Pages">
                                <i class="tio-pages-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Incapacidades
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                @if (auth()->user()->hasRole('Servicio Medico'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/incapacidades') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Registrar Incapacidades</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                @if (auth()->user()->hasRole('Administrador Sistema') ||
                                        auth()->user()->hasRole('Servicio Medico') ||
                                        auth()->user()->hasRole('Recursos Humanos') ||
                                        auth()->user()->hasRole('Coordinador/Analista')||
                                        auth()->user()->hasRole('Gerente Produccion'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{!! url('/incapacidades.reporte') !!}" title="Welcome message"
                                            data-placement="left">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Reporte General</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if((auth()->user()->email=='atapia@intimark.com.mx' ||
                     auth()->user()->email=='msalazar@intimark.com.mx' ||
                      auth()->user()->email=='gvergara@intimark.com.mx' ||
                      auth()->user()->email=='bteofilo@intimark.com.mx' ||
                      auth()->user()->email=='seguridadpii@intimark.com.mx' ||
                      auth()->user()->email=='seguridadpi@intimark.com.mx' ||
                      auth()->user()->email=='ilopez@intimark.com.mx' || auth()->user()->email=='cproyectos@intimark.com.mx' || auth()->user()->email=='ggonzalez@intimark.com.mx' ||
                      auth()->user()->email=='coordinadorreclutamientosb@intimark.com.mx' || auth()->user()->email=='rhumanospII@intimark.com.mx' || auth()->user()->email=='rmoreno@intimark.com.mx' ||
                      auth()->user()->email=='lsanchez@intimark.com.mx' || auth()->user()->email== 'gvm7506@gmail.com'	))
                    <li class="navbar-vertical-aside-has-menu">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                            href="javascript:;" title="Eventos">
                            <i class="tio-pages-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Eventos</span>
                        </a>

                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                            @if (auth()->user()->hasRole('Seguridad e Higiene') ||
                            auth()->user()->hasRole('Administrador Sistema') ||  auth()->user()->hasRole('Jefe Administrativo') )
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('eventos.create') }}" title="Crear Evento"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Nuevo Evento</span>
                                    </a>
                                </li>
                             @endif
                        </ul>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                            <li class="nav-item">
                               <a class="nav-link" href="{{ route('eventos.PreRegistro') }}" title="Lista para Registro" data-placement="left">
                                    <i class="tio-circle nav-indicator-icon"></i>
                                    <span class="text-truncate">Registro de Evento</span>
                                </a>
                            </li>
                        </ul>

                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                            <li class="nav-item">
                               <a class="nav-link" href="{{ route('eventos.RegistroAsistencias') }}" title="Lista para PreRegistro" data-placement="left">
                                    <i class="tio-circle nav-indicator-icon"></i>
                                    <span class="text-truncate">Asistencia con Registro</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('eventos.ListaEventos') }}" title="Lista de Eventos" data-placement="left">
                                            <i class="tio-circle nav-indicator-icon"></i>
                                            <span class="text-truncate">Asistencia sin Registro</span>
                                        </a>
                                    </li>
                        </ul>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('eventos.ReportesEventos') }}" title="Reporte Eventos"
                                    data-placement="left">
                                    <i class="tio-circle nav-indicator-icon"></i>
                                    <span class="text-truncate">Reporte Eventos</span>
                                </a>
                            </li>
                        </ul>
                        {{--Inicio Apartado entrega papel --}}
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('eventos.VistaPapel') }}" title="Reporte Papel"
                                    data-placement="left">
                                    <i class="tio-circle nav-indicator-icon"></i>
                                    <span class="text-truncate">Papel </span>
                                </a>
                            </li>
                        </ul>
                        {{--Fin Apartado Entrega papel--}}
                    </li>
                    @endif


                    @if (auth()->user()->hasPermissionTo('usuario.index'))
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Pages">
                                <i class="tio-apps nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Catalogos</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">

                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/parametros') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Parametros</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">

                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/puestos') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Puestos</span>
                                    </a>
                                </li>
                            </ul>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">

                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/calendario') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Calendario</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/saldovacaciones') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Saldo de Vacaciones</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! url('/asisweb') !!}" title="Welcome message"
                                        data-placement="left">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Asisweb</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermissionTo('usuario.index') || auth()->user()->email=='bteofilo@intimark.com.mx' )
                        <!-- End Pages -->

                        <!-- Admin -->
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Apps">
                                <i class="tio-apps nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Admón
                                    Accesos</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                @if (auth()->user()->hasPermissionTo('permiso.index') &&
                                        auth()->user()->hasRole('Administrador') )
                                    <li class="nav-item">
                                        <a class="nav-link " href="{!! url('/permiso') !!}" title="Kanban">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Permisos</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('role.index') &&
                                        auth()->user()->hasRole('Administrador'))
                                    <li class="nav-item">
                                    <li class="nav-item">
                                        <a class="nav-link " href="{!! url('/role') !!}" title="File manager">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Roles</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('acceso.index') &&
                                        auth()->user()->hasRole('Administrador'))
                                    <li class="nav-item">
                                    <li class="nav-item">
                                        <a class="nav-link " href="{!! url('/acceso') !!}" title="File manager">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Accesos</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('usuario.index'))
                                    <li class="nav-item">
                                        <a class="nav-link " href="{!! url('/usuario') !!}" title="File manager">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">Usuarios</span>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                        <!-- End Admin -->
                        <!-- Admin -->
                        <li class="navbar-vertical-aside-has-menu ">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle "
                                href="javascript:;" title="Apps">
                                <i class="tio-apps nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Admón
                                    Autorización</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                                <li class="nav-item">
                                    <a class="nav-link " href="{!! url('/incidencia') !!}" title="Kanban">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Incidencias</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="{!! url('/responsables') !!}" title="File manager">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Responsables</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="{!! url('/autorizacion') !!}" title="File manager">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Matriz Permisos</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="{!! url('/matrizautorizacion') !!}" title="File manager">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Matriz Autorizaciones</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- End Admin -->

                    @endif

                    <li class="nav-item">
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </div>
</aside>
