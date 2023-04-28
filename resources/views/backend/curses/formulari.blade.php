@extends('layouts.backend')

@section('title', 'Curses')

@section('head')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
  <style>
    .form-group {
        margin-bottom: 0.3rem;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: .25rem;
        font-size: 90%;
        color: #dc3545;
    }

    /* #gmap_canvas{
        display: none;
    } */
  </style>

  <script type="module">
    $( document ).ready(function() {
        let img_mapa_url = "<?php echo old('img_mapa')?>"
        let cartell_promocio_url = "<?php echo old('cartell_promocio')?>"
        if(img_mapa != "" && cartell_promocio_url != ""){
            img_mapa_url = "<?php echo asset(old('img_mapa'))?>"
            cartell_promocio_url = "<?php echo asset(old('cartell_promocio'))?>"
            let dtr = new DataTransfer()
            fetch(img_mapa_url).then(response => response.blob()).then(blob => {
                let file = new File([blob], img_mapa_url.split("/")[img_mapa_url.split("/").length-1], { type: blob.type });
                dtr.items.add(file)
                $("#img_mapa").prop("files", dtr.files)
            })
            let dtr2 = new DataTransfer()
            fetch(cartell_promocio_url).then(response => response.blob()).then(blob => {
                let file = new File([blob], cartell_promocio_url.split("/")[cartell_promocio_url.split("/").length-1], { type: blob.type });
                dtr2.items.add(file)
                $("#cartell_promocio").prop("files", dtr2.files)
            })
        }
    });
  </script>
  
@endsection

@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{old('img_mapa') != "" && old('cartell_promocio') != ""?"Editar cursa":"Afegir cursa"}} </div>

                    <div class="card-body">
                        <form action="{{$route_form}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_cursa" value="{{ app('request')->query('id') }}">
                            <input type="hidden" name="old_img_mapa" value="{{ old('img_mapa') }}">
                            <input type="hidden" name="old_cartell_promocio" value="{{ old('cartell_promocio') }}">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="punt_sortida">Punt de sortida</label>
                                        <input type="text" name="punt_sortida" id="punt_sortida" class="form-control @if($errors->has('punt_sortida')) @if($errors->first('punt_sortida') !== 'El punt de sortida a la data indicada ya ha estat pres.') @error('punt_sortida') is-invalid @enderror @endif @endif" value="{{old('punt_sortida')}}" />
                                        @if($errors->has('punt_sortida'))
                                            @if ($errors->first('punt_sortida') !== 'El punt de sortida a la data indicada ya ha estat pres.')
                                                @error('punt_sortida')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            @endif
                                        @endif
                                        <!-- <div id="gmap_canvas"><iframe width="542.5vw" height="300" src=""></iframe></div> -->
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="max_participants">Participants maxíms</label>
                                        <input type="number" name="max_participants" id="max_participants" class="form-control @error('max_participants') is-invalid @enderror" value="{{old('max_participants')}}" />
                                        @error('max_participants')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="longitud">Longitud de cursa</label>
                                        <div class="form-group d-flex">
                                            <input type="number" name="longitud" id="longitud" class="form-control @error('longitud') is-invalid @enderror" value="{{old('longitud')}}" step='0.001' />
                                            <select class="form-control w-25 @error('longitud_type') is-invalid @enderror form-select" name="longitud_type" id="longitud_type">
                                                @if(!strpos($route_form, "editar"))
                                                        <option value="km">km</option>
                                                        <option value="m">m</option>
                                                    @else
                                                        @if(old('longitud_type') == 'km')
                                                            <option value="km" selected>km</option>
                                                            <option value="m">m</option>
                                                        @else
                                                            <option value="km">km</option>
                                                            <option value="m" selected>m</option>
                                                        @endif
                                                    @endif
                                            </select>
                                        </div>
                                        <div>
                                            @error('longitud')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @error('longitud_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="desnivell">Desnivell</label>
                                        <div class="form-group d-flex">
                                            <input type="text" name="desnivell" id="desnivell" class="form-control @error('desnivell') is-invalid @enderror" value="{{old('desnivell')}}" />
                                            
                                            <select class="form-control form-select w-25 @error('desnivell_type') is-invalid @enderror" name="desnivell_type" id="desnivell_type">
                                                    @if(!strpos($route_form, "editar"))
                                                        <option value="km">km</option>
                                                        <option value="m">m</option>
                                                    @else
                                                        @if(old('desnivell_type') == 'km')
                                                            <option value="km" selected>km</option>
                                                            <option value="m">m</option>
                                                        @else
                                                            <option value="km">km</option>
                                                            <option value="m" selected>m</option>
                                                        @endif
                                                    @endif
                                            </select>
                                        </div>
                                        <div>
                                            @error('desnivell')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            @error('desnivell_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cost_patrocini">Cost de patrocini</label>
                                        <input type="number" name="cost_patrocini" id="cost_patrocini" class="form-control @error('cost_patrocini') is-invalid @enderror" value="{{old('cost_patrocini')}}" step='0.01' />
                                        @error('cost_patrocini')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="data_cursa">Data de la cursa</label>
                                        <input type="datetime-local" class="form-control @error('data_cursa') is-invalid @enderror" value="{{old('data_cursa')}}" id="data_cursa" name="data_cursa" />
                                        @error('data_cursa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="img_mapa">Imatge de mapa</label><br />
                                        <input type="file" name="img_mapa" id="img_mapa" class="form-control-file @error('img_mapa') is-invalid @enderror" /><br />
                                        @error('img_mapa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cartell_promocio">Cartell de promoció</label><br />
                                        <input type="file" name="cartell_promocio" id="cartell_promocio" class="form-control-file @error('cartell_promocio') is-invalid @enderror" /><br />
                                        @error('cartell_promocio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcio">Descripció</label>
                                        <textarea name="descripcio" id="descripcio" class="form-control  @error('descripcio') is-invalid @enderror" rows="3">{{old('descripcio')}}</textarea>
                                        @error('descripcio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary w-100">{{old('img_mapa') != "" && old('cartell_promocio') != ""?"Editar cursa":"Afegir cursa"}}</button>
                            </div>
                            @if($errors->has('punt_sortida'))
                                @if ($errors->first('punt_sortida') === 'El punt de sortida a la data indicada ya ha estat pres.')
                                    <div class="alert alert-danger">*{{ $errors->first('punt_sortida') }}</div>
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- <script>
    document.getElementById("punt_sortida").oninput = function() {
        let val = document.getElementById("punt_sortida").value;
        let gmap = document.getElementById("gmap_canvas");
        console.log();
        if(val == ""){
            gmap.style.display = "none";
        }
        else{
            if(getComputedStyle(gmap).display == "none"){
                gmap.style.display = "block";
            }
            gmap.children[0].setAttribute("src", "https://maps.google.com/maps?q="+val.replace(" ","+")+"&output=embed")
        }
        
    }
    document.getElementById("punt_sortida").onclick = function() {
        let val = document.getElementById("punt_sortida").value;
        let gmap = document.getElementById("gmap_canvas");
        if(val == ""){
            gmap.style.display = "none";
        }
        else{
            gmap.style.display = "block"
        }
        
    }
    document.getElementById("punt_sortida").onchange = function() {
        document.getElementById("gmap_canvas").style.display = "none";

    }
</script> -->
<!-- @if ($errors->any())
    <div class="alert alert-danger">
            @php
                print($errors)
            @endphp
    </div>
@endif -->