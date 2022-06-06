@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-md-6">
                <div class="categories-pie"></div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="days-bar"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center mt-5">
                <h2 class="h1">Producto más vendido este mes:</h2>
                <p class="top-sales h4"></p>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('scripts.charts')
@endpush
