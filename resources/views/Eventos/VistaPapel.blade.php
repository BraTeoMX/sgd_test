@extends('layouts.main') {{-- Define la plantilla principal para esta vista --}}

@section('content')
    {{-- Sección de contenido --}}

    <div class="row"> {{-- Contenedor de fila --}}
        <div class="col-12"> {{-- Columna con ancho completo en dispositivos grandes --}}
            <div class="card"> {{-- Tarjeta de presentación --}}
                <div class="card-header">
                    <h1 class= "textoH1">Evento: Papel</h1> {{-- Título en la cabecera de la tarjeta --}}
                    @if (auth()->user()->hasRole('Seguridad e Higiene') ||
                            auth()->user()->hasRole('Administrador Sistema') ||
                            auth()->user()->hasRole('Jefe Administrativo'))
                        <a href="{{ route('eventos.DeleteRegPre') }}" class="btn btn-danger deleteReg">Eliminar registros</a>
                    @endif
                </div>
                <div class="card-body"> {{-- Cuerpo de la tarjeta --}}
                    @if (session('error'))
                        {{-- Verifica si hay un mensaje de error en la sesión --}}
                        <div class="alert alert-danger">
                            {{ session('error') }} {{-- Muestra el mensaje de error --}}
                        </div>
                    @endif
                    <div class="container"> {{-- Contenedor principal --}}
                        <div class="row">
                            <div class="col-6">
                                <form method="POST" action="{{ route('eventos.RegistroVistaPapel') }}">
                                    {{-- Formulario que envía datos a una ruta --}}
                                    @csrf {{-- Token CSRF para protección contra ataques --}}
                                    <div class="form-group" id="textboxDiv">
                                        <label for="datos_evento">Registra tu tag o número de empleado:</label>
                                        <input type="text" name="datos_evento" id="datos_evento" class="form-control"
                                            value="{{ old('datos_evento') }}" type="text" inputmode="numeric">
                                        <script>
                                            // Obtén el elemento de entrada por su ID
                                            var input = document.getElementById('datos_evento');
                                            // Enfoca el campo de entrada cuando la pagina se carga
                                            input.focus();
                                        </script>
                                    </div>
                                    <div class="d-flex justify-content-between"> {{-- Contenedor con elementos distribuidos horizontalmente y alineados al espacio entre ellos --}}
                                        <div>
                                            <button type="submit" class="btn btn-primary" id="registrarBtn">Registrar asistencia</button> {{-- Botón para enviar el formulario --}}
 
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-6">
                                <style>
                                    .custom-shadow-table {
                                        box-shadow: 25px 25px 25px 15px rgba(205, 221, 236, 0.767);
                                        /* Ajusta los valores según tu preferencia */

                                    }

                                    /*Aqui es para cambiar el color que se requiere personalizar, asi como el texto  */
                                    .custom-header {
                                        background-color: #7fa1cc;
                                        /* Cambia el color a tu preferencia */
                                        color: rgb(0, 0, 0);
                                        /* Cambia el color del texto si es necesario */
                                    }

                                    .custom-rounded-table {
                                        border-top-left-radius: 10px;
                                        /* Ajusta el valor según tu preferencia */
                                        border-top-right-radius: 10px;
                                        /* Ajusta el valor según tu preferencia */
                                        border-radius: 15px;
                                        /* Ajusta el valor según tu preferencia */
                                    }
                                </style>
                                <table class="table table-hover table-bordered custom-shadow-table custom-rounded-table">

                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col"><strong>Ubicación</strong></th>
                                            <th scope="col">Conteo inicial</th>
                                            <th scope="col">Conteo Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($ConteoRegistroIxtlahuaca)
                                        <tr>
                                            <td scope="row">Ixtlahuaca</td>
                                            <td>{{ $ConteoRegistroIxtlahuaca }}</td>
                                            <td># </td>
                                        </tr>
                                        @endisset
                                        @isset($ConteoRegistroSanBartolo)
                                        <tr>
                                            <td scope="row">San Bartolo</td>
                                            <td>{{ $ConteoRegistroSanBartolo }}</td>
                                            <td>#</td>
                                        </tr>
                                        @endisset
                                        <tr>
                                            <td scope="row"><strong>Total General</strong></td>
                                            <td>{{$ConteoRegistros}}</td>
                                            <td> # </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="messageDiv" class="alert alert-info text-center"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;"></div>
    {{-- Mensaje de información oculto en la parte superior centrada de la página --}}
    <script>
        document.getElementById("tipo_evento").addEventListener("change", function() {
            {{-- Escucha el evento de cambio del menú desplegable --}}
            var tipoEvento = document.getElementById("tipo_evento").value;
            var textboxDiv = document.getElementById("textboxDiv");
            textboxDiv.style.display = tipoEvento !== "default" ? "block" : "none";
            {{-- Muestra u oculta el campo de entrada --}}
        });

        document.getElementById("registrarBtn").addEventListener("click", function(e) {
            {{-- Escucha el evento de clic en el botón "Registrar asistencia" --}}
            var tipoEvento = document.getElementById("tipo_evento").value;
            var datosEvento = document.getElementById("datos_evento").value;

            if (tipoEvento === "default" || datosEvento.trim() === "") {
                e.preventDefault();
                showMessage("Seleccione un evento y complete el campo de datos válidos.", "");
                {{-- Evita enviar el formulario y muestra un mensaje de error --}}
            }
        });
    </script>
