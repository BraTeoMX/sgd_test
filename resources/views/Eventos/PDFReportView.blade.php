<!DOCTYPE html>
<html>
    <style>
        @page {
            margin: 0.5cm 2.5cm 0.5cm 2.5cm;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        table {
            color: black;
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
            border-radius: 5px; /* Agregar esquinas redondeadas a la tabla */
            overflow: hidden; /* Para asegurar que las esquinas redondeadas se muestren correctamente */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para agregar profundidad */
            transition: box-shadow 0.3s ease; /* Transición suave de la sombra */
        }

        table:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Efecto de sombra más pronunciado al pasar el ratón */
        }

        th, td, tr, tfoot {
            padding: 5px; /* Aumentar el espacio interno */
            text-align: center;
            border: 0.5px solid black; /* Reducir el grosor de los bordes */
        }

        th {
            background-color: #765341; /* Color naranja suave para el encabezado */
            font-weight: lighter; /* Utilizar una fuente más ligera */
        }

        tr:odd {
            background-color: rgb(195, 194, 194); /* Color más oscuro para filas alternas */
        }

        .borde {
            border: 0.5px solid black; /* Reducir el grosor de los bordes */
        }

        .sin-borde {
            border: none;
        }
    </style>


<body>
    @php
            $reportesQuery = optional($datosReporte)['reportesQuery'];
            $optionsave = optional($datosReporte)['optionsave'];
            $tipoEvento = optional($datosReporte)['request']->tipo_evento;
            $totalRegistros = optional($datosReporte)['totalRegistros'];
        @endphp
    <div class="card" style="height: auto; width: auto;">
        <div class="card-header">
            <table style="width:100%; align:center;">
                 <tr>
                    <td class="sin-borde" style="text-align:center;"><h1>Reporte {{ optional($datosReporte)['nombre'] }}</h1></td>
                 </tr>
                </table>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>Numero de empleado</th>
                        <th>Nombre del evento</th>
                        <th>Nombre del empleado</th>
                        <th>Puesto</th>
                        <th>Departamento</th>
                        <th>Planta</th>
                        @if (optional($datosReporte)['optionsave'] == '5')
                            <th>Rollos</th>
                        @else
                            <th>Asistencia</th>
                        @endif

                        <th>Fecha de asistencia</th>
                        @if (optional($datosReporte)['optionsave'] == '5')
                            <th>Firma</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset(optional($datosReporte)['reportesQuery']))
                        @foreach (optional($datosReporte)['reportesQuery'] as $reporte)
                            <tr>
                                <td>{{ $reporte->no_empleados }}</td>
                                <td>{{ $reporte->tipo_evento }}</td>
                                <td>{{ $reporte->nombre_empleado }}</td>
                                <td>{{ $reporte->Puesto }}</td>
                                <td>{{ $reporte->Departamento }}</td>
                                @if ( $reporte->Planta=='Intimark1')
                                    <td>Ixtlahuaca</td>
                                @elseif ($reporte->Planta=='Intimark2')
                                    <td>San Bartolo</td>
                                @endif
                                @if (optional($datosReporte)['request']->tipo_evento == '5')
                                    <td>2</td>
                                @elseif ($reporte->asistencia==null)
                                    <td>No ha confirmado asistencia</td>
                                @else
                                    <td>{{ $reporte->asistencia }}</td>
                                @endif
                                <td>{{ $reporte->created_at }}</td>
                                @if (optional($datosReporte)['optionsave'] == '5')
                                    <td>
                                        @php
                                        $numeroEmpleado = $reporte->no_empleados;
                                        $rutaImagen = public_path('img/Firmas/' . $numeroEmpleado . '.jpg');
                                        @endphp
                                        @if (file_exists($rutaImagen))
                                            <img height="50px" width="100px" src="data:image/jpg;base64,{{ base64_encode(file_get_contents($rutaImagen)) }}" alt="">
                                        @else
                                            Firma no encontrada
                                        @endif
                                    </td>

                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <table class="table" style="text-align: left">
                <tr>
                    @if (optional($datosReporte)['optionsave'] == '5')
                        <th style="text-align: left">Total de rollos: {{ optional($datosReporte)['totalRegistros'] * 2}}</th>
                    @else
                        <th style="text-align: left">Total de Registros: {{ optional($datosReporte)['totalRegistros']}}</th>
                    @endif
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
