@extends('layouts.app')

@section('content')
    @include('all.filters')
    <div class="container pt-5 mb-3 products-home-container">
        <div class='spinner-wrapper'>
            <div class="spinner"></div>
        </div>
        <div class="mb-5">
            <form id="form-search">
                <div class="search-container d-flex justify-content-between">
                    <input type="search" autocomplete="off" disabled id="search-input" placeholder="Buscar productos"
                           class="form-control search-input">
                    <button type="submit" disabled class="btn button-primary-dark button-search"><i class="fa fa-search"></i></button>
                </div>
            </form>

        </div>
        <div class="row products-home"></div>
    </div>
    @push('scripts')
        @include('scripts.home_products')
    @endpush
@endsection