@endsection
@section('scriptBFile')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            var auxiliar = "{{ session('auxiliar') }}"; // Leer el valor de la sesión
            var datos = "{{ session('datos') }}"; // Asumiendo que también estás enviando 'datos' si es necesario

            switch(auxiliar) {
                case '0':
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'No se encontraron datos para el registro',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    break;
                case '1':
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Registro guardado con exito!<br>' + datos,
                        showConfirmButton: false,
                        timer: 1000
                    });
                    break;
                
                case '2':
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: datos +'<br>Ya confirmó su asistencia!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    break;
                case '3':
                    Swal.fire({
                        position: 'center',
                        icon: 'info',
                        title: datos +'<br>Puesto No permitido',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    break;
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tipoEvento = document.getElementById("tipo_evento").value;
            var eventosExistentes = document.getElementById("eventos-existentes");
            var titulome = document.getElementById("titulome");
            var isHidden = localStorage.getItem("elementsHidden") === "true";

            if (isHidden) {
                eventosExistentes.style.display = "none";
                titulome.style.display = "none";
            } else {
                if (tipoEvento !== "default") {
                    eventosExistentes.style.display = "none";
                    titulome.style.display = "none";
                }
            }

            document.getElementById("tipo_evento").addEventListener("change", function() {
                var tipoEvento = document.getElementById("tipo_evento").value;
                var textboxDiv = document.getElementById("textboxDiv");

                if (tipoEvento !== "default") {
                    textboxDiv.style.display = "block";
                    eventosExistentes.style.display = "none";
                    titulome.style.display = "none";
                    localStorage.setItem("elementsHidden", "true");
                } else {
                    textboxDiv.style.display = "none";
                }
            });
        });
    </script>
        
    <style>
        /* Estilo por defecto */
        .title-selected1 {
            background-color: #ff0d00;
            /* Cambia el color a tu elección */

        }

        .title-selected2 {
            background-color: #229954;
            /* Cambia el color a tu elección */

        }

        .btn-Sim {
            background-color: #ff0d00;
            /* Cambia el color de fondo a tu elección para el evento "Fiesta" */
            color: white;
            /* Cambia el color del texto a tu elección */
        }

        .btn-sa12 {
            background-color: #229954;
            /* Cambia el color de fondo a tu elección para el evento "sa12" */
            color: white;
            /* Cambia el color del texto a tu elección */
        }

        /*Apartado para la seccion de colores evento  Entrega despensa */
        .title-selected3 {
            background-color: #7c6b6b;
            /* Cambia el color a tu elección */

        }

        .btn-EaDa6 {
            background-color: #7c6b6b;
            /* Cambia el color de fondo a tu elección para el evento "Fiesta" */
            color: white;
            /* Cambia el color del texto a tu elección */
        }

        .textoH1 {
            color: rgb(42, 39, 39);
        }
    </style>
@endsection
