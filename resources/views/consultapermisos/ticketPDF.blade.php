<!DOCTYPE html>
<html>
<head>
    <title>Permisos</title>
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
    <div id="encabezado">
        <table style="width:100%; align:center">
        <tr>
            <td width=30% style="text-align:center"><img src="../public/img/logo.png" width="100px" heigth="82px" ></td>
            <td width=30%>&nbsp;</td>
            <td style="text-align:center; font-size: 10px" >Folio: {{ $id }}</td>       
         </tr>
         <tr>
            <td style="text-align:center; font-size: 10px " colspan =3><p >AUTORIZACION DE ENTRADA-SALIDA DEL PERSONAL</p></td>
         </tr>
        </table>
        @forelse($permisos as $per)

        <table style="width:100%; align:center; font-size: 10px ">
            <tr>
                <td>Fecha: </td>
                <td><b> {{ optional($per)->fecha_solicitud }} </b></td>
                <td colspan=2>&nbsp;</td>
                <td>Area: </td>
                <td><b> {{ optional($per)->Departamento }}</b></td>
            </tr>
            <tr>
                <td>De: </td>
                <td><b>{{ $per->Nom_Emp.' '.$per->Ap_Pat.' '.$per->Ap_Mat }}</b></td>
                <td>Num.: </td>
                <td><b>{{ $per->No_Empleado }}</b></td>
                <td>Turno: </td>
                <td><b>{{ $per->Id_Turno }}</b></td>
            </tr>
            <tr>
                <td>Frecuencia: </td>
                <td><b> </b></td>
            </tr>

            <tr>
                <td colspan=6>Por medio del presente, solicito me sea otorgado un periodo vacacional de {{ optional($per)->folio_per}} días laborables </td>
                
            </tr>
            <tr>
                <td>Fecha de Ingreso: </td>
                <td><b> {{ optional($per)->folio_per }}</b></td>
            </tr>
        </table>

    </div>
    <div id="customers">
        <table style="width:100%; border: solid 1px #000; border-collapse: collapse; font-size: 10px ">
            <thead>         
                <tr class='warning' style="border: solid 1px #000; border-collapse: collapse;">
                    <th style="border: solid 1px #000; border-collapse: collapse;">Dias Disponibles  {{ optional($per)->folio_per }} correspondientes al periodo de 2022 -2023, a disfrutar del  {{ $per->fech_ini_per }} al  {{ $per->fech_fin_per }}</th>
                </tr>
               
            </thead>
            <tbody>
                <tr class='warning' style="border: solid 1px #000; border-collapse: collapse;">
                    <th style="border: solid 1px #000; border-collapse: collapse;">Menos  {{ optional($per)->folio_per }} dias solicitados en este memorándum, restan por disfrutar  {{ optional($per)->folio_per }} días</th>
                </tr>
                        </tbody>
        </table>
        @empty
       @endforelse                  
 
        <br>
        <table style="width:100%; align:center; font-size: 10px " >
            <tr style="text-align:center;"  >
                <td><strong>Director o Gerente de Area</strong></td>
                <td><strong>Firma del Solicitante</strong></td>
            </tr>
            <tr style="text-align:center;">
                <td><br><br><br><br><br></td>
            </tr>
        </table>
        <table style="width:100%; align:center; font-size: 10px " >
            <tr style="text-align:center;" >
                <td>Fecha: <strong></strong></td>
                <td> Hora: <strong></strong></td>
                <td>Periodo: <strong></strong></td>
            </tr>
        </table>
       
    </div>
    </br>
    <p  style="width:100%; align:left; font-size: 10px ">{{ $cadena }}</p>

</body>
</html>