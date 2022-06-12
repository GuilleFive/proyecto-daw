@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <form action="{{route('orders.post')}}"  method="POST">
                    @csrf
                    <div class="d-flex flex-wrap dark-background address-chooser checkout-form-background mb-4">
                        <p class="h2 w-100">{{__('Dirección de envío')}}</p>
                        <p class="w-100">{{__('Destinatario:')}} <b>{{$addresses[0]->facturation_name}}</b></p>
                        <select class="form-select w-50" name="address">
                            @foreach($addresses as $address)
                                <option id="{{$address->id}}" name="{{$address->id}}">{{$address->name}}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn button-primary-outline-dark ms-3"><i
                                class="fa fa-plus-circle"></i>
                        </button>
                    </div>

                    <div class="d-flex flex-wrap dark-background address-chooser checkout-form-background mb-4">
                        <p class="h2 mb-3 w-100">{{__('Método de pago')}}</p>
                        <select class="form-select w-50" name="payment">
                            <option id="1" name="1">Visa</option>
                            <option id="2" name="2">Mastercard</option>
                            <option id="3" name="3">Paypal</option>
                        </select>
                    </div>

                    <div
                        class="d-flex flex-column flex-wrap dark-background address-chooser checkout-form-background mb-5">
                        <p class="h2 mb-3 w-100">{{__('Revisa los productos')}}</p>
                        <div class="products">

                            @foreach (json_decode($products) as $productItem)

                                <div class="product-container mb-4 text-center">
                                    <div class="h4 mb-2">- {{json_decode($productItem->product)->name}}</div>
                                    <p class="h5 text-primary-dark"> Cantidad: {{$productItem->amount}}</p>
                                </div>
                            @endforeach

                            <div class="h3 float-end">Total: {{$total}}€</div>
                        </div>
                    </div>

                    <div class="buttons-container float-end">
                        <a href="{{url()->previous()}}" class="btn btn-secondary me-3">{{__('Volver')}}</a>
                        <button class="btn button-primary-dark">{{__('Tramitar pedido')}}</button>
                    </div>
                </form>

            </div>

            <div class="col-12 col-lg-4"></div>

        </div>
    </div>
@endsection
