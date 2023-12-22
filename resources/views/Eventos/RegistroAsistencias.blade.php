@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                    <h1>Asistencia: {{$contador}}</h1>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="container">
                    <h2 id="eventos-existentes">Eventos existentes</h2>
                    <form method="POST" action="{{ route('eventos.RegistroAsistencias') }}">
                        @csrf
                        <div class="form-group">
                            <label id="titulome">Elije el evento al que desees generar registros de asistencia</label>
                            <input type="hidden" name="optionsave" id="optionsave" class="form-control" value="{{ $optionsave }}">
                            <input type="hidden" name="auxiliar" id="auxiliar" class="form-control" value="{{ $auxiliar }}">
                            <input type="hidden" name="datos" id="datos" class="form-control" value="{{'Nombre: '.$nombre.'<br> Numero de empleado: '.$emplTag }}">

                            <p>Selecciona una opción</p>
                            <select name="tipo_evento" id="tipo_evento" class="form-control">
                                <option value="default" selected>Selecciona una opción</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->cve_evento }}" {{ $evento->cve_evento == $optionsave ? 'selected' : '' }}>{{ $evento->tipo_evento }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group" id="textboxDiv" style="{{ $optionsave !== 'default' ? 'display: block;' : 'display: none;' }}">
                                    <label for="datos_evento">Registra tu tag o número de empleado:</label>
                                    <input type="text" name="datos_evento" id="datos_evento" class="form-control" value="{{ old('datos_evento') }}"
                                        type="text" inputmode="numeric">
                                        <script>
                                            // Obtén el elemento de entrada por su ID
                                            var input = document.getElementById('datos_evento');

                                            // Enfoca el campo de entrada cuando la página se carga
                                            input.focus();

                                            // Validar antes de enviar el formulario
                                        </script>
                                </div>
                            </div>
                            <div class="col-6">
                                <style>
                                    .custom-shadow-table {
                                      box-shadow: 25px 25px 25px 15px rgba(205, 221, 236, 0.767);
                                    }
                                    .custom-header {
                                        background-color: #7fa1cc;
                                        color: rgb(0, 0, 0);
                                    }
                                    .custom-rounded-table {
                                        border-top-left-radius: 10px;
                                        border-top-right-radius: 10px;
                                        border-radius: 15px;
                                    }
                                </style>
                                <table class="table table-hover table-bordered custom-shadow-table custom-rounded-table">
                                    <thead class="table-primary">

                                            <th scope="col"><strong>Ubicación</strong></th>
                                            <th scope="col">Conteo Asistencia</th>
                                            <th scope="col">Conteo Registro</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="row">Ixtlahuaca</td>
                                            <td>{{$ConteoRegistroIxtlahuaca}}</td>
                                            <td>{{$RegistroIxtlahuacaTotal}}</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">San Bartolo</td>
                                            <td>{{$ConteoRegistroSanBartolo}}</td>
                                            <td>{{$RegistroSanBartoloTotal}}</td>
                                        </tr>
                                        <tr>
                                            <td scope="row"><strong>Total General</strong></td>
                                            <td>{{$ConteoRegistros}}</td>
                                            <td>{{$ConteoRegistrosTotal}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary" id="registrarBtn">Registrar asistencia del evento</button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary registrarbecarioBtn" id="registrarbecarioBtn">Registrar asistencia del becario al evento</button>
                            </div>
                                <script>
                                    document.getElementById("registrarBtn").addEventListener("click", function (e) {
            var tipoEvento = document.getElementById("tipo_evento").value;
        });
        function showMessage(message) {
            var messageDiv = document.getElementById("messageDiv");
            messageDiv.textContent = message;
            messageDiv.style.display = "block";

            setTimeout(function () {
                messageDiv.style.display = "none";
            }, 4000); // 3 segundos (3000 milisegundos)
        }
                                    </script>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="messageDiv" class="alert alert-info text-center" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;"></div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Obtiene los elementos necesarios
        var tipoEventoSelect = document.getElementById("tipo_evento");
        var registrarBtn = document.getElementById("registrarbecarioBtn");

        // Agrega un evento de cambio al menú desplegable
        tipoEventoSelect.addEventListener("change", function () {
            // Obtiene el valor seleccionado
            var selectedValue = tipoEventoSelect.value;

            // Habilita o inhabilita el botón según si se ha seleccionado una opción
            if (selectedValue !== "default") {
                registrarBtn.disabled = false; // Habilita el botón
            } else {
                registrarBtn.disabled = true; // Inhabilita el botón
            }
        });

        // Al cargar la página, verifica el estado inicial del botón
        if (tipoEventoSelect.value === "default") {
            registrarBtn.disabled = true; // Inhabilita el botón
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("registrarbecarioBtn").addEventListener("click", async function () {
            const { value: idregistro } = await Swal.fire({
                title: 'Asistencia de becarios',

                html:
                    '<input id="idregistro" name="idregistro" class="swal2-input" placeholder="Ingrese su # de registro">',
                focusConfirm: false
            });
            var ids= $('#idregistro').val();
            //alert (ids);
            if (ids !== "" && !isNaN(ids)) {
    // Verifica si idregistro no está vacío y es un número
    const numeroRegistro = parseInt(ids, 10);

    enviarDatosAlControlador(numeroRegistro);
} else {
    // Mensaje de error si el valor no es válido
    Swal.fire({
        icon: 'error',
        title: 'Número de registro inválido',
        text: 'Por favor, ingrese un número de registro válido.'
    });
}

        });
    });
</script>
<script>
    // Añade la función enviarDatosAlControlador aquí
    function enviarDatosAlControlador(data) {
        const url = '{{ route('eventos.AsistenciaBecarios') }}'; // Ruta al controlador Laravel
        const formData = new FormData();
        formData.append('numeroRegistro', data); // Nombre del becario
        formData.append('tipo_evento', $('#tipo_evento').val()); // Tipo de evento desde el formulario

        //alert(idreg);
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token CSRF necesario para las solicitudes POST
            },
        })

        .then(response => {
            if (response.ok) {
                return response.json(); // Si la respuesta es exitosa, procesa la respuesta JSON
            } else {
                throw new Error('Error al enviar los datos al controlador'); // Lanza un error si la respuesta no es exitosa
            }
        })

        .then(data => {
            if (data.error) {
                // Muestra un mensaje de error personalizado si el controlador devuelve un error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error,
                });
            } else {
                // Muestra un mensaje de éxito si todo va bien
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Asistencia confirmada.<br>',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Puedes realizar más acciones después de guardar con éxito, como actualizar la página
                // o realizar otras operaciones si es necesario
            }
        })
        .catch(error => {
            console.error('Error al enviar los datos al controlador:', error);
            // Puedes manejar el error mostrando una notificación de error o tomando otras medidas
            Swal.fire({
                icon: 'error',
                title: 'Error al enviar datos al servidor',
                text: 'Por favor, inténtalo de nuevo más tarde.'
            });
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tipoEvento = document.getElementById("tipo_evento");
        var textboxDiv = document.getElementById("textboxDiv");
        var eventosExistentes = document.getElementById("eventos-existentes");
        var titulome = document.getElementById("titulome");
        var isHidden = localStorage.getItem("elementsHidden") === "true";

        if (isHidden) {
            textboxDiv.style.display = "block";
            eventosExistentes.style.display = "none";
            titulome.style.display = "none";
        } else {
            if (tipoEvento.value === "default") {
                textboxDiv.style.display = "none";
                eventosExistentes.style.display = "block";
                titulome.style.display = "block";
            } else {
                textboxDiv.style.display = "block";
                eventosExistentes.style.display = "none";
                titulome.style.display = "none";
            }
        }

        tipoEvento.addEventListener("change", function () {
            var tipoEventoValue = tipoEvento.value;

            if (tipoEventoValue === "default") {
                textboxDiv.style.display = "none";
                eventosExistentes.style.display = "block";
                titulome.style.display = "block";
                localStorage.setItem("elementsHidden", "false");
            } else {
                textboxDiv.style.display = "block";
                eventosExistentes.style.display = "none";
                titulome.style.display = "none";
                localStorage.setItem("elementsHidden", "true");
            }
        });
    });

    document.getElementById("registrarBtn").addEventListener("click", function (e) {
        var tipoEvento = document.getElementById("tipo_evento").value;
        var datosEvento = document.getElementById("datos_evento").value;

        if (tipoEvento === "default" || datosEvento.trim() === "") {
            e.preventDefault();
            showMessage("Seleccione un evento y complete el campo de datos válidos.", "error");
        }
    });
</script>

@endsection

@section('scriptBFile')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        if ($('#auxiliar').val() == 1) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Asistencia confirmada!<br>' + $('#datos').val(),
                showConfirmButton: false,
                timer: 1500
            })
        } else if ($('#auxiliar').val() == 0) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Este usuario no cuenta con un registro<br>Dato de empleado no encontrado.',
                showConfirmButton: false,
                timer: 1500
            })
        } else if ($('#auxiliar').val() == 3) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Ya confirmó su asistencia!<br>' + $('#datos').val(),
                showConfirmButton: false,
                timer: 1500
            })
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
      // Obtiene elementos necesarios
      var tipoEventoSelect = document.getElementById("tipo_evento");
      var tituloEvento = document.querySelector(".card-header h1");
      var eventoOptions = document.getElementById("tipo_evento").getElementsByTagName('option'); // Obtén todas las opciones

      // Escucha el evento de cambio en el menú desplegable
      tipoEventoSelect.addEventListener("change", function () {
          // Obtiene el valor seleccionado
          var selectedValue = tipoEventoSelect.value;

          // Busca el texto correspondiente al valor seleccionado
          var selectedText = "";
          for (var i = 0; i < eventoOptions.length; i++) {
              if (eventoOptions[i].value === selectedValue) {
                  selectedText = eventoOptions[i].text;
                  break;
              }
          }

          // Actualiza el título en la cabecera de la tarjeta con el tipo de evento
          if (selectedValue !== "default") {
              tituloEvento.textContent = "Asistencia: " + selectedText;
          } else {
              tituloEvento.textContent = "Asistencia: {{$contador}}"; // Vuelve al título original
          }
      });
  });

  </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
      // Obtiene elementos necesarios
      var tipoEventoSelect = document.getElementById("tipo_evento");
      var tituloEvento = document.querySelector(".card-header h1");
      var backhear = document.querySelector(".card-header");
      var eventoOptions = document.getElementById("tipo_evento").getElementsByTagName('option'); // Obtén todas las opciones

      // Escucha el evento de cambio en el menú desplegable
      tipoEventoSelect.addEventListener("change", function () {
          // Obtiene el valor seleccionado
          var selectedValue = tipoEventoSelect.value;
          backhear.classList.remove("title-selected1","title-selected2");

if (selectedValue !== "default") {
// Si se selecciona una opción diferente de "default", aplica la clase para cambiar el color
backhear.classList.add("title-selected");
}if (selectedValue == "Fiesta"){
backhear.classList.add("title-selected1");
}if (selectedValue == "Misa12"){
backhear.classList.add("title-selected2");
}
var selectedValue = tituloEvento.value;


var selectedValue = tipoEventoSelect.value;
registrarBtn.classList.remove("btn-fiesta", "btn-sa12");

if (selectedValue !== "default") {
// Si se selecciona una opción diferente de "default", aplica la clase para cambiar el color de fondo
registrarBtn.classList.add("btn-selected");

if (selectedValue === "Fiesta") {
// Si el evento es "Fiesta", agrega la clase específica para "Fiesta"
registrarBtn.classList.add("btn-fiesta");
} else if (selectedValue === "Misa12") {
// Si el evento es "sa12", agrega la clase específica para "sa12"
registrarBtn.classList.add("btn-sa12");
}
}
          // Busca el texto correspondiente al valor seleccionado
          var selectedText = "";
          for (var i = 0; i < eventoOptions.length; i++) {
              if (eventoOptions[i].value === selectedValue) {
                  selectedText = eventoOptions[i].text;
                  break;
              }
          }

          // Actualiza el título en la cabecera de la tarjeta con el tipo de evento
          if (selectedValue !== "default") {
              tituloEvento.textContent = "Asistencia: " + selectedText;
          } else {
              tituloEvento.textContent = "Asistencia: {{$contador}}"; // Vuelve al título original
          }
      });
  });

  </script>
<style>
    /* Estilo por defecto */
    .title-selected1 {
    background-color: #007bff; /* Cambia el color a tu elección */

    }
    .title-selected2 {
    background-color: #01f0e8; /* Cambia el color a tu elección */

    }.btn-fiesta {
    background-color: #007bff; /* Cambia el color de fondo a tu elección para el evento "Fiesta" */
    color: white; /* Cambia el color del texto a tu elección */
    }

    .btn-sa12 {
    background-color: #01f0e8; /* Cambia el color de fondo a tu elección para el evento "sa12" */
    color: rgb(42, 39, 39); /* Cambia el color del texto a tu elección */
    }
    </style>
@endsection
