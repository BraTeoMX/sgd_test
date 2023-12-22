<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RolLogisticaDetalle;
use App\RolLogistica;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PDF;

class PDFController extends Controller
{
    public function generatePDF(RolLogistica $rollogistica)
    {
        //dd($rollogistica);
        
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y')
        // ];

        //$detalle = RolLogisticaDetalle::find($id);

        //$pdf = PDF::loadHTML('myPDF/myPDF')->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf');

        //$pdf = PDF::loadView('myPDF/myPDF', $data);

        //return $pdf->download('itsolutionstuff.pdf');
        //

        $totalreg=RolLogisticaDetalle::where('roles_logistica_id',$rollogistica->id)
       // ->where('recoleccion_asitencia','SI')
        ->count();
        $materiales=RolLogisticaDetalle::where('roles_logistica_id',$rollogistica->id)
       // ->where('recoleccion_asitencia','SI')
        ->get();
        
        $horarios=RolLogisticaDetalle::whereNotNull('folio_planta')
        ->whereNotNull('hora_llegada_planta')->whereNotNull('hora_salida_planta')
        ->where('roles_logistica_id',$rollogistica->id)->first();
      
        $largo=567.00;
        if($totalreg>4){
            $largo=(110*$totalreg)+48.2;
        }
        //dd($totalreg);
        $customPaper = array(0,0,$largo,227.04);
        $mytime = Carbon::now()->toDateTimeString();
        //dd($mytime)
        $cadena=md5('2021'.$rollogistica->id);
        // $pdf = PDF::loadHTML(
        //     'myPDF/myPDF')->setPaper($customPaper, 'landscape',  array('UTF-8','UTF8'));
        $pdf = PDF::loadView(
                'myPDF/myPDF', compact('rollogistica','materiales','totalreg','mytime','cadena','horarios'))->setPaper($customPaper, 'landscape',  array('UTF-8','UTF8'));
        $nombre='rolticket_'.$rollogistica->id.'.pdf';        
        return $pdf->download($nombre);

    }
}
