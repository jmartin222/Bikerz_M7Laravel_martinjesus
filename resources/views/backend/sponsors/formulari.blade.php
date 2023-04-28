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

    /* #gmap_canvas{
        display: none;
    } */
  </style>

  <script type="module">
    $( document ).ready(function() {
        let logo_url = "<?php echo old('logo')?>"
        if(logo_url != ""){
            logo_url = "<?php echo asset(old('logo'))?>"
            let dtr = new DataTransfer()
            fetch(logo_url).then(response => response.blob()).then(blob => {
                let file = new File([blob], logo_url.split("/")[logo_url.split("/").length-1], { type: blob.type });
                dtr.items.add(file)
                $("#logo").prop("files", dtr.files)
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
                    <div class="card-header">{{strpos($route_form, "editar") ?"Editar asseguradora":"Afegir asseguradora"}} </div>
                    
                    <div class="card-body">
                        <form action="{{$route_form}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="old_logo" value="{{ old('logo') }}">
                            <div class="form-group row">
                                @if(old('logo') == '')
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="cif">CIF</label>
                                            <input type="text" name="cif" id="cif" class="form-control @error('cif') is-invalid @enderror" value="{{old('cif')}}" />
                                            @error('cif')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <!-- <div id="gmap_canvas"><iframe width="542.5vw" height="300" src=""></iframe></div> -->
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="cif" id="cif" class="form-control" value="{{old('cif')}}" />
                                @endif
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nom">Nom</label>
                                        <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{old('nom')}}" />
                                        @error('nom')
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
                                        <label for="adreca">Adreça</label>
                                        <input type="text" name="adreca" id="adreca" class="form-control @error('adreca') is-invalid @enderror" value="{{old('adreca')}}" step='0.001' />
                                        @error('adreca')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="logo">Logo</label><br />
                                        <input type="file" name="logo" id="logo" class="form-control-file @error('logo') is-invalid @enderror" /><br />
                                        @error('logo')
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
                                        <label for="primera_plana">Primera plana</label>
                                        <div class="form-group d-flex">
                                            <input type="checkbox" name="primera_plana" id="primera_plana" <?php old('primera_plana')?print("checked"):print("") ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary w-100">{{old('logo') != "" ?"Editar sponsor":"Afegir sponsor"}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- <script>
    document.getElementById("CIF").oninput = function() {
        let val = document.getElementById("CIF").value;
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
    document.getElementById("CIF").onclick = function() {
        let val = document.getElementById("CIF").value;
        let gmap = document.getElementById("gmap_canvas");
        if(val == ""){
            gmap.style.display = "none";
        }
        else{
            gmap.style.display = "block"
        }
        
    }
    document.getElementById("CIF").onchange = function() {
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