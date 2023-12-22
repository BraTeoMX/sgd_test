<%@page contentType="text/html" pageEncoding="UTF-8"%> 
<%@taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c"%>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="cache-control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Sistema de Reinscripciones</title>
        <link href="/CDN-ITT/css/base.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/font-awesome.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/general.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/jquery-ui.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/tablas.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/bootstrap-datepicker.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/catalogos-tablas.estandarITT.css" rel="stylesheet">
        <link href="/CDN-ITT/css/catalogos-modal.estandarITT.css" rel ="stylesheet">
        <link href="css_tec/tables.css" rel="stylesheet">        
        <link href="css_tec/general.css" rel="stylesheet">                
        <link href="css_tec/reinscripcionesAlu.css" rel="stylesheet">
        <link href="css_tec/botones.css" rel="stylesheet">
        <link href="css_tec/loader_spinner.css" rel="stylesheet">
        <!--<script type="text/javascript" src="/jquery/jquery.hint.js"></script>-->
        <link rel="icon" href="imagenes/logo-final.png"></link>
        <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>-->
        <!-- <link href="css/BuscarMateria.css" rel="stylesheet" type="text/css"></link> -->
        <link rel="stylesheet" type="text/css" href="shadowbox/css/shadowbox.css"></link>
        <link rel="stylesheet" href="jquery/development-bundle/themes/redmond/jquery.ui.all.css" type="text/css"></link>
        <!-- <link rel="stylesheet" href="jquery/development-bundle/demos/demos.css" type="text/css"> -->
        <link rel="stylesheet" href="css/menu.css" type="text/css"></link>
        <link rel="stylesheet" href="css/botones.css" type="text/css"></link> 
        <link href="css/reinscripcion.css" rel="stylesheet" type="text/css"></link>
        <!-- Para los alert -->        
        <link href="css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen"></link> 
    </head>
    <body>
        <%@include file="../../templates/header.jsp" %>
        <div id="pageLoader">
            <div id="pageSpinner">
                <%@include file="../templates/spinner.jsp" %>
            </div>
        </div>
        <div class="container">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand">Módulo de Reinscripción para Alumnos</a>                        
                    </div>       
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">                
                            <li class="dropdown">                                 
                                <a href="/Reinscripciones/salir_JSP.do" onClick="checkSession();" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">                             
                                    <i class="fa fa-sign-out"></i>
                                    Salir                        
                                </a>                       
                            </li>
                        </ul>                            
                    </div>  
                </div><!-- /.container-fluid  <input type=button name=boton value=Cerrar onclick="window.close()">  -->
            </nav>
            <%@include file="../templates/datosAlumno.jsp" %>
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div role="tabpanel">  
                        <div align="right" class="form-horizontal navbar-form">     
                            <div class="input-group" id="EspacioBuscarMateriaPorGrupo" >
                                <input type="text" id="txt_buscar" class="form-control input-xs" placeholder="Grupo o materia"></input>
                                <div class="input-group-btn" id="EspacioBuscarMateriaPorGrupo">
                                    <button class="btn btn-default btn-xs" id="botonBuscarMateria"><i class="fa fa-search"></i></button>
                                </div>
                            </div>                                                            
                        </div>
                        <!-- Nav tabs -->                        
                        <ul class="nav nav-tabs" role="tablist">                            
                            <li role="presentation" id="li_busqueda"><a href="#busqueda_tab" data-toggle="tab">Resultado de búsqueda</a></li>
                            <li role="presentation" class="active" id="li_todas"><a href="#todas" data-toggle="tab">Materias <!-- <i id="spCarga" class="fa fa-spinner fa-spin fa-fw"></i> --> </a></li>                                                        
                            <li role="presentation" id="li_1"><a href="#1" data-toggle="tab">Primero</a></li>
                            <li role="presentation" id="li_2"><a href="#2" data-toggle="tab">Segundo</a></li>                                        
                            <li role="presentation" id="li_3"><a href="#3" data-toggle="tab">Tercero</a></li>                                        
                            <li role="presentation" id="li_4"><a href="#4" data-toggle="tab">Cuarto</a></li>                                        
                            <li role="presentation" id="li_5"><a href="#5" data-toggle="tab">Quinto</a></li>                                        
                            <li role="presentation" id="li_6"><a href="#6" data-toggle="tab">Sexto</a></li>                                        
                            <li role="presentation" id="li_7"><a href="#7" data-toggle="tab">Séptimo</a></li>                                        
                            <li role="presentation" id="li_8"><a href="#8" data-toggle="tab">Octavo</a></li>                                        
                            <li role="presentation" id="li_9"><a href="#9" data-toggle="tab">Noveno</a></li>                                        
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">                            
                            <div role="tabpanel" class="tab-pane active" id="todas">
                                <br/>
                                <div class="scrolltab" id="ul_todas">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_todas">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>                                                        
                            </div>
                            <div role="tabpanel" class="tab-pane scrolltab" id="1">    
                                <br/>                                
                                <div class="scrolltab" id="ul_1">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_1">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane scrolltab" id="2">
                                <br/>
                                <div class="scrolltab" id="ul_2">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_2">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="3">
                                <br/>
                                <div class="scrolltab" id="ul_3">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_3">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="4">
                                <br/>
                                <div class="scrolltab" id="ul_4">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_4">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="5">
                                <br/>
                                <div class="scrolltab" id="ul_5">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_5">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="6">
                                <br/>
                                <div class="scrolltab" id="ul_6">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_6">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="7">
                                <br/>
                                <div class="scrolltab" id="ul_7">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_7">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="8">
                                <br/>
                                <div class="scrolltab" id="ul_8">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_8">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="9">
                                <br/>
                                <div class="scrolltab" id="ul_9">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_9">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>-</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                            <div role="tabpanel" class="tab-pane scrolltab" id="busqueda_tab">
                                <br/>
                                <div class="scrolltab" id="ul_busqueda">
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_busqueda">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>
                                                <th>Créditos</th>
                                                <th>Lunes</th>
                                                <th>Martes</th>
                                                <th>Miercoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>                                                
                                                <th>Búsqueda</th>
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>  
                            </div>                                                        
                        </div>
                    </div>
                </div>                
            </div>
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div role="tabpanel">                        
                        <!-- Nav tabs -->                        
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a role="tab" data-toggle="tab">Materias Agregadas </a></li>                                                                                             
                        </ul>                        
                        <!-- Tab panes -->
                        <div class="tab-content">                            
                            <div role="tabpanel" class="tab-pane active">
                                <br/>                                                                                                             
                                <div class="scrolltab">                                    
                                    <table class="table table-bordered table-responsive tablesorter" id="tbl_seleccionadas">
                                        <thead>
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Materia</th>                                                
                                                <th>Lunes</th>
                                                <th>Martes</th>                                            
                                                <th>Miércoles</th>
                                                <th>Jueves</th>
                                                <th>Viernes</th>
                                                <th>Sábado</th>
                                                <th>Créditos</th>                                                
                                                <th>Oportunidad</th>
                                                <th>--</th>                                                               
                                            </tr>
                                        </thead>                                        
                                    </table>
                                </div>                                
                            </div>                              
                        </div>                         
                    </div>                                                        
                </div>
            </div>             
            <div id="message_box">
                <center>
                    <table>
                        <tr>
                            <td class="tdangosto2">Tiempo de Sesi&oacute;n</td>
                        </tr>
                        <tr>
                            <td class="tdangosto2">
                                <form name='crono'>
                                    <input id="temp" type='text' class='cont' size='10' name='contador' title='Cronometro'></input>
                                </form>
                            </td>
                        </tr>
                    </table>
                </center>
            </div> 
            <%@include file="../../templates/footer.jsp" %> 
            <script src="/CDN-ITT/js/jquery.estandarITT.js"></script>     
            <script src="/CDN-ITT/js/base.estandarITT.js"></script> 
            <script language="javascript" src="jquery/js/jquery-1.5.1.min.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/jquery-1.5.1.js"></script>
            <script type="text/javascript" src="jquery/js/jquery-1.4.2.min.js"></script>	<!--EEEESSSSTEEEE BLOQUEA-->
            <script language="javascript" src="jquery/js/jquery-ui-1.8.11.custom.min.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.core.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.widget.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.tabs.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.autocomplete.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.draggable.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.position.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.resizable.js"></script>
            <script type="text/javascript" src="jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
            <script language="javascript" src="js/flotante.js"></script>
            <script type="text/javascript" src="js/cargar_materias2.js"></script>
            <script type="text/javascript" src="js/carga_seleccion2.js"></script>
            <script type="text/javascript" src="js/revisa_especialidad.js"></script>
            <script type="text/javascript" src="js/valida_caso_critico.js"></script>
            <script type="text/javascript" src="js/buscarmateria.js"></script>
            <script type="text/javascript" src="jquery/js/jquery.js"></script>
            <script type="text/javascript" src="shadowbox/js/shadowbox.js"></script>
            <script type="text/javascript" src="js/botones.js"></script>
            <script type="text/javascript" src="js/funciones_seleccionadas.js"></script>
            <script type='text/javascript' src="js/tiempo.js"></script>
            <script type='text/javascript' src="js/sesion.js"></script>
            <!-- Para los alert -->
            <script src="js/jquery.alerts.js" type="text/javascript"></script>
            <script type="text/javascript" src="jquery/js/jquery-1.4.2.min.js"></script><!--EEEESSSSTEEEE BLOQUEA-->
            <script language="javascript" src="jquery/js/jquery-ui-1.8.11.custom.min.js"></script>		
    </body>
</html>