<!DOCTYPE html>
<html>
<head>
    <title>Ticket recoleccion</title>
    <style>
@page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 0cm 0.5cm 0.5cm;
        }
</style>
</head>
<body>
<div>
    <div style="float:left;width: 30%;"><img src="http://inventarioIntimark.com/img/logo_ticket2021.png" width="100px" heigth="82px" ></div>
    <div style="float:left;width: 70%;"> <h6 style="text-align:right">Recuperadora de <br>Materiales
    Ocampo<br> de Toluca SA de CV</h6></td>
</div>    
    <h3 style="text-align:center">Sucursal {{ $rollogistica->sucursalCliente->sucursal }}</h3>
    <h3 style="text-align:center">Cliente {{ $rollogistica->convenio->catCliente->nombre_comercial }}</h3>
    <p>Sitio: <strong>{{ optional($rollogistica->sitiosAll)->sitio }}</strong> </p>
    <p>Chofer: <strong>{{ optional($rollogistica->catChofer)->nombre }}</strong> </p>
    <p>Unidad: <strong>{{optional($rollogistica->vehiculo)->tipo_unidad}}</strong> </p>
    <p>Placas: <strong>{{optional($rollogistica->vehiculo)->placas}}</strong></p>
    <p>Recoleccion finalizada: <strong>{{$rollogistica->recolecion_terminada}}</strong></p>
    </br>
    <p> 
        Folio Intimark: <strong>{{ $rollogistica->convenio->catCliente->serie }} - {{ $rollogistica->id }}</strong>
     </p>
    </br>
    </br>
    <p> Folio planta: <strong>{{ optional($horarios)->folio_planta }}</strong> </p>
    </br>
    <p>Fecha: <strong>{{ $rollogistica->fecha->format('d-m-Y') }}</strong></p>
    <div id="customers">
        <table style="width:100%">
            <colgroup>
                <col width="15%">
                <col width="55%">
                <col width="30%">
            </colgroup>
            <thead>         
                <tr class='warning'>
                    <th style="text-align:left">#</th>
                    <th>Material</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
            {{ $i=1 }}
            @forelse($materiales as $material)
                <tr>
                    <td style="text-align:left">{{ $i++ }}</td>
                    <td>
                        {{ $material->conveniosdetalles->material_cliente }} 
                       
                    </td>
                    <td style="text-align:right">
                    
                        <strong>{{ $material->total_planta }} {{ $material->conveniosdetallesAll->CatUnidadMedida->clave }}</strong>   
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No se han registrado materiales</td>
                </tr>
            @endforelse   
               
            </tbody>
        </table> 
    </div>
    </br>
    </br>
    <p>Total de materiales: <strong>{{ $totalreg }}</strong> </p>
    </br>
    <!-- <p>Fecha impresion: <strong>{{ $mytime }}</strong> </p> -->
    <p>Entrada: <strong>{{ substr(optional($horarios)->hora_llegada_planta,11,5) }}</strong>
    &nbsp;&nbsp;&nbsp; -&nbsp;&nbsp; &nbsp;
    Salida: <strong>{{  substr(optional($horarios)->hora_salida_planta,11,5) }}</strong></p> 
    </br>
    <p>{{ $cadena }}</p>

</body>
</html>