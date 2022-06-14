@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-wrap dark-background address-chooser checkout-form-background mb-4">
            <p class="h2 mb-5 w-100">{{__('Factura del pedido').' #'.session()->get('order')->id}}</p>
            <div class="products">
<div class=" d-flex justify-content-between">
    <div class="h3 mb-3">{{__('Producto')}}</div>
    <div class="h3 mb-3">{{__('Precio')}}</div>
</div>
                <div class="row">
                @foreach (session()->get('products') as $productItem)
                    <div class="product-container mb-4 d-flex justify-content-between">
                        <div class="product-container">
                            <div class="h5 mb-2 text-break pe-5">- {{$productItem['product']->name}}</div>
                            <p class="text-primary-dark"> Cantidad: {{$productItem['amount']}}</p>
                        </div>
                        <div class="price">
                            <p class="h3 text-primary-dark">{{$productItem['product']->price * $productItem['amount']}}€</p>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="h3 float-end text-primary-dark border-top pt-4 w-100 text-end">Total: {{session()->get('total')}}€</div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            localStorage.clear();
        </script>
    @endpush
@endsection
