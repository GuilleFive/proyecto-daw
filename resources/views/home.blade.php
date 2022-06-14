@extends('layouts.app')

@section('content')
    @include('all.filters')
    <div class="container mb-3 products-home-container">
        <div class='spinner-wrapper'>
            <div class="spinner"></div>
        </div>
        <div class="row products-home">

        </div>

    </div>
    @push('scripts')
        @include('scripts.home_products')
    @endpush
@endsection
