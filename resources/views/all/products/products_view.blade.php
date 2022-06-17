@extends('layouts.app')

@section('content')
    <div class="container mb-3">
        <div class="row">
            <div class="col-12 col-xl-5 mb-5 mb-xl-0">
                <div class="image-container product-view bg-dark p-3">
                    <img class="img-fluid rounded-3" alt="{{__('Imagen del prodcuto')}}" src="{{$product->getMedia()[0]->getFullUrl()}}">
                </div>
            </div>

            <div class="col-12 col-xl-5 mb-5 mb-xl-0">
                <div class="product-specs product-view bg-dark p-3">
                    <div class="title mb-5">
                        <p class="h1">{{$product->name}}</p>
                    </div>
                    <div class="price mb-5">
                        <p class="h4">{{$product->price}}€</p>
                    </div>
                    <div class="description mb-5">
                        <p class="h5 text-break">{{$product->description}}</p>
                    </div>

                    <a href="{{url()->previous() === 'http://proyecto-daw.test/products'? route('products'):route('home')}}" class="btn btn-secondary">Volver</a>

                </div>
            </div>
            @can('create_products')
            @else
                <div class="col-12 col-xl-2">
                    <div class="actions-container d-flex flex-column product-view bg-dark p-3">
                        <div class="price">
                            <p class="h2">{{$product->price}}€</p>
                        </div>

                        <div class="stock mb-2">
                            <p class="h4 @if($product->stock<6) text-danger @else text-success @endif">@if($product->stock<6)
                                    Quedan {{$product->stock}} @else En stock. @endif</p>
                        </div>

                        <div class="delivery">
                            <p class="">{{__('Entrega de 2 a 5 días laborales')}}</p>
                        </div>

                        <div class="align-self-end">
                            <button type="button" data-product='{{json_encode($product)}}'
                                    class="btn button-primary-outline-dark add-cart"><i class="fa fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>


    @push('scripts')
        @can('create_products')
        @else
            <script defer>

                document.querySelector('.add-cart').addEventListener('click', () => {

                    const arrayProducts = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : [];
                    const product = JSON.parse(document.querySelector('.add-cart').dataset.product);

                    if (arrayProducts.length === 0)
                        arrayProducts.push({'product': JSON.stringify(product), 'amount': 1});
                    else if (checkNewProduct(arrayProducts, JSON.stringify(product)))
                        arrayProducts.push({'product': JSON.stringify(product), 'amount': 1});

                    localStorage.setItem('cart', JSON.stringify(arrayProducts));

                    changeNumberItem();
                })
            </script>
        @endcan
    @endpush
@endsection
