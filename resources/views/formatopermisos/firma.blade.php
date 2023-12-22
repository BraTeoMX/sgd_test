@extends('layouts.main')
@section('styleBFile')
<style>
#canvas{
    border: 1px solid black;
  
}
</style>
@endsection
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Firma del Empleado</h3>
            </div>
            <div class="card-body">
           
                    <div class="form-group">
                        <div class="row justify-content-center"">
                            <div class="col-lg-6 col-md-6" >   
                                <canvas id="canvas"  width="550px" height="300px" ></canvas>
                                <br>
                                
                              <!-- <button id="btnDescargar">Guardar</button>
                                <button id="btnLimpiar">Limpiar</button> 
-->
                                {!! BootForm::hidden('folio', $folio); !!}
                                <button type="submit" name="btnLimpiar" id='btnLimpiar' value="limpiar" class="btn btn-primary">Limpiar</button>

                              
                                <td><a class="btn btn-info"  name="btnDescargar" id='btnDescargar' href="{!! route('formatopermisos.seguridad')!!} ">Aceptar</a></td>
                                
                            </div>
                        </div>
                    </div>
              
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scriptBFile')

<script>
    const $canvas = document.querySelector("#canvas");
    const contexto = $canvas.getContext("2d");
    const COLOR = "black";
    const COLOR_PINCEL = "black";
    const COLOR_FONDO = "white";
    const GROSOR = 2;
    let xAnterior = 0, yAnterior = 0, xActual = 0, yActual = 0;
    const obtenerXReal = (clientX) => clientX - $canvas.getBoundingClientRect().left;
    const obtenerYReal = (clientY) => clientY - $canvas.getBoundingClientRect().top;
    let haComenzadoDibujo = false; // Bandera que indica si el usuario está presionando el botón del mouse sin soltarlo
    
$canvas.addEventListener("mousedown", evento => {
    // En este evento solo se ha iniciado el clic, así que dibujamos un punto
    xAnterior = xActual;
    yAnterior = yActual;
    xActual = obtenerXReal(evento.clientX);
    yActual = obtenerYReal(evento.clientY);
    contexto.beginPath();
    contexto.fillStyle = COLOR;
    contexto.fillRect(xActual, yActual, GROSOR, GROSOR);
    contexto.closePath();
    // Y establecemos la bandera
    haComenzadoDibujo = true;
});

$canvas.addEventListener("mousemove", (evento) => {
    if (!haComenzadoDibujo) {
        return;
    }
    // El mouse se está moviendo y el usuario está presionando el botón, así que dibujamos todo

    xAnterior = xActual;
    yAnterior = yActual;
    xActual = obtenerXReal(evento.clientX);
    yActual = obtenerYReal(evento.clientY);
    contexto.beginPath();
    contexto.moveTo(xAnterior, yAnterior);
    contexto.lineTo(xActual, yActual);
    contexto.strokeStyle = COLOR;
    contexto.lineWidth = GROSOR;
    contexto.stroke();
    contexto.closePath();
});
["mouseup", "mouseout"].forEach(nombreDeEvento => {
    $canvas.addEventListener(nombreDeEvento, () => {
        haComenzadoDibujo = false;
    });
});

$canvas.addEventListener("mousedown", evento => {
    // En este evento solo se ha iniciado el clic, así que dibujamos un punto
    xAnterior = xActual;
    yAnterior = yActual;
    xActual = obtenerXReal(evento.clientX);
    yActual = obtenerYReal(evento.clientY);
    contexto.beginPath();
    contexto.fillStyle = COLOR_PINCEL;
    contexto.fillRect(xActual, yActual, GROSOR, GROSOR);
    contexto.closePath();
    // Y establecemos la bandera
    haComenzadoDibujo = true;
});

$canvas.addEventListener("mousemove", (evento) => {
    if (!haComenzadoDibujo) {
        return;
    }
    // El mouse se está moviendo y el usuario está presionando el botón, así que dibujamos todo

    xAnterior = xActual;
    yAnterior = yActual;
    xActual = obtenerXReal(evento.clientX);
    yActual = obtenerYReal(evento.clientY);
    contexto.beginPath();
    contexto.moveTo(xAnterior, yAnterior);
    contexto.lineTo(xActual, yActual);
    contexto.strokeStyle = COLOR_PINCEL;
    contexto.lineWidth = GROSOR;
    contexto.stroke();
    contexto.closePath();
});
["mouseup", "mouseout"].forEach(nombreDeEvento => {
    $canvas.addEventListener(nombreDeEvento, () => {
        haComenzadoDibujo = false;
    });
});

$( "#btnLimpiar" ).click(function() {
    
    // Colocar color blanco en fondo de canvas
    contexto.fillStyle = COLOR_FONDO;
    contexto.fillRect(0, 0, $canvas.width, $canvas.height);
});
//limpiarCanvas();

//$btnLimpiar.onclick = limpiarCanvas;

// Escuchar clic del botón para descargar el canvas
/*
$btnDescargar.onclick = () => {
    alert("ok");
    const enlace = document.createElement('a');
    // El título
    enlace.download = "Firma.png";
    // Convertir la imagen a Base64 y ponerlo en el enlace
    enlace.href = $canvas.toDataURL();
    // Hacer click en él
    enlace.click();
};
*/
$( "#btnDescargar" ).click(function() {
 
    const enlace = document.createElement('a');
    // El título
    
    enlace.download = $( "input[name*='folio']" ).val()+".png";
    // Convertir la imagen a Base64 y ponerlo en el enlace
    enlace.href = $canvas.toDataURL();
    // Hacer click en él
    enlace.click();
                                       
   });

</script>
@endsection


