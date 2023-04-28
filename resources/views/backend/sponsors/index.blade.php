@extends('layouts.backend')

@section('title', 'Sponsors')

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
        const php_patrocinis = {!! json_encode($patrocinis->toArray(), JSON_HEX_TAG) !!}
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
               url:"{{route('sponsors.filtreAjax')}}",
               data:arr_filter,
               success:function(response) {
                let html_v = ""
                if(response.length == 0){
                    html_v = '<tr><td colspan="12" class="text-center h2">No hi ha cap sponsor</td></tr>'
                }
                else{
                    $.each(response, function (key, value) {  
                        let cif= value.CIF                                              
                        html_v += `<tr><td class="text-center align-middle"><img src="{{ asset('`+value.logo+`') }}" alt="logo"></div></td> <td class="text-center align-middle"><div>` + cif + '</div></td> <td class="text-center align-middle"><div>' + value.nom + '</div></td> <td class="text-center align-middle"><div>' + value.adreca + '</div></td> <td class="text-center align-middle"><div class="form-group form-check"><input type="checkbox" onclick="module.primeraplanaClick(this)" name="primera_plana_table" id="primera_plana_table" value="' + (cif) + '" '+ (value.primera_plana?"checked":"") +'></div></td><td class="text-center align-middle"><div class="form-group form-check"><input type="checkbox" onclick="module.actiuClick(this)" name="actiu_table" id="actiu_table" value="' + (cif) + '" '+ (value.actiu?"checked":"") +'></div></td><td class="text-center align-middle"><a class="nav-link" href="' +  route("sponsors.formulariEditar", {"cif":cif}) + '" ><button class="btn btn-warning">Editar</button></a></td><td class="text-center align-middle"></tr><tr><td class="text-center align-middle" colspan="'+(php_patrocinis.some(a => a.cif_sponsor == cif)?"4":"7")+'"><a class="nav-link" href="'+route('sponsors.formulariPatrocini', {'cif_sponsor':cif})+'" ><button class="w-100 btn btn-secondary">Patrocinar cursa/es</button></a></td>'+(php_patrocinis.some(a => a.cif_sponsor == cif)?'<td class="text-center align-middle" colspan="3"><a class="nav-link" ><button class="w-100 btn btn-secondary">Descarregar factura PDF</button></a></td>':"")+'</tr>';
                    });
                }
                $("#sponsors-list").html(html_v)
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
               url:"{{route('sponsors.checkedAjax')}}",
               data:{"cif":e.value, "checked":e.checked},
               error: function(xhr, status, error){
                    console.log(error)
               }
            });
            ajaxFilter(arr_filter)
         }

         function primeraplanaClick(e){
            console.log(e.value)
            $.ajax({
               headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               type:'POST',
               url:"{{route('sponsors.checkedAjax2')}}",
               data:{"cif":e.value, "checked":e.checked},
               error: function(xhr, status, error){
                    console.log(error)
               }
            });
         }

         module.actiuClick = actiuClick;
         module.primeraplanaClick = primeraplanaClick;
    </script>

@endsection

@section('content')
        <div class="w-100 text-center">
            <h1>Sponsors</h1>
        </div>
        <div class="col-md-4 d-flex w-100 justify-content-end">
            <a class="nav-link" href="{{ route('sponsors.formulariAfegir') }}" style="display: inline-block; text-decoration: none;">
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
                    <th class="text-center">Logo</th>
                    <th class="text-center">CIF</th>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Adreça</th>
                    <th class="text-center">Primera plana</th>
                    <th class="text-center">Actiu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="sponsors-list">
                @if(count($sponsors) == 0)
                    <tr><td colspan="12" class="text-center h2">No hi ha cap sponsor</td></tr>
                @else
                    @foreach ($sponsors as $sponsor)
                        <tr>
                            <td class="text-center align-middle overflow-auto"><img src="{{ asset($sponsor->logo) }}" alt="logo"></td>
                            <td class="text-center align-middle"><div>{{ $sponsor->CIF }}</div></td>
                            <td class="text-center align-middle"><div>{{ $sponsor->nom }}</div></td>
                            <td class="text-center align-middle"><div>{{ $sponsor->adreca }}</div></td>
                            <td class="text-center align-middle">
                                <div class="form-group form-check">
                                    <input type="checkbox" onclick="module.primeraplanaClick(this)" name="primera_plana_table" id="primera_plana_table" value="{{ $sponsor->CIF }}" <?php $sponsor["primera_plana"]?print("checked"):print("") ?>>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="form-group form-check">
                                    <input type="checkbox" onclick="module.actiuClick(this)" name="actiu_table" id="actiu_table" value="{{ $sponsor->CIF }}" <?php $sponsor["actiu"]?print("checked"):print("") ?>>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <a class="nav-link" href="{{ route('sponsors.formulariEditar', ['cif'=>$sponsor->CIF]) }}" ><button class="btn btn-warning">Editar</button></a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" colspan="{{ (collect($patrocinis)->contains('cif_sponsor', $sponsor->CIF)) ? 4 : 7 }}">
                                <a class="nav-link" href="{{ route('sponsors.formulariPatrocini', ['cif_sponsor' => $sponsor->CIF]) }}" ><button class="w-100 btn btn-secondary">Patrocinar cursa/es</button></a>
                            </td>
                            @if(collect($patrocinis)->contains('cif_sponsor', $sponsor->CIF))
                            <td class="text-center align-middle" colspan="3">
                                <a class="nav-link" href="{{ route('sponsors.descarregarFactura', ['cif_sponsor' => $sponsor->CIF]) }}"><button class="w-100 btn btn-secondary">Descarregar factura PDF</button></a>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
@endsection