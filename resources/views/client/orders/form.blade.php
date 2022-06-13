@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <form action="{{route('orders.post')}}" method="POST">
                    @csrf
                    <div class="d-flex flex-wrap dark-background address-chooser checkout-form-background mb-4">
                        <p class="h2 w-100">{{__('Dirección de envío')}}</p>
                        <p class="w-100 receiver">{{__('Destinatario:')}} <b>{{$addresses[0]->receiver_name}}</b></p>
                        <p class="w-100 postal-code">{{__('Código postal:')}} <b>{{$addresses[0]->postal_code}}</b></p>
                        <select id="addresses" class="form-select w-50" name="addresses">
                            @foreach($addresses as $address)
                                <option id="{{$address->id}}" value="{{$address->address}}"
                                        data-address='{{json_encode($address)}}'
                                        name="{{$address->id}}">{{$address->address}}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn button-primary-outline-dark ms-3 button-new-address"><i
                                class="fa fa-plus-circle"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger ms-3 button-remove-address"><i
                                class="fa fa-trash"></i>
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
                        class="d-flex flex-column flex-wrap dark-background address-chooser checkout-form-background mb-4">
                        <p class="h2 mb-3 w-100">{{__('Revisa los productos')}}</p>
                        <div class="products">

                            @foreach (json_decode($products) as $productItem)

                                <div class="product-container mb-4 text-center">
                                    <div class="h4 mb-2">- {{json_decode($productItem->product)->name}}</div>
                                    <p class="h5 text-primary-dark"> Cantidad: {{$productItem->amount}}</p>
                                </div>

                                <input type="hidden" name="products[]"
                                       value='{{json_encode(['product' => $productItem->product,'amount' => $productItem->amount])}}'>
                            @endforeach

                            <div class="h3 float-end">Total: {{$total}}€</div>
                        </div>
                    </div>

                    <div class="d-block d-lg-none">
                        <div class="d-flex flex-wrap dark-background address-chooser checkout-form-background mb-4">
                            <p class="h2 mb-3 w-100">{{__('Resumen del pedido')}}</p>

                            <p class="w-100">{{__('Productos')}}: {{$amountProducts}}</p>
                            <p>{{__('Total')}}: {{$total}}€</p>

                        </div>
                    </div>

                    <div class="buttons-container float-end ">
                        <a href="{{url()->previous()}}" class="btn btn-secondary me-3">{{__('Volver')}}</a>
                        <button class="btn button-primary-dark">{{__('Tramitar pedido')}}</button>
                    </div>
                </form>

            </div>

            <div class="col-12 col-lg-4 d-none d-lg-block">
                <div class="d-flex flex-wrap dark-background address-chooser checkout-form-background mb-4">
                    <p class="h2 mb-3 w-100">{{__('Resumen del pedido')}}</p>

                    <p class="w-100">{{__('Productos')}}: {{$amountProducts}}</p>
                    <p>{{__('Total')}}: {{$total}}€</p>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script defer>

            document.querySelector('.button-new-address').addEventListener('click', openModalNewAddress);
            document.querySelector('.button-remove-address').addEventListener('click', openModalRemoveAddress);
            document.querySelector('#addresses').addEventListener('change', changeAddressData);

            function changeAddressData() {

                const receiver = document.querySelector('p.receiver b');
                const postal_code = document.querySelector('p.postal-code b');
                const addressesSelect = document.querySelector('#addresses');
                receiver.textContent = JSON.parse(addressesSelect.options[addressesSelect.selectedIndex].dataset.address).receiver_name;
                postal_code.textContent = JSON.parse(addressesSelect.options[addressesSelect.selectedIndex].dataset.address).postal_code;
            }

            function openModalNewAddress() {
                Swal.fire({
                    title: 'Nueva dirección',
                    html: `<div class="mb-2">
                                <label for="receiver-name" class="form-label">{{__('Destinatario')}}</label>
                                <input type="text" name="receiver-name" class="form-control" id="receiver-name">
                            </div>

                            <div class="mb-2">
                                <label for="address" class="form-label">{{__('Dirección')}}</label>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>

                            <div class="mb-2">
                                <label for="postal-code" class="form-label">{{__('Código Postal')}}</label>
                                <input type="tel" name="postal-code" class="form-control" id="postal-code">
                            </div>`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#2891de',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Añadir',
                    color: '#dee2e6',
                    iconColor: '#2891de',
                    background: '#24292d',
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{csrf_token()}}',
                            }
                        });

                        $.ajax({
                            url: '{{route('addresses.post')}}',
                            type: 'POST',
                            data: {
                                'receiver_name': document.querySelector('#receiver-name').value.trim(),
                                'address': document.querySelector('#address').value.trim(),
                                'postal_code': document.querySelector('#postal-code').value.trim(),
                            },
                        }).success(
                            data => {
                                document.querySelector('#addresses').innerHTML += data;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dirección creada',
                                    showConfirmButton: false,
                                    timer: 1100,
                                    color: '#dee2e6',
                                    iconColor: '#85ff3e',
                                    background: '#24292d',
                                });
                            }).fail(
                            () => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Compruebe que todos los campos son correctos',
                                    showConfirmButton: true,
                                    color: '#dee2e6',
                                    iconColor: '#d83131',
                                    background: '#24292d',
                                })
                            })
                    }
                })
            }

            function openModalRemoveAddress() {
                const addressOption = document.querySelector('#addresses').options[document.querySelector('#addresses').selectedIndex];
                const address = JSON.parse(addressOption.dataset.address);

                Swal.fire({
                    title: '¿Desea eliminar esta dirección?',
                    html: `<p>Destinatario: ${address.receiver_name}</p><p>Dirección: ${address.address}</p><p>Código postal: ${address.postal_code}</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2891de',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Eliminar',
                    color: '#dee2e6',
                    iconColor: '#ff852d',
                    background: '#24292d',
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{csrf_token()}}',
                            }
                        });

                        $.ajax({
                            url: '{{route('addresses.delete')}}',
                            type: 'DELETE',
                            data: {
                                'address': address.id
                            },
                        }).success(
                            () => {

                                addressOption.remove();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dirección eliminada',
                                    showConfirmButton: false,
                                    timer: 1100,
                                    color: '#dee2e6',
                                    iconColor: '#85ff3e',
                                    background: '#24292d',
                                });
                            }).fail(
                            () => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de conexión',
                                    text: 'Inténtelo de nuevo',
                                    showConfirmButton: true,
                                    color: '#dee2e6',
                                    iconColor: '#d83131',
                                    background: '#24292d',
                                })
                            })
                    }
                });

            }

        </script>
    @endpush

@endsection
