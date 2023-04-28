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
            margin: 0 auto;
        }
    </style>
    <script>
        const module = {}
    </script>
    <script type="module">
        let arr_filter = {}
        arr_filter['actiu'] = true
        function filter_object(object){
                    if(object[0]=="punt_sortida"){
                        return object[1].includes('ade')
                    }
                }
        $('.filter_input').on("input", function filter(e) {
            e.preventDefault();
            if(e.target.name == "actiu"){
                arr_filter[e.target.name] = e.target.checked;
            }
            else{
                arr_filter[e.target.name] = e.target.value;
            }
            ajaxFilter(arr_filter)
         });
         function ajaxFilter(arr){
            $.ajax({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               type:'POST',
               url:"{{route('curses.filtreAjax')}}",
               data:arr_filter,
               success:function(response) {
                let html_v = ""
                $.each(response, function (key, value) {  
                    let id_get = value.id                                              
                    html_v += `<tr><td class="text-center align-middle"><img src="{{ asset('`+value.img_mapa+`') }}" alt="img_mapa"></div></td> <td class="text-center align-middle"><div>` + value.punt_sortida + '</div></td> <td class="text-center align-middle"><div>' + value.max_participants + '</div></td> <td class="text-center align-middle"><div>' + value.longitud + '</div></td> <td class="text-center align-middle"><div>' + value.desnivell + '</div></td> <td class="text-center align-middle"><div>' + value.data_cursa + '</div></td> <td class="text-center align-middle"><div>' + value.hora_cursa + `<td class="text-center align-middle"><img src="{{ asset('`+value.cartell_promocio+`') }}" alt="img_mapa"></div></td> <td class="text-center align-middle"><div>` + value.cost_patrocini + '</div></td> <td class="text-center align-middle"><div>' + value.descripcio + '</td><td class="text-center align-middle"><div class="form-group form-check"><input type="checkbox" onclick="module.actiuClick(this)" name="actiu_table" id="actiu_table" value="' + (value.id) + '" '+ (value.actiu?"checked":"") +'></div></td><td class="text-center align-middle"><a class="nav-link" href="' +  route("curses.formulariEditar", {"id":value.id}) + '" ><button class="btn btn-warning">Editar</button></a></td><td class="text-center align-middle"></tr><tr><td class="text-center align-middle" colspan="'+(new Date()>=new Date(value.data_cursa)?"6":"12")+'"><a class="nav-link" href="'+route('curses.fotografies', {"id":value.id})+'" ><button class="w-100 btn btn-secondary">Veure participants</button></a></td>'+(new Date()>=new Date(value.data_cursa)?'<td class="text-center align-middle" colspan="6"><a class="nav-link" href="'+route('curses.fotografies', {"id":value.id})+'" ><button class="w-100 btn btn-secondary">Fotografies de la cursa</button></a></td>':'')+'</tr>';
                });
                $("#curses-list").html(html_v)
               },
               error: function(xhr, status, error){
                    console.log(error)
               }
            });
         }
         function actiuClick(e){
            // console.log(e.checked)
            $.ajax({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               type:'POST',
               url:"{{route('curses.checkedAjax')}}",
               data:{"id":e.value, "checked":e.checked},
               error: function(xhr, status, error){
                    console.log(error)
               }
            });
            ajaxFilter(arr_filter)
         }

         module.actiuClick = actiuClick;
    </script>

@endsection

@section('content')
        <div class="w-100 text-center">
            <h1>Curses</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-end w-100">
            <a class="nav-link" href="{{ route('curses.formulariAfegir') }}">
                <button class="btn btn-primary">Afegir</button>
            </a>
        </div>
        <form>
            <div class="form-group row">
                <div class="form-group col-md-4">
                <label for="date_from">Desde:</label>
                <input type="date" name="date_from" class="form-control filter_input" value="{{ request('date_from') }}">
                </div>
                <div class="form-group col-md-4">
                <label for="date_to">Fins:</label>
                <input type="date" name="date_to" class="form-control filter_input" value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group col-md-4">
                <label for="search">Punt de sortida:</label>
                <input type="text" name="search" class="form-control filter_input" value="{{ request('search') }}">
                </div>
                <div class="form-group col-md-4 form-check" style="display: flex; align-items: center;">
                <input type="checkbox" name="actiu" class="form-check-input filter_input" checked>
                <label class="form-check-label" for="actiu" style="margin-left: 5px;">Actiu</label>
                </div>
            </div>
        </form>


        <table class="table">
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
                    <th class="text-center">Actiu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="curses-list">
                @if(count($curses) == 0)
                    <tr><td colspan="12" class="text-center h2">No hi ha cap cursa</td></tr>
                @else
                    @foreach ($curses as $cursa)
                        <tr>
                            <td class="text-center align-middle overflow-auto"><img src="{{ asset($cursa->img_mapa) }}" alt="img_mapa"></td>
                            <td class="text-center align-middle"><div>{{ $cursa->punt_sortida }}</div></td>
                            <td class="text-center align-middle"><div>{{ $cursa->max_participants }} persones</div></td>
                            <td class="text-center align-middle"><div>{{ $cursa->longitud }}</div></td>
                            <td class="text-center align-middle"><div>{{ $cursa->desnivell }}</div></td>
                            <td class="text-center align-middle"><div>{{ $cursa->data_cursa }}</div></td>
                            <td class="text-center align-middle"><div>{{ $cursa->hora_cursa }}</div></td>
                            <td class="text-center align-middle overflow-auto"><img src="{{ asset($cursa->cartell_promocio) }}" alt="cartell_promocio"></td>
                            <td class="text-center align-middle"><div>{{ $cursa->cost_patrocini }}€</div></td>
                            <td class="text-center align-middle"><div>{{ $cursa->descripcio }}</div></td>
                            <td class="text-center align-middle">
                                <div class="form-group form-check">
                                    <input type="checkbox" onclick="module.actiuClick(this)" name="actiu_table" id="actiu_table" value="{{ $cursa->id }}" <?php $curses[0]["actiu"]?print("checked"):print("") ?>>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <a class="nav-link" href="{{ route('curses.formulariEditar', ['id'=>$cursa->id]) }}" ><button class="btn btn-warning">Editar</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" colspan="{{ (strtotime($cursa->data_cursa) <= strtotime('now')) ? 6 : 12 }}">
                                <a class="nav-link" href="{{ route('curses.participants', ['id'=>$cursa->id]) }}" ><button class="w-100 btn btn-secondary">Veure participants</button></a>
                            </td>
                            @if(strtotime($cursa->data_cursa) <= strtotime('now'))
                            <td class="text-center align-middle" colspan="6">
                                <a class="nav-link" href="{{ route('curses.fotografies', ['id'=>$cursa->id]) }}" ><button class="w-100 btn btn-secondary">Fotografies de la cursa</button></a>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
@endsection