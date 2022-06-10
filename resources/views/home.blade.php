@extends('layouts.app')

@section('content')
    <div class="container ps-4 mb-3">
        <div class="row">
            @foreach($products as $product)
                <div class="cold-12 col-md-6 col-xl-3 mb-5">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100">
                            <img class="card-img-top dark-background" src="" alt="Card image cap">
                            <div class="card-body d-flex flex-wrap justify-content-center align-content-around h-100">
                                <h5 class="card-title">{{$product->name}}</h5>
                                <p class="card-text w-100">{{$product->description}}</p>
                                <div class="align-self-end w-100">
                                    @if($product->stock < 6)
                                    <p class="card-text text-danger"
                                       title="¡Quedan pocas unidades!">
                                        Solo {{$product->stock}} unidades</p>
                                    @else
                                    <p class="card-text text-success">
                                        En stock</p>
                                    @endif

                                    <button type="button" data-product="{{json_encode($product)}}"
                                            class="btn button-primary-outline-dark align-self-end float-md-end add-cart">
                                        <i class="fa fa-cart-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.add-cart').forEach(element => {
                element.addEventListener('click', () => {

                    const arrayProducts = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : [];
                    const product = element.dataset.product;

                    arrayProducts.push({'product': product});
                    localStorage.setItem('cart', JSON.stringify(arrayProducts));

                    changeNumberItem();
                })
            })


        </script>
    @endpush
@endsection
