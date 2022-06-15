@extends('layouts.app')

@section('content')
    <div class="container">
        <div
            class="d-flex flex-column flex-wrap dark-background address-chooser checkout-form-background mb-4">
            <p class="h2 mb-5 w-100">{{__('Factura del pedido').' #'.$order->id}}</p>
            <div class="address mb-5">
                <div class="d-flex justify-content-between">
                    <div class="receiver">
                        <p class="h5">Destinatario: {{$order->address->receiver_name}}</p>
                    </div>
                    <div class="address">
                        <p class="h5">Calle: {{$order->address->address}}</p>
                    </div>
                    <div class="postal-code">
                        <p class="h5">Código postal: {{$order->address->postal_code}}</p>
                    </div>
                </div>
            </div>
            <div class="products">
                <div class=" d-flex justify-content-between">
                    <div class="h3 mb-3">{{__('Producto')}}</div>
                    <div class="h3 mb-3">{{__('Precio')}}</div>
                </div>
                <div class="row">
                    @foreach ($products as $productItem)
                        <div class="product-container mb-4 d-flex justify-content-between">
                            <div class="product-container">
                                <div class="h5 mb-2 text-break pe-5">- {{$productItem['product']->name}}</div>
                                <p class="text-primary-dark"> Cantidad: {{$productItem['amount']}}</p>
                            </div>
                            <div class="price">
                                <p class="h3 text-primary-dark">{{$productItem['product']->price * $productItem['amount']}}
                                    €</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="h3 float-end text-primary-dark border-top pt-4 w-100 text-end">Total: {{$order->total}}€
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            @if(session()->get('done'))
            Swal.fire({
                icon: 'success',
                title: "{{session()->get('done')}}",
                showConfirmButton: false,
                timer: 1100,
                color: '#dee2e6',
                iconColor: '#85ff3e',
                background: '#24292d',
            });
            localStorage.clear();
            @endif
        </script>
    @endpush
@endsection
