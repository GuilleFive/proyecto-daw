<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Retkon</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{asset('js/components.js')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
          integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    @stack('styles')
    <link rel="icon" href="{{asset('Retkon Icon.png')}}">
</head>
<body>
<div id="app">
    @include('layouts.navbar.navbar')

    <div class="app__container">
        @yield('content')
    </div>
</div>
@include('layouts.footer')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="	https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script>
    @can('create_products')
    @else
    window.addEventListener('load', changeNumberItem);

    function changeNumberItem() {
        const numberItems = document.querySelectorAll('.cart-number-items');
        const products = JSON.parse(localStorage.cart || null);

        if (products) {
            let cartAmount = 0;
            for (const productItem of products) {
                cartAmount += productItem.amount;
            }
            numberItems.forEach(element => {
                element.textContent = `${cartAmount}`
            });
        } else
            numberItems.forEach(element => {
                element.textContent = `0`
            });

    }
    @endcan
</script>
<script defer>
    function checkNewProduct(arrayProducts, product) {

        for (const element of arrayProducts) {

            if (element.product === product) {
                element.amount++;
                return false;
            }
        }

        return true;
    }
</script>
@stack('scripts')
</body>
</html>
