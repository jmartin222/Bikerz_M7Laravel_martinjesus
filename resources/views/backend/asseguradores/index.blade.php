@extends('layouts.backend')

@section('title', 'asseguradores')

@section('head')
    <style>
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
               url:"{{route('asseguradores.filtreAjax')}}",
               data:arr_filter,
               success:function(response) {
                let html_v = ""
                if(response.length == 0){
                    html_v = '<tr><td colspan="12" class="text-center h2">No hi ha cap asseguradora</td></tr>'
                }
                else{
                    $.each(response, function (key, value) {  
                        let cif= value.CIF                                              
                        html_v += `<tr><td class="text-center align-middle"><div>` + cif + '</div></td> <td class="text-center align-middle"><div>' + value.nom + '</div></td> <td class="text-center align-middle"><div>' + value.adreca + '</div></td> <td class="text-center align-middle"><div>' + value.preu_per_cursa + '</div></td> <td class="text-center align-middle"><div class="form-group form-check"><input type="checkbox" onclick="module.actiuClick(this)" name="actiu_table" id="actiu_table" value="' + (cif) + '" '+ (value.actiu?"checked":"") +'></div></td><td class="text-center align-middle"><a class="nav-link" href="' +  route("asseguradores.formulariEditar", {"cif":cif}) + '" ><button class="btn btn-warning">Editar</button></a></td><td class="text-center align-middle"></tr>';
                    });
                }
                $("#asseguradores-list").html(html_v)
               },
               error: function(xhr, status, error){
                    console.log(error)
               }
            });
         }
         function actiuClick(e){
            // console.log(e.chvecked)
            $.ajax({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               type:'POST',
               url:"{{route('asseguradores.checkedAjax')}}",
               data:{"cif":e.value, "checked":e.checked},
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
            <h1>Asseguradores</h1>
        </div>
        <div class="col-md-4 d-flex w-100 justify-content-end">
            <a class="nav-link" href="{{ route('asseguradores.formulariAfegir') }}" style="display: inline-block; text-decoration: none;">
            <button class="btn btn-primary" style="width: 100%;">Afegir</button>
            </a>
        </div>
        <form>
            <div class="form-group row">
                <div class="form-group col-md-4">
                <label for="search_cif">CIF:</label>
                <input type="text" name="search_cif" class="form-control filter_input" value="{{ request('search_cif') }}">
                </div>
                <div class="form-group col-md-4">
                <label for="search_nom">Nom:</label>
                <input type="text" name="search_nom" class="form-control filter_input" value="{{ request('search_nom') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group col-md-4">
                <label for="search_adreca">Adreça:</label>
                <input type="text" name="search_adreca" class="form-control filter_input" value="{{ request('search_adreca') }}">
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
                    <th class="text-center">CIF</th>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Adreça</th>
                    <th class="text-center">Preu per cursa</th>
                    <th class="text-center">Actiu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="asseguradores-list">
                @if(count($asseguradores) == 0)
                    <tr><td colspan="12" class="text-center h2">No hi ha cap asseguradora</td></tr>
                @else
                    @foreach ($asseguradores as $asseguradora)
                        <tr>
                            <td class="text-center align-middle"><div>{{ $asseguradora->CIF }}</div></td>
                            <td class="text-center align-middle"><div>{{ $asseguradora->nom }}</div></td>
                            <td class="text-center align-middle"><div>{{ $asseguradora->adreca }}</div></td>
                            <td class="text-center align-middle"><div>{{ $asseguradora->preu_per_cursa }}€</div></td>
                            <td class="text-center align-middle">
                                <div class="form-group form-check">
                                    <input type="checkbox" onclick="module.actiuClick(this)" name="actiu_table" id="actiu_table" value="{{ $asseguradora->CIF }}" <?php $asseguradores[0]["actiu"]?print("checked"):print("") ?>>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <a class="nav-link" href="{{ route('asseguradores.formulariEditar', ['cif'=>$asseguradora->CIF]) }}" ><button class="btn btn-warning">Editar</button></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
@endsection