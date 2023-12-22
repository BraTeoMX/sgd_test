@extends('layouts.main')
@section('content')
<!-- Card -->
<div class="card">
  <div class="card-header">
    <h5 class="card-header-title">Adjuntar archhivos</h5>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- <label for="file">Elige un archivo</label>
        <input type="file" name="file"> -->
        <div class="form-group">
          <label class="input-label" for="exampleFormControlInput1">Descripciòn documento</label>
          <input type="text" id="descripcion" name="descripcion" class="form-control" >
        </div>
        <!-- File Attachment Button -->
        <label class="custom-file-boxed" for="customFileInputBoxedEg">
        <span id="customFileBoxedEg">Clic para buscar documentos</span>
        <small class="d-block text-muted">Tamaño maximo del archivo 10MB</small>
        <input id="customFileInputBoxedEg" name="custom-file-boxed" type="file" class="js-file-attach custom-file-boxed-input"
        data-hs-file-attach-options='{
        "textTarget": "#customFileBoxedEg"
        }'>
        </label>
        <button type="submit" name="submit">Upload</button>
        <!-- End File Attachment Button -->
    </form>

  </div>
</div>
<!-- End Card -->
@endsection
@section('scriptBFile')
    <script src="{!! asset('/materialfront/assets/vendor/hs-file-attach/dist/hs-file-attach.min.js') !!}"></script>
    <script>
        // initialization of custom file
        $('.js-file-attach').each(function () {
            var customFile = new HSFileAttach($(this)).init();
        });
    </script>


@endsection