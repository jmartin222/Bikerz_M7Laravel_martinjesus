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
    <h2>La inscripció s'ha realitzat correctament</h2>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6 d-inline-flex justify-content-center w-100">
            <a class="nav-link h-100" href="{{ route('frontend.descarregarPdf', ['htmlContent'=>$htmlContent,'htmlContentTitle'=>$htmlContentTitle]) }}" ><button class="w-100 h-100 btn btn-secondary">Descarregar PDF</button></a>
        </div>
    </div>
</div>
@endsection