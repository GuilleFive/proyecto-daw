<nav class="navbar navbar-dark bg-dark navbar-expand-md shadow-sm navbar__container">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}<img class="navbar__icon-img" src="{{asset('Retkon Icon.png')}}">
        </a>
        <div class="d-flex pe-2 align-items-center">
            <div class="d-md-none order-last ms-3">
                <a href="{{route('cart')}}" class="text-primary-dark">
                    <i class="text-primary-dark fa fa-shopping-cart"></i>
                    <span class="position-absolute badge rounded-pill bg-danger">
                        <span class="cart-number-items">0</span>
                        <span class="visually-hidden">Cart</span>
                    </span>
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
            @include('layouts.navbar.sides.left_side')

            <!-- Right Side Of Navbar -->
                @include('layouts.navbar.sides.right_side')
            </div>
    </div>
</nav>
