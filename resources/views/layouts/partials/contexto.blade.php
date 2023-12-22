<div class="card">
    <div class="card-body">
        <div class="row m-t-10 contexto">
          <div class="col-3">
            <div class="flex-row ">
                <h6>Folio: <medium class="label">{{$rollogistica->folio_Intimark}}</medium></h6>
            </div>
          </div>
          <div class="col-3">
              <div class="flex-row ">
                  <h6>Material: <medium class="label">{{$rollogistica->conveniosDetalle->CatMateriales->material}}</medium></h6>
              </div>
          </div>
          <div class="col-3">
              <div class="flex-row ">
                  <h6>Planta: <medium class="label">{{$rollogistica->catCliente->nombre_comercial}}</medium></h6>
              </div>
          </div>
          <div class="col-3">
              <div class="flex-row ">
                  <h6>Destino: <medium class="label">{{(filled($rollogistica->cliente_destino_id))? $rollogistica->catClienteDestino->nombre_comercial:$rollogistica->catSucursale->sucursal}}</medium></h6>
              </div>
          </div>
        </div>
    </div>
</div>
