@extends('layouts.backend')

@section('title', 'Curses')

@section('head')
<style>
    .dropzone {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
    }

    .dropzone.has-image {
        border-color: #4caf50;
    }

    .dropzone .dz-preview {
        display: inline-block;
        margin: 0 10px;
    }
    .dropzone .dz-preview:hover .dz-progress {
        display: none;
    }
    #documentDropzone .dz-progress {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
</style>
<script>
    const module = {}
</script>
<script type="module">
    var myDropzone = Dropzone.options.documentDropzone = {
        url: "{{ route('curses.afegirFotos') }}",
        params: {"id_cursa": "{{app('request')->query('id')}}"},
        paramName: "images",
        acceptedFiles : "image/*",
        uploadMultiple: true,
        parallelUploads: 100,
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        dictRemoveFile: "Eliminar",
        dictCancelUpload: "Cancelar",
        autoProcessQueue: false,
        clickable: '#select_photos',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("addedfile", function(file) {
                if (!file.type.match(/image.*/)) {
                    this.removeFile(file);
                }
            });
            this.on('success', function (file, response) {
                console.log(response);
                location.reload()
            });

            this.on('error', function (file, response) {
                console.log(response);
            });
        }
    };

    document.getElementById('upload_photos').addEventListener('click', function (e) {
        document.querySelector('#documentDropzone .dz-progress').style.opacity = 1;
        Dropzone.forElement(document.querySelector('#documentDropzone')).processQueue();
    });
    function eliminarFoto(path){
        console.log(path);
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{route('curses.deletePhoto')}}",
                data:{"img":path},
                success: function (response) {
                        location.reload(); 
                },
                error: function(xhr, status, error){
                        console.log(error)
                }
        })
    }

    module.eliminarFoto = eliminarFoto;
</script>
@endsection

@section('content')
<div class="w-100 text-center">
    <h1>Fotografies</h1>
</div>
<div class="container">
  <form enctype="multipart/form-data" id="dropzoneForm">
    @csrf
    <div class="form-group">
      <div class="needsclick dropzone" id="documentDropzone">
        <div class="dz-message needsclick">
          <i class="fa fa-cloud-upload fa-3x"></i>
          <h3>Arrosega la/es fotografia/es aquí</h3>
        </div>
      </div>
    </div>
    <div class="form-group">
        <br />
        <button id="select_photos" type="button" class="btn btn-secondary w-100">Seleccionar fotografies</button>
    </div>
    <div class="form-group">
        <br />
      <button id="upload_photos" type="button" class="btn btn-primary w-100">Pujar fotografies</button>
    </div>
  </form>
  <br />

    <div class="row">
        @foreach ($fotografies as $fotografia)
            <div class="col-md-4 mb-4">
                <img src="{{ asset('images/curses/fotografies/'.app('request')->query('id').'/'.$fotografia) }}" alt="{{ $fotografia }}" style="max-width: 100%; max-height: 100%; ">
                <br />
                <button type="button" onclick="module.eliminarFoto('<?php echo 'images/curses/fotografies/'.app('request')->query('id').'/'.$fotografia ?>')"  class="btn btn-danger w-100">Eliminar</button>

            </div>
            
        @endforeach
    </div>
</div>
@endsection