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
        .carousel-control-prev:not(:hover),
        .carousel-control-next:not(:hover) {
            opacity: 0;
            transition: opacity 0.3s;
        }


        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
            transition: opacity 0.3s;
        }

        @media (max-width: 500px) {
            .carousel-inner .carousel-item > div {
                display: none;
            }
            .carousel-inner .carousel-item > div:first-child {
                display: block;
            }
        }

        .carousel-inner .carousel-item.active,
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev {
            display: flex;
        }

        /* medium and up screens */
        @media (min-width: 501px) {
            
            .carousel-inner .carousel-item-end.active,
            .carousel-inner .carousel-item-next {
                transform: translateX(50%);
            }
            
            .carousel-inner .carousel-item-start.active, 
            .carousel-inner .carousel-item-prev {
                transform: translateX(-50%);
            }
        }

        .carousel-inner .carousel-item-end,
        .carousel-inner .carousel-item-start { 
            transform: translateX(0);
        }

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
        document.addEventListener('DOMContentLoaded', () => {

            let items = document.querySelectorAll('.carousel .carousel-item')
            items.forEach((el) => {
                const minPerSlide = 2
                let next = el.nextElementSibling
                for (var i=1; i<minPerSlide; i++) {
                    if (next == null) {
                        // wrap carousel by using first child
                        next = items[0]
                    }
                    let cloneChild = next.cloneNode(true)
                    el.appendChild(next.cloneNode(true).children[0])
                    console.log(el)
                    next = next.nextElementSibling
                }
            })

            document.querySelector('.carousel-control-next').addEventListener('click', () => {
                console.log(document.querySelectorAll('.carousel .carousel-item.active .col .cards')[0].display = "none")
            })
        })

        document.querySelector("square-button").onclick = function () {
            location.href = "";
        };

    </script>


  
@endsection

@section('content')
<div class="container">
    <div class="row mx-auto my-auto justify-content-center">
        <div id="recipeCarousel" class="carousel slide p-0" data-bs-ride="carousel" style=" width: 70%">
            <div class="carousel-inner" role="listbox">
                @foreach($curses as $cursa)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="col">
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
                </div>
                @endforeach
            </div>
            @if(count($curses) > 2)
            <button class="carousel-control-prev" type="button" data-bs-target="#recipeCarousel" data-bs-slide="prev" style="background: #333;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recipeCarousel" data-bs-slide="next" style="background: #333;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
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


@endsection