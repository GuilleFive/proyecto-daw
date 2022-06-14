@extends('layouts.app')

@section('content')
    <div class="container mb-3">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="products-list">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title h2">Tu carrito está vacío</h5>
                            <div class="products"></div>
                        </div>
                        <div class="card-footer">
                            <p class="float-end h4 total-cost">Total: 0,00€</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-lg-0 col-lg-4">
                <div class="checkout hide">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title h4 total-cost">Total: 0,00€</h5>
                            <form action="{{route('orders.form')}}" id="checkout-form" method="GET"></form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

            window.addEventListener('load', getCart);

            function getCart() {
                if (localStorage.getItem('cart')) {

                    document.querySelector('.products-list .card-title').textContent = 'Carrito de Retkon';

                    const cart = JSON.parse(localStorage.getItem('cart'));
                    const products = document.querySelector('.products');
                    const form = document.querySelector('#checkout-form');

                    let total = 0;

                    products.innerHTML = '<div class="d-flex flex-column">';
                    form.innerHTML = ``;
                    for (const productItem of cart) {
                        const oProduct = JSON.parse(productItem.product);

                        form.innerHTML += `<input type="hidden" name="products[]" value='${JSON.stringify({
                            id: oProduct.id,
                            amount: productItem.amount
                        })}'>`;
                        products.innerHTML += `<div class="row align-items-center align-content-center mb-5"><div class="col-12 col-md-5">${oProduct.name}</div><div class="d-none d-md-block col-md-2"></div> <div class="col-12 col-md-5 mt-3 mt-md-0 text-md-end"><button type="button" class="btn btn-outline-danger minus">-</button><input type="number" class="form-control input-amount" min="0" max="250" value="${productItem.amount}"> <input type="hidden" value="${oProduct.id}"> <button type="button" class="btn btn-outline-success plus">+</button> <button type="button" class="btn text-primary-dark remove-item">Eliminar de la lista</button></div></div>`;

                        total += oProduct.price * productItem.amount;
                    }

                    products.innerHTML += '</div>';
                    form.innerHTML += `<input type="hidden" name="total" value='${Math.round(total * 100) / 100}'><button class="btn button-primary-dark float-end button-checkout">Tramitar pedido</button>`;

                    document.querySelectorAll('.total-cost').forEach(element => {
                        element.textContent = `Total: ${Math.round(total * 100) / 100}€`;
                    })
                    document.querySelector('.checkout').classList.remove('hide');


                    addEvents();

                } else {
                    document.querySelector('.products-list .card-title').textContent = 'Tu carrito está vacío';
                    document.querySelector('.total-cost').textContent = `Total: 0,00€`;
                    document.querySelector('.products').innerHTML = '';
                    document.querySelector('.checkout').classList.add('hide');
                    document.querySelector('#checkout-form').outerHTML = '';


                }
            }

            function addEvents() {

                document.querySelectorAll('.plus').forEach(element => {
                    element.addEventListener('click', () => addItem(element));
                });

                document.querySelectorAll('.minus').forEach(element => {
                    element.addEventListener('click', () => subtractItem(element));

                });

                document.querySelectorAll('.remove-item').forEach(element => {
                    element.addEventListener('click', () => removeItem(element));

                });

                document.querySelector('.input-amount').addEventListener('change', () => changeAmountManually(document.querySelector('.input-amount')));

            }

            function changeAmountManually(element) {
                const productId = element.parentElement.querySelector('input[type = hidden]').value;
                const cart = JSON.parse(localStorage.getItem('cart'));

                for (const productItem of cart) {
                    const oProduct = JSON.parse(productItem.product);
                    if (oProduct.id === parseInt(productId)) {
                        if (parseInt(element.value.trim()) <= 250 && parseInt(element.value.trim()) > 0)
                            productItem.amount = parseInt(element.value.trim());

                    }
                }
                localStorage.setItem('cart', JSON.stringify(cart));

                refreshCart()

            }

            function addItem(element) {
                const productId = element.parentElement.querySelector('input[type = hidden]').value;
                const cart = JSON.parse(localStorage.getItem('cart'));

                for (const productItem of cart) {
                    const oProduct = JSON.parse(productItem.product);
                    if (oProduct.id === parseInt(productId)) {
                        productItem.amount++
                    }

                    if (productItem.amount === 0) {
                        cart.splice(cart.indexOf(productItem), 1);
                    }

                }
                if (cart.length !== 0)
                    localStorage.setItem('cart', JSON.stringify(cart));
                else
                    localStorage.removeItem('cart');

                refreshCart();

            }

            function subtractItem(element) {
                const productId = element.parentElement.querySelector('input[type = hidden]').value;
                const cart = JSON.parse(localStorage.getItem('cart'));

                for (const productItem of cart) {
                    const oProduct = JSON.parse(productItem.product);
                    if (oProduct.id === parseInt(productId)) {
                        productItem.amount--
                    }
                    if (productItem.amount === 0) {
                        cart.splice(cart.indexOf(productItem), 1);
                    }

                }
                if (cart.length !== 0)
                    localStorage.setItem('cart', JSON.stringify(cart));
                else
                    localStorage.removeItem('cart');

                refreshCart();
            }

            function removeItem(element) {
                const productId = element.parentElement.querySelector('input[type = hidden]').value;
                const cart = JSON.parse(localStorage.getItem('cart'));

                for (const productItem of cart) {
                    const oProduct = JSON.parse(productItem.product);
                    if (oProduct.id === parseInt(productId)) {
                        cart.splice(cart.indexOf(productItem), 1);
                    }
                }
                if (cart.length !== 0)
                    localStorage.setItem('cart', JSON.stringify(cart));
                else
                    localStorage.removeItem('cart');

                refreshCart();
            }

            function refreshCart() {
                changeNumberItem();
                getCart();
            }

            @if(session()->get('product_name'))
            Swal.fire({
                icon: 'error',
                title: 'Error en el producto {{session()->get('product_name')}}',
                text: '{{session()->get('message')}}',
                showConfirmButton: true,
                color: '#dee2e6',
                iconColor: '#d83131',
                background: '#24292d',
            })
            @endif
        </script>
    @endpush
@endsection
