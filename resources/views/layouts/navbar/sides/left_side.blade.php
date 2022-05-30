<ul class="navbar-nav me-auto">
    @can('create_products')
        <li class="nav-item"><a class="nav-link" href="{{route('products.create')}}">{{__('Alta productos')}}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('products')}}">{{__('Listado productos')}}</a></li>
        @can('create_admins')
            <li class="nav-item"><a class="nav-link" href="{{route('users')}}">{{__('Listado usuarios')}}</a></li>
        @else
            <li class="nav-item"><a class="nav-link" href="{{route('users')}}">{{__('Listado clientes')}}</a></li>
        @endcan
        <li class="nav-item"><a class="nav-link" href="{{route('orders')}}">{{__('Listado pedidos')}}</a></li>
    @else
        <li class="nav-item"><a class="nav-link" href="{{route('home')}}">{{__('Productos')}}</a></li>
    @endcan
</ul>
