@extends('layouts.frontend')

@section('title', 'Curses')

@section('head')
<style>
</style>
<script>
</script>
@endsection

@section('content')
<div class="w-100 text-center">
    <h1>{{count($fotografies)>0?'Fotografies':'No hi ha cap fotografia'}}</h1>
</div>
<div class="container">
    <div class="row">
        @if(count($fotografies) > 0)
            @foreach ($fotografies as $fotografia)
                <div class="col-md-4 mb-4">
                    <img src="{{ asset('images/curses/fotografies/'.app('request')->query('id').'/'.$fotografia) }}" alt="{{ $fotografia }}" style="max-width: 100%; max-height: 100%; ">
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection