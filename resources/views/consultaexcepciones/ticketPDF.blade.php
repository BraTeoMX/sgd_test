<!DOCTYPE html>
<html>
<head>
    <title>Vacaciones</title>
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
            <td style="text-align:center; font-size: 10px " colspan =3><p >MEMORANDUM DE SOLICITUD DE VACACIONES AL AREA DE NOMINAS</p></td>
         </tr>
        </table>
        @forelse($vacaciones as $vac)

        <table style="width:100%; align:center; font-size: 10px ">
            <tr>
                <td>Fecha: </td>
                <td><b> {{ optional($vac)->fecha_solicitud }} </b></td>
                <td colspan=2>&nbsp;</td>
                <td>Area: </td>
                <td><b> {{ optional($vac)->Departamento }}</b></td>
            </tr>
            <tr>
                <td>De: </td>
                <td><b>{{ $vac->Nom_Emp.' '.$vac->Ap_Pat.' '.$vac->Ap_Mat }}</b></td>
                <td>Num.: </td>
                <td><b>{{ $vac->No_Empleado }}</b></td>
                <td>Turno: </td>
                <td><b>{{ $vac->Id_Turno }}</b></td>
            </tr>
            <tr>
                <td>Frecuencia: </td>
                <td><b> </b></td>
            </tr>

            <tr>
                <td colspan=6>Por medio del presente, solicito me sea otorgado un periodo vacacional de {{ optional($vac)->folio_vac}} días laborables </td>
                
            </tr>
            <tr>
                <td>Fecha de Ingreso: </td>
                <td><b> {{ optional($vac)->folio_vac }}</b></td>
            </tr>
        </table>

    </div>
    <div id="customers">
        <table style="width:100%; border: solid 1px #000; border-collapse: collapse; font-size: 10px ">
            <thead>         
                <tr class='warning' style="border: solid 1px #000; border-collapse: collapse;">
                    <th style="border: solid 1px #000; border-collapse: collapse;">Dias Disponibles  {{ optional($vac)->folio_vac }} correspondientes al periodo de 2022 -2023, a disfrutar del  {{ $vac->fech_ini_vac }} al  {{ $vac->fech_fin_vac }}</th>
                </tr>
               
            </thead>
            <tbody>
                <tr class='warning' style="border: solid 1px #000; border-collapse: collapse;">
                    <th style="border: solid 1px #000; border-collapse: collapse;">Menos  {{ optional($vac)->folio_vac }} dias solicitados en este memorándum, restan por disfrutar  {{ optional($vac)->folio_vac }} días</th>
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