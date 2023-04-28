@php
    $months = ["Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre"];
    
    $de_mes = in_array(intval(explode('-', $cursa->data_cursa)[1])-1, [9,3,7])?' d\'':' de ';
    
    $date_str = explode('-', $cursa->data_cursa)[2] . $de_mes. $months[intval(explode('-', $cursa->data_cursa)[1])-1] .' de ' . explode('-', $cursa->data_cursa)[0];

    $hour_str = ' a ' . (explode(':', $cursa->hora_cursa)[0]=='01'?'la ':'les ') . explode(':', $cursa->hora_cursa)[0] . (explode(':', $cursa->hora_cursa)[1]!='00'?'.'.explode(':', $cursa->hora_cursa)[1]:'') . 'h';

    $de_mes_monthearly = in_array(intval(explode('-', date('Y-m-d', strtotime($cursa->data_cursa . ' -1 month')))[1])-1, [9,3,7])?' d\'':' de ';
    
    $date_str_monthearly = explode('-', date('Y-m-d', strtotime($cursa->data_cursa . ' -1 month')))[2] . $de_mes_monthearly. $months[intval(explode('-', date('Y-m-d', strtotime($cursa->data_cursa . ' -1 month')))[1])-1] .' de ' . explode('-', date('Y-m-d', strtotime($cursa->data_cursa . ' -1 month')))[0];

    $hour_str_monthearly = ' a ' . (explode(':', $cursa->hora_cursa)[0]=='01'?'la ':'les ') . explode(':', $cursa->hora_cursa)[0] . (explode(':', $cursa->hora_cursa)[1]!='00'?'.'.explode(':', $cursa->hora_cursa)[1]:'') . 'h';
@endphp

@extends('layouts.frontend')

@section('title', 'Curses')

@section('head')

<style>

.form-check-input[type="radio"] {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  width: 0;
  height: 0;
  margin: 0;
  padding: 0;
}

.form-check-label {
  display: inline-block;
  position: relative;
  margin-bottom: 0;
  cursor: pointer;
  width: 6rem;
  height: 3rem;
  line-height: 3rem;
  font-size: 1rem;
  text-align: center;
  padding: 0;
  color: #000;
}

.form-check-input[type="radio"]:checked + .form-check-label {
    background-color: #000;
    color: #fff;
}

.form-check-inline {
  display: inline-block;
  margin-right: 1rem;
}

.row-element-bikerz{
    padding-left: 1.5rem;
    margin-right: 1.25rem;
}

.row-text-bikerz{
    font-size: 18px;
    font-style: inherit;
    font-weight: inherit;
}

.img-promocio{
    width: 95%;
    height: 95%;
}

</style>

<script type="module">
    $(document).ready(function() {
        // Show the text input for option 1 by default
        $('#proOptionContent').show();
        
        // Show/hide the content based on the selected radio button
        $('input[name="option"]').on('change', function() {
            if ($(this).val() === 'pro') {
                $('#proOptionContent').show();
                $('#openOptionContent').hide();
            } else if ($(this).val() === 'open') {
                $('#proOptionContent').hide();
                $('#openOptionContent').show();
            }
        });
    });

</script>

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <img src="{{ asset($cursa->cartell_promocio) }}" class="img-fluid img-promocio" alt="...">
            </div>
            <div class="col-sm-6">
                <div class="row py-2">
                    <div class="col-sm-12">
                        <div class="row-element-bikerz">
                            <p class="row-text-bikerz">{{$cursa->descripcio}}</p>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-sm-12">
                        <div class="row-element-bikerz">
                            <p class="row-text-bikerz">Vacants disponibles: {{($cursa->max_participants-$n_inscripcions) < 0?'0':$cursa->max_participants-$n_inscripcions}}</p>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-sm-12">
                        <div class="row-element-bikerz">
                            <?php ?>
                            <p class="row-text-bikerz">Data: {{$date_str . $hour_str}}</p>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-sm-12">
                        <div class="row-element-bikerz">
                            <p class="row-text-bikerz">Punt de sortida: {{$cursa->punt_sortida}}</p>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-sm-12">
                        <div class="row-element-bikerz">
                            <p class="row-text-bikerz">Circuit de {{$cursa->longitud}} amb {{$cursa->desnivell}} de Desnivell</p>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                @if(strtotime('now') < strtotime($cursa->data_cursa) && strtotime('now') >= strtotime($cursa->data_cursa . ' -1 month') && ($cursa->max_participants-$n_inscripcions) > 0)
                <div class="row py-2">
                    <div class="col-sm-6">
                        <div>
                            <div class="card">
                                <div class="card-header">Inscribir-se</div>
                                
                                <div class="card-body">    
                                    <form action="{{route('corredors.pagament')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" class="form-control" id="cursa_id" name="cursa_id" value="{{$cursa->id}}" />
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="form-group row-element-bikerz">
                                                    <label for="dni_participant">DNI</label>
                                                    <input type="text" class="form-control  @error('dni_participant') is-invalid @enderror" name="dni_participant" id="dni_participant" class="form-control" value="{{old('dni_participant')}}" />
                                                    @error('dni_participant')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <!-- <div id="gmap_canvas"><iframe width="542.5vw" height="300" src=""></iframe></div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input optionCursa" type="radio" name="option" id="proOption" value="pro" checked>
                                                    <label class="form-check-label rounded-pill text-center border border-dark optionCursa" for="proOption">PRO</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="option" id="openOption" value="open">
                                                    <label class="form-check-label rounded-pill text-center border border-dark" for="openOption">OPEN</label>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="form-group row-element-bikerz" id="proOptionContent">
                                                        <label for="proOptionInput">Introdueix el teu numero federat:</label>
                                                        <input type="text" class="form-control @error('proOptionInput') is-invalid @enderror" name="proOptionInput" id="proOptionInput">
                                                        @error('proOptionInput')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group row-element-bikerz" id="openOptionContent" style="display: none;">
                                                        <label for="openOptionSelect">Selecciona la asseguradora:</label>
                                                        <select class="form-control form-select" name="openOptionSelect" id="openOptionSelect">
                                                            @foreach($asseguradores as $asseguradora)
                                                                <option value="{{$asseguradora->CIF}}">{{$asseguradora->nom}} - {{$asseguradora->preu_per_cursa}}€</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-danger w-100">Inscribir-se</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                @elseif(strtotime('now') >= strtotime($cursa->data_cursa))
                    <div class="row py-2">
                        <div class="col-sm-6">
                            <div class="row row-element-bikerz">
                                <div class="col-sm-6 d-inline-flex align-items-center">
                                    <a class="nav-link h-100" href="{{ route('frontend.cursa.fotografies', ['id'=>$cursa->id]) }}" ><button class="w-100 h-100 btn btn-secondary">Fotografies de la cursa</button></a>
                                </div>
                                <div class="col-sm-6 d-inline-flex align-items-center">
                                    <a class="nav-link h-100" href="{{ route('frontend.cursa.classificacio', ['id'=>$cursa->id]) }}" ><button class="w-100 h-100 btn btn-secondary">Classificació</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                @elseif(($cursa->max_participants-$n_inscripcions) == 0 && strtotime('now') < strtotime($cursa->data_cursa))
                    <div class="row py-2">
                        <div class="col-sm-6">
                            <div class="row-element-bikerz">
                                <p class="row-text-bikerz">No hi ha més vacants</p>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                @else
                    <div class="row py-2">
                        <div class="col-sm-12">
                            <div class="row-element-bikerz">
                                <p class="row-text-bikerz">Les inscripcions és podran fer a partir del {{$date_str_monthearly . $hour_str_monthearly}}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row py-2">
                    <div class="col-sm-12">
                        <div class="row-element-bikerz h-75">
                            <img src="{{asset($cursa->img_mapa)}}" alt="..." style="max-width:100%;max-height:100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="row py-2">
                    <div class="col-sm-12 d-flex justify-content-center mt-3"><h2>Patrocinadors:</h2></div>
                    @if(count($sponsors) > 0)
                        @foreach ($sponsors as $sponsor)
                            <div class="col-sm-3 mb-4 d-flex justify-content-center">
                                <img src="{{ asset($sponsor->logo) }}" alt="..." style="max-width: 100%; max-height: 100%; ">
                            </div>
                        @endforeach
                    @endif
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