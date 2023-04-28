@extends('layouts.frontend')

@section('title', 'Curses')

@section('head')
    <style>
        td > div{
            overflow-y: auto;
            max-height: 100px;
            max-width: 202px;
            word-wrap:break-word;
            margin: 0 auto;
        }

        .active_menu{
            background:#000000;
        }

        .active_menu > *{
            color:white;
        }

        .not_active_menu:hover{
            background: #303030;
            transition: all 0.3s;
        }

        .not_active_menu:hover > *{
            color:white;
            transition: all 0.3s;
        }

        #navbarParticipants > .navbar-nav{
            border-bottom: 1px solid #000000	
        }
    </style>
    <script>
        const module = {}
    </script>
    <script type="module">
        let arr_filter = {}
        // function filter_object(object){
        //             if(object[0]=="punt_sortida"){
        //                 return object[1].includes('ade')
        //             }
        //         }
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
            <h1>Classificació</h1>
        </div>
        <br>
        <form>
            <nav class="navbar navbar-expand-md navbar-light shadow-sm">
                <div class="container">
                    <div class="row w-100">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="collapse navbar-collapse justify-content-center" id="navbarParticipants">
                                <ul class="navbar-nav w-100 justify-content-center">
                                    <li class="nav-item {{$filtre == 'general'?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa])}}">General</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 'Home'?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>'Home'])}}">Masculina</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 'Dona'?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>'Dona'])}}">Femenina</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 20?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>20])}}">Masters 20</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 30?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>30])}}">Masters 30</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 40?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>40])}}">Masters 40</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 50?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>50])}}">Masters 50</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 60?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>60])}}">Masters 60</a>
                                    </li>
                                    <li class="nav-item {{$filtre == 'punts'?'active_menu':'not_active_menu'}}">
                                        <a class="nav-link" href="{{route('frontend.cursa.classificacio', ['id'=>$id_cursa, 'filtre'=>'punts'])}}">Per punts</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </form>


        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Posicio</th>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Sexe</th>
                    <th class="text-center">Data de naixement</th>
                    <th class="text-center">Temps</th>
                    <th class="text-center">Punts</th>
                </tr>
            </thead>
            <tbody id="curses-list">
                @if(count($participants) == 0)
                    <tr><td colspan="12" class="text-center h2">No hi ha cap participant</td></tr>
                @else
                    @foreach ($participants as $participant)
                        <tr>
                            <td class="text-center align-middle">{{ $participant->posicio }}º</td>
                            <td class="text-center align-middle"><div>{{ $participant->nom }}</div></td>
                            <td class="text-center align-middle"><div>{{ $participant->sexe }}</div></td>
                            <td class="text-center align-middle"><div>{{ $participant->data_naixement }}</div></td>
                            <td class="text-center align-middle">{{ number_format((float)$participant->temps, 2, '.', '') }} minuts</td>
                            <td class="text-center align-middle"><div>{{ $participant->punts }}</div></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
@endsection