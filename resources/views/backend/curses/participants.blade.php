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

        .active_menu{
            background:#000000;
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
            <h1>Participants</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-end w-100">
            <a class="nav-link" href="{{ route('curses.descarregarZipQR', ['id'=>$id_cursa]) }}">
                <button class="btn btn-secondary">Descarregar Zip de QRs</button>
            </a>
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">DNI</th>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Sexe</th>
                    <th class="text-center">Data de naixement</th>
                    <th class="text-center">Punts</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="curses-list">
                @if(count($participants) == 0)
                    <tr><td colspan="12" class="text-center h2">No hi ha cap participant</td></tr>
                @else
                    @foreach ($participants as $participant)
                        <tr>
                            <td class="text-center align-middle">{{ $participant->DNI }}</td>
                            <td class="text-center align-middle"><div>{{ $participant->nom }}</div></td>
                            <td class="text-center align-middle"><div>{{ $participant->sexe }}</div></td>
                            <td class="text-center align-middle"><div>{{ $participant->data_naixement }}</div></td>
                            <td class="text-center align-middle"><div>{{ $participant->punts }}</div></td>
                            <td class="text-center align-middle">
                                <a class="nav-link" href="{{ route('curses.descarregarQR', ['id_cursa'=>$id_cursa, 'dni_participant'=>$participant->DNI]) }}" ><button class="btn btn-secondary">QR</button></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
@endsection