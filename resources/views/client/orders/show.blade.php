@extends('layouts.app')

@section('content')
    <div class="container">
        <button type="button" class="btn button-primary-outline-dark btn-print mb-4">Descargar factura <i
                class="ps-2 fas fa-file-pdf"></i></button>

        <div
            class="d-flex flex-column flex-wrap dark-background address-chooser checkout-form-background mb-4 printable">
            <div class="d-flex justify-content-between">
                <p class="h2 mb-5">{{__('Factura del pedido').' #'.$order->id}}</p>
                <p class="h2 mb-5">{{ date( 'd-m-Y', strtotime($order->order_date))  }}</p>
            </div>
            <div class="address mb-5">
                <div class="row text-start text-md-center">
                    <div class="col-12 col-md-4 receiver">
                        <p class="h5">Destinatario: {{$order->address->receiver_name}}</p>
                    </div>
                    <div class="col-12 col-md-4 address">
                        <p class="h5">Calle: {{$order->address->address}}</p>
                    </div>
                    <div class="col-12 col-md-4 postal-code">
                        <p class="h5">Código postal: {{$order->address->postal_code}}</p>
                    </div>
                </div>
            </div>
            <div class="products">
                <div class="d-flex justify-content-between">
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
        <a href="{{url()->previous() === 'http://proyecto-daw.test/orders/my_orders'? route('orders.mine'):route('home')}}"
           class="btn btn-secondary btn-print mb-4">Volver</a>
    </div>


    @push('scripts')
        <script>
            document.querySelector('.btn-print').addEventListener('click', () => {
                const printElement = document.querySelector(".printable");
                const pdf = new JsPDF('p', 'pt', [520, 600]);

                pdf.text("Factura", 230, 20);
                pdf.html(printElement, {
                    callback: function (doc) {
                        doc.save("Factura pedido #{{$order->id}}.pdf");
                    },
                    margin: [38, 10, 5, 10],
                    width: 500,
                    windowWidth: printElement.offsetWidth,
                });
            });
        </script>
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
