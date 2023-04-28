@extends('layouts.backend')

@section('title', 'Curses')

@section('head')
  <style>
        img{
            width: 100px;
            height: 100px;
        }
        td > div{
            overflow-y: auto;
            max-height: 100px;
            max-width: 202px;
            word-wrap:break-word;
        }
        .tab {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid black;
            cursor: pointer;
        }

        .active {
            background-color: black;
            color: white;
        }

        .pat-tab{
            cursor: pointer;
            text-decoration:none;
        }

        #patrocinades{
            display: none;
        }
  </style>

  <script>
        function showTab(e, tabId) {
			var tables = document.getElementsByTagName("table");
			for (var i = 0; i < tables.length; i++) {
				if (tables[i].id === tabId) {
					tables[i].style.display = "table";
				} else {
					tables[i].style.display = "none";
				}
			}
			var tabs = document.getElementsByClassName("pat-tab");
			for (var i = 0; i < tabs.length; i++) {
				if (tabs[i].id === e.id) {
					tabs[i].classList.add("active");
				} else {
					tabs[i].classList.remove("active");
				}
			}
		}
  </script>

  <script type="module">
    $('#patrocinar').on('click', function (e) {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{route('sponsors.patrocinar')}}",
                data:{"cif_sponsor":"{{app('request')->query('cif_sponsor')}}", "id_cursa": e.target.value}, 
                success: function (response) {
                        location.reload(); 
                },
                error: function(xhr, status, error){
                        console.log(error)
                }
        })
    })
    $('#quitarPatrocini').on('click', function (e) {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:"{{route('sponsors.quitarPatrocini')}}",
                data:{"cif_sponsor":"{{app('request')->query('cif_sponsor')}}", "id_cursa": e.target.value}, 
                success: function (response) {
                        location.reload(); 
                },
                error: function(xhr, status, error){
                        console.log(error)
                }
        })
    })
  </script>
  
@endsection

@section('content')
    <div class="container mt-5">
        <ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active pat-tab" id="tab_no_patrocinades" data-toggle="tab" onclick="showTab(this,'no_patrocinades')">No patrocinades</a>
			</li>
			<li class="nav-item">
				<a class="nav-link pat-tab" id="tab_patrocinades" data-toggle="tab" onclick="showTab(this,'patrocinades')">Patrocinades</a>
			</li>
		</ul>
        <table class="table" id="no_patrocinades">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">Imatge de Mapa</th>
                        <th class="text-center">Punt Sortida</th>
                        <th class="text-center">Max Participants</th>
                        <th class="text-center">Longitud</th>
                        <th class="text-center">Desnivell</th>
                        <th class="text-center">Data Cursa</th>
                        <th class="text-center">Hora Cursa</th>
                        <th class="text-center">Cartell de Promoció</th>
                        <th class="text-center">Cost Patrocini</th>
                        <th class="text-center">Descripcio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="curses-list">
                    @if(count($curses) == 0 || count(array_intersect($curses->pluck('id')->toArray(), $patrocinis->pluck('id_cursa')->toArray())) == count($curses->pluck('id')->toArray()))
                        <tr><td colspan="12" class="text-center h2">No hi ha cap cursa</td></tr>
                    @else
                        @foreach ($curses as $cursa)
                            @if(!collect($patrocinis)->contains('id_cursa', $cursa->id))
                                <tr>
                                    <td class="text-center align-middle overflow-auto"><img src="{{ asset($cursa->img_mapa) }}" alt="img_mapa"></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->punt_sortida }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->max_participants }} persones</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->longitud }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->desnivell }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->data_cursa }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->hora_cursa }}</div></td>
                                    <td class="text-center align-middle overflow-auto"><img src="{{ asset($cursa->cartell_promocio) }}" alt="img_mapa"></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->cost_patrocini }}€</div></td>
                                    <td class="text-center align-middle"><div><div>{{ $cursa->descripcio }}</div></div></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-secondary" id="patrocinar" value="{{$cursa->id}}">Patrocinar</button>
                                    </td>
                                </tr>
                            @endif
                    @endforeach
                @endif
            </tbody>
        </table>
        <table class="table" id="patrocinades">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center">Imatge de Mapa</th>
                        <th class="text-center">Punt Sortida</th>
                        <th class="text-center">Max Participants</th>
                        <th class="text-center">Longitud</th>
                        <th class="text-center">Desnivell</th>
                        <th class="text-center">Data Cursa</th>
                        <th class="text-center">Hora Cursa</th>
                        <th class="text-center">Cartell de Promoció</th>
                        <th class="text-center">Cost Patrocini</th>
                        <th class="text-center">Descripcio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="curses-list">
                    @if(count($curses) == 0 || count(array_intersect($curses->pluck('id')->toArray(), $patrocinis->pluck('id_cursa')->toArray())) == 0)
                        <tr><td colspan="12" class="text-center h2">No hi ha cap cursa</td></tr>
                    @else
                        @foreach ($curses as $cursa)
                            @if(collect($patrocinis)->contains('id_cursa', $cursa->id))
                                <tr>
                                    <td class="text-center align-middle overflow-auto"><img src="{{ asset($cursa->img_mapa) }}" alt="img_mapa"></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->punt_sortida }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->max_participants }} persones</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->longitud }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->desnivell }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->data_cursa }}</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->hora_cursa }}</div></td>
                                    <td class="text-center align-middle overflow-auto"><img src="{{ asset($cursa->cartell_promocio) }}" alt="img_mapa"></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->cost_patrocini }}€</div></td>
                                    <td class="text-center align-middle"><div>{{ $cursa->descripcio }}</div></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-secondary" id="quitarPatrocini" value="{{$cursa->id}}">Quitar patrocini</button>
                                    </td>
                                </tr>
                            @endif
                    @endforeach
                @endif
            </tbody>
        </table>
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