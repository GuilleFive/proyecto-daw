@extends('layouts.app')

@section('content')
    <div class="container mb-3">
        <div class="row">
            <div class="col-12 col-md-8">
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
            <div class="col-12 col-md-4">
                <div class="checkout"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            if(localStorage.getItem('cart')){
                const cart = JSON.parse(localStorage.getItem('cart'));
                const products = document.querySelector('.products');
                document.querySelector('.products-list .card-title').textContent = 'Carrito de Retkon';
                let total = 0;
                const arrayProducts = [];
                for (const cartElement of cart) {
                    let product = JSON.parse(cartElement.product);

                    arrayProducts[product.name]?arrayProducts[product.name]++:arrayProducts[product.name] = 1;
                    total += product.price;
                }
                for (const product of Object.keys(arrayProducts)) {
                    products.innerHTML += `<p>${product} x ${arrayProducts[product]}</p>`;
                }

                document.querySelector('.total-cost').textContent = `Total: ${total}€`;
            }
        </script>
    @endpush
@endsection
