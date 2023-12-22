<aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
    <div class="navbar-vertical-container"  style="background-color: #8c8c8c;">
      <div class="navbar-vertical-footer-offset" >
        <div class="navbar-brand-wrapper justify-content-between" style="background-color: #ffffff;">
          <!-- Logo -->
  
  
            <a class="navbar-brand" href="{!! route('home') !!}" aria-label="Front">
              <img class="navbar-brand-logo" src="{!! asset('/img/logo.png') !!}" alt="Logo">
              <img class="navbar-brand-logo-mini" src="{!! asset('/img/logo.png') !!}" alt="Logo">
            </a>
  
          <!-- End Logo -->
  
          <!-- Navbar Vertical Toggle -->
          <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
            <i class="tio-clear tio-lg"></i>
          </button>
          <!-- End Navbar Vertical Toggle -->
        </div>
  
        <!-- Content -->
        <div class="navbar-vertical-content"  style="background-color: #efebe9;">
          <ul class="navbar-nav navbar-nav-lg nav-tabs">
            <!-- Dashboards -->
  
            <!-- End Dashboards -->
              <!-- Pages -->
             
          <!-- End Pages -->
         <!--<li class="nav-item">
              <div class="nav-divider"></div>
            </li>-->
            <!-- Pages -->
      
           
            @if(auth()->user()->hasRole("Jefe Administrativo") )
            
              <li class="navbar-vertical-aside-has-menu " >
                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle " href="javascript:;" title="Pages">
                  <i class="tio-pages-outlined nav-icon"></i>
                  <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Vacaciones</span>
                </a>
  
                <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
               
                    <li class="nav-item">
                      <a class="nav-link" href="{!! url('/vacaciones.solicitarvacaciones') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Solicitud de Vacaciones</span>
                      </a>
                    </li>

  
                    <li class="nav-item">
                      <a class="nav-link" href="{!! url('/vacaciones') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Autorizar Vacaciones</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{!! url('/consultavacaciones') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Reporte Vacaciones</span>
                      </a>
                    </li>
                </ul>
              </li>
              @endif
              @if(auth()->user()->hasRole("VP")  )
            
            <li class="navbar-vertical-aside-has-menu " >
              <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle " href="javascript:;" title="Pages">
                <i class="tio-pages-outlined nav-icon"></i>
                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Incidencias</span>
              </a>

              <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
              
                  <li class="nav-item">
                    <a class="nav-link" href="{!! url('/vacaciones') !!}" title="Welcome message" data-placement="left">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Autorizar Vacaciones</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{!! url('/consultavacaciones') !!}" title="Welcome message" data-placement="left">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Reporte Vacaciones</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{!! url('/vacaciones') !!}" title="Welcome message" data-placement="left">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Excepciones</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{!! url('/consultaexcepciones') !!}" title="Welcome message" data-placement="left">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Reporte Excepciones</span>
                    </a>
                  </li>
              </ul>
            </li>
            @endif
              @if(auth()->user()->hasPermissionTo("vacaciones.solicitarvacaciones") && auth()->user()->hasPermissionTo("usuario.index"))
              
                  <li class="nav-item">
                      <a class="nav-link" href="{!! url('/vacaciones.solicitarvacaciones') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Solicitud de Vacaciones</span>
                      </a>
                    </li>

                   <!-- <li class="nav-item">
                      <a class="nav-link" href="{!! url('/formatopermisos') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Solicitud de Permisos</span>
                      </a>
                    </li>-->

                    @if(auth()->user()->hasRole("Team Leader") ) 
                    <li class="nav-item">
                      <a class="nav-link" href="{!! url('/vacaciones') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Vacaciones Modulo</span>
                      </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasRole("Team Leader") ))
                    <li class="nav-item">
                      <a class="nav-link" href="{!! url('/estatusvacaciones') !!}" title="Welcome message" data-placement="left">
                        <span class="tio-circle nav-indicator-icon"></span>
                        <span class="text-truncate">Estatus Vacaciones</span>
                      </a>
                    </li>
                    @endif
                @endif
          
                @if(auth()->user()->hasPermissionTo("usuario.index") )
           
           <li class="navbar-vertical-aside-has-menu " >
             <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle " href="javascript:;" title="Pages">
             <i class="tio-apps nav-icon"></i>
               <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Catalogos</span>
             </a>
             <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
              
              <li class="nav-item">
                <a class="nav-link" href="{!! url('/parametros') !!}" title="Welcome message" data-placement="left">
                  <span class="tio-circle nav-indicator-icon"></span>
                  <span class="text-truncate">Parametros</span>
                </a>
              </li>
          </ul>
             <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
              
                 <li class="nav-item">
                   <a class="nav-link" href="{!! url('/puestos') !!}" title="Welcome message" data-placement="left">
                     <span class="tio-circle nav-indicator-icon"></span>
                     <span class="text-truncate">Puestos</span>
                   </a>
                 </li>
             </ul>
            
             <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
              
              <li class="nav-item">
                <a class="nav-link" href="{!! url('/calendario') !!}" title="Welcome message" data-placement="left">
                  <span class="tio-circle nav-indicator-icon"></span>
                  <span class="text-truncate">Calendario</span>
                </a>
              </li>
          </ul>
           </li>
          
     @endif

         @if(auth()->user()->hasPermissionTo("usuario.index"))
            <!-- End Pages -->
      
            <!-- Admin -->
              <li class="navbar-vertical-aside-has-menu ">
                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle " href="javascript:;" title="Apps">
                  <i class="tio-apps nav-icon"></i>
                  <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Admón Accesos</span>
                </a>
               
                <ul class="js-navbar-vertical-aside-submenu nav nav-sub">
                @if(auth()->user()->hasPermissionTo("permiso.index") )
                <li class="nav-item">
                    <a class="nav-link " href="{!! url('/permiso') !!}" title="Kanban">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Permisos</span>
                    </a>
                  </li>
                  @endif
                  @if(auth()->user()->hasPermissionTo("role.index") )
                <li class="nav-item">
                  <li class="nav-item">
                    <a class="nav-link " href="{!! url('/role') !!}" title="File manager">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Roles</span>
                    </a>
                  </li>
                  @endif
                  @if(auth()->user()->hasPermissionTo("role.index") )
                <li class="nav-item">
                  <li class="nav-item">
                    <a class="nav-link " href="{!! url('/acceso') !!}" title="File manager">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Accesos</span>
                    </a>
                  </li>
                  @endif
                  <li class="nav-item">
                    <a class="nav-link " href="{!! url('/usuario') !!}" title="File manager">
                      <span class="tio-circle nav-indicator-icon"></span>
                      <span class="text-truncate">Usuarios</span>
                    </a>
                  </li>
                  
  
                </ul>
              </li>
            <!-- End Admin -->
             <!-- Admin -->
             <li class="navbar-vertical-aside-has-menu ">
                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle " href="javascript:;" title="Apps">
                  <i class="tio-apps nav-icon"></i>
                  <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Admón Autorización</span>
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
                      <span class="text-truncate">Autorización</span>
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
  