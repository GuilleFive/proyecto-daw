@extends('layouts.app')

@section('content')
    <div class="container mb-3">
        <div class="row">
            <div class="col-12 col-xl-5 mb-5 mb-xl-0">
                <div class="image-container product-view bg-dark p-3">IMAGEN</div>

            </div>

            <div class="col-12 col-xl-5 mb-5 mb-xl-0">
                <div class="product-specs product-view bg-dark p-3">
                    <div class="title mb-5">
                        <p class="h1">{{json_decode($product)->name}}</p>
                    </div>
                    <div class="price mb-5">
                        <p class="h4">{{json_decode($product)->price}}€</p>
                    </div>
                    <div class="description mb-5">
                        <p class="h5 text-break">{{json_decode($product)->description}}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-2">
                <div class="actions-container d-flex flex-column product-view bg-dark p-3">
                    <div class="price">
                        <p class="h2">{{json_decode($product)->price}}€</p>
                    </div>

                    <div class="stock mb-2">
                        <p class="h4 @if(json_decode($product)->stock<6) text-danger @else text-success @endif">@if(json_decode($product)->stock<6) Quedan {{json_decode($product)->stock}} @else En stock. @endif</p>
                    </div>

                    <div class="delivery">
                        <p class="">{{__('Entrega de 2 a 5 días laborales')}}</p>
                    </div>

                    <div class="align-self-end">
                        <button type="button" data-product='{{json_encode($product)}}' class="btn button-primary-outline-dark add-cart"><i class="fa fa-cart-plus"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script defer>
        document.querySelector('.add-cart').addEventListener('click', () => {

            const arrayProducts = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : [];
            const product = JSON.parse(document.querySelector('.add-cart').dataset.product);

            if (arrayProducts.length === 0)
                arrayProducts.push({'product': product, 'amount': 1});
            else if (checkNewProduct(arrayProducts, product))
                arrayProducts.push({'product': product, 'amount': 1});

            localStorage.setItem('cart', JSON.stringify(arrayProducts));

            changeNumberItem();
        })
    </script>
    @push('scripts')
    @endpush
@endsection
