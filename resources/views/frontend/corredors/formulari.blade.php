@extends('layouts.frontend')

@section('title', 'Afegir corredors')

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
  </script>
  
@endsection

@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registrar corredor</div>
                    
                    <div class="card-body">    
                        <form action="{{$route_form}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="dni">DNI</label>
                                            <input type="text" class="form-control @error('dni') is-invalid @enderror" name="dni" id="dni" class="form-control" value="{{old('dni')}}" />
                                            @error('dni')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <!-- <div id="gmap_canvas"><iframe width="542.5vw" height="300" src=""></iframe></div> -->
                                        </div>
                                    </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nom">Nom</label>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" id="nom" class="form-control" value="{{old('nom')}}" />
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
                                        <input type="text" class="form-control @error('adreca') is-invalid @enderror" name="adreca" id="adreca" class="form-control" value="{{old('adreca')}}" />
                                        @error('adreca')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="data_naixement">Data de naixement</label>
                                        <input type="date" class="form-control @error('data_naixement') is-invalid @enderror" name="data_naixement" id="data_naixement" class="form-control" value="{{old('data_naixement')}}" />
                                        @error('data_naixement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ str_replace(['-', 'years','El camp data de naixement ha de ser una data abans de '],['','anys','Has de tindre miním '],$message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="sexe">Sexe</label>
                                        <br/>
                                        <input type="radio" class="form-check-input @error('sexe') is-invalid @enderror" name="sexe" class="form-control" value="Home" /> Home
                                        <input type="radio" class="form-check-input @error('sexe') is-invalid @enderror" name="sexe" class="form-control" value="Dona" /> Dona
                                        @error('sexe')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary w-100">Registrar corredor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- <script>
    document.getElementById("dni").oninput = function() {
        let val = document.getElementById("dni").value;
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
    document.getElementById("dni").onclick = function() {
        let val = document.getElementById("dni").value;
        let gmap = document.getElementById("gmap_canvas");
        if(val == ""){
            gmap.style.display = "none";
        }
        else{
            gmap.style.display = "block"
        }
        
    }
    document.getElementById("dni").onchange = function() {
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