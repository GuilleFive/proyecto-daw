@extends('layouts.app')

@section('content')
    <div class="container-fluid px-5 pb-5 text-center">
        <h2 class="">{{$product->name}}</h2>
        <div class="d-flex justify-content-around flex-wrap flex-row w-100">
                <div class="container-img-desc">
                    <div class="square"></div>
                    <div class="">
                        <p>{{$product->description}}</p>
                    </div>
                    <div class="">
                    <p>Stock: {{$product->stock}}</p>
                    <p>Precio: {{$product->price}}€</p>
                </div>
                </div>

        </div>
    </div>
@endsection
