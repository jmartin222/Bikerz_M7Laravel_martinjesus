@php
    $numItems = count($curses);
    $lastIndex = $numItems - 1;

    $months = ["Gener", "Febrer", "Març", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre"]
@endphp

@extends('layouts.frontend')

@section('title', 'Curses')

@section('head')
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>

        .square-button {
            background-color: #121212;
            color: #ededed;
            border: 1px solid #ededed;
            border-radius: 0;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .square-button:hover {
            background-color: #b60505;
            border: 1px solid #b60505;
            cursor: pointer;
        }
    </style>

    <script>

    </script>


  
@endsection

@section('content')
<div class="container">
    <div class="row">
        @if(count($curses) > 0)
            @foreach ($curses as $cursa)
                <div class="col-md-4 mb-4">
                    <div class="card text-center p-1" style="background: #121212">
                        <div class="card-img">
                            <img src="{{ asset($cursa->cartell_promocio) }}" class="img-fluid" alt="...">
                        </div>
                        <?php $de_mes = in_array(intval(explode('-', $cursa->data_cursa)[1])-1, [9,3,7])?'d\'':'de '?>
                        <div class="card-text font-weight-bold lead" style="color: #ededed;">{{explode('-', $cursa->data_cursa)[2]}} {{$de_mes}}{{$months[intval(explode('-', $cursa->data_cursa)[1])-1]}} de {{explode('-', $cursa->data_cursa)[0]}}</div>
                        <div class="d-flex justify-content-center">
                            <button onclick="location.href = `{{ route('frontend.cursa', ['id'=>$cursa->id]) }}`" type="button" class="w-50 square-button">Més informació</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>


@endsection